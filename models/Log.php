<?php

namespace app\models;

use Yii;
use app\models\patronState\EstadoBase;
use app\components\behaviors\AuditoriaBehaviors;
/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $fechaingreso
 * @property string|null $fechaegreso
 * @property string $falla
 * @property string|null $observacion
 * @property int $id_equipo
 * @property int $id_estado
 * @property int|null $id_proveedor
 * @property Equipo $equipo
 * @property Estado $estado
 * @property Proveedor $proveedor
 * @property Contactohistorico[] $contactohistoricos
 */
class Log extends \yii\db\ActiveRecord
{


  public function behaviors()
    {

      return array(
             'AuditoriaBehaviors'=>array(
                    'class'=>AuditoriaBehaviors::className(),
                    ),
        );
   }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechaegreso', 'observacion'], 'default', 'value' => null],
            [['fechaingreso', 'falla', 'id_equipo', 'id_estado'], 'required'],
            [['fechaingreso', 'fechaegreso'], 'safe'],
            [['falla', 'observacion'], 'string'],
            // [[ 'id_proveedor'], 'default', 'value' => null],
            [
                'id_proveedor',
                'required',
                'when' => function ($model) {
                    return $model->id_estado == EstadoBase::ENVIADO_A;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#estado-log').val() == " . EstadoBase::ENVIADO_A . ";
                }",
                'message' => 'Debe elegir un proveedor cuando el estado es ENVIADO'
            ],
            [['id_equipo', 'id_estado', 'id_proveedor'], 'integer'],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['id_equipo' => 'id']],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::class, 'targetAttribute' => ['id_estado' => 'id']],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::class, 'targetAttribute' => ['id_proveedor' => 'id']],
            [['fechaingreso','fechaegreso'], 'validarSuperposicion' ,'skipOnEmpty'=>false ],
            ['id_estado', 'validarEstado'],
              ];
    }



    public function validarEstado($attribute, $params)
    {
        // Asegurate de que las constantes sean del mismo tipo (int/string)
        if ($this->id_estado == EstadoBase::REPARADO || $this->id_estado == EstadoBase::IRREPARABLE ) {
            // Si no hay fecha de egreso, agregamos error
            if (empty($this->fechaegreso)) {
                // Mostrar el error en el campo fechaegreso (más intuitivo)
                $this->addError('fechaegreso', 'Para transicionar a este estado debe tener fecha de egreso.');
            }
        }
        if (($this->id_estado == EstadoBase::EN_REPARACION || $this->id_estado == EstadoBase::PENDIENTE_DE_REPARACION || $this->id_estado == EstadoBase::ENVIADO_A) && !empty($this->fechaegreso)){
          $this->addError('id_estado', "{$this->estado->nombre} no puede tener fecha de egreso.");

        }
    }

    /**
   * Validador para evitar superposición de logs (formato de fecha sin hora).
   * Cambiado para que la comparación final sea estricta: other.end > thisStart
   * (es decir, si other.end == thisStart NO se considera solapamiento).
   */
  public function validarSuperposicion($attribute, $params)
  {
      if (empty($this->id_equipo) || empty($this->fechaingreso)) {
          return;
      }

      $parseDate = function($value) {
          $value = trim((string)$value);
          if ($value === '') return null;
          if (strpos($value, '/') !== false) {
              $d = \DateTime::createFromFormat('d/m/Y', $value);
              if ($d) return $d->setTime(0,0,0);
              $d = \DateTime::createFromFormat('d-m-Y', $value);
              if ($d) return $d->setTime(0,0,0);
              return null;
          }
          $d = \DateTime::createFromFormat('Y-m-d', $value);
          if ($d) return $d->setTime(0,0,0);
          try {
              $d = new \DateTime($value);
              return $d->setTime(0,0,0);
          } catch (\Exception $e) {
              return null;
          }
      };

      $fechaIngresoObj = $parseDate($this->fechaingreso);
      if (!$fechaIngresoObj) {
          $this->addError('fechaingreso', 'Fecha de ingreso inválida (esperado d/m/Y o Y-m-d).');
          return;
      }

      $fechaEgresoObj = null;
      if (!empty($this->fechaegreso)) {
          $fechaEgresoObj = $parseDate($this->fechaegreso);
          if (!$fechaEgresoObj) {
              $this->addError('fechaegreso', 'Fecha de egreso inválida (esperado d/m/Y o Y-m-d).');
              return;
          }
      }

      // coherencia interna: si existe egreso, debe ser >= ingreso (o si querés estrictamente >, cambiar aquí)
      if ($fechaEgresoObj !== null && $fechaEgresoObj < $fechaIngresoObj) {
          $this->addError('fechaegreso', 'La fecha de egreso debe ser mayor o igual a la fecha de ingreso.');
          return;
      }

      // convertir a formato Y-m-d (solo fecha) para comparar en SQL
      $thisStart = $fechaIngresoObj->format('Y-m-d');
      $thisEnd   = $fechaEgresoObj ? $fechaEgresoObj->format('Y-m-d') : '9999-12-31';

      // Query para encontrar cualquier log del mismo equipo que *solape* con [thisStart, thisEnd]
      // Condición de solapamiento adaptada a comparación estricta en el límite superior:
      // other.start <= thisEnd AND COALESCE(other.end, '9999-12-31') > thisStart
      $query = \app\models\Log::find()
          ->where(['id_equipo' => $this->id_equipo])
          ->andWhere(['<=', 'fechaingreso', $thisEnd])
          ->andWhere(new \yii\db\Expression("COALESCE(fechaegreso, '9999-12-31') > :thisStart", [':thisStart' => $thisStart]));

      if (!$this->isNewRecord) {
          $query->andWhere(['<>', 'id', $this->id]);
      }

      $conflict = $query->one();
      if ($conflict) {
          $otherStart = $conflict->fechaingreso;
          $otherEnd = $conflict->fechaegreso ?: 'abierto';
          $this->addError('fechaingreso', "La fecha de ingreso se solapa con el registro (ID: {$conflict->id}) " .
              "con rango {$otherStart} — {$otherEnd}.");
      }
  }

    public function afterSave($insert, $changedAttributes)
     {
         parent::afterSave($insert, $changedAttributes);
         // Tras crear o actualizar, aseguramos que el equipo tenga el estado más reciente
         $this->cambiarEstadoEquipoSave();
     }
    private function cambiarEstadoEquipoSave(){

      $ultimo = self::find()
              ->where(['id_equipo' => $this->id_equipo])
              ->orderBy(['fechaingreso' => SORT_DESC, 'id' => SORT_DESC])
              ->limit(1)
              ->one();

          // Si este registro es el último, aplicamos su estado al equipo
          if ($ultimo && $ultimo->id === $this->id) {
              Equipo::aplicarEstadoDesdeLog($this->id_equipo, $this->id_estado, $this->id);
          }
    }


     public function afterDelete()
     {
         parent::afterDelete();
         // Tras borrar, recalculamos el estado del equipo
         $this->cambiarEstadoEquipoDelete();
     }

     private function cambiarEstadoEquipoDelete(){
       // Buscar el último log restante
          $ultimo = self::find()
              ->where(['id_equipo' => $this->id_equipo])
              ->orderBy(['fechaingreso' => SORT_DESC, 'id' => SORT_DESC])
              ->limit(1)
              ->one();

          if ($ultimo) {
              Equipo::aplicarEstadoDesdeLog($this->id_equipo, $ultimo->id_estado, $ultimo->id);
          } else {
              // No quedan logs: volver al estado pendiente por defecto
              Equipo::aplicarEstadoDesdeLog($this->id_equipo, EstadoBase::PENDIENTE_DE_REPARACION, null);
          }

       }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fechaingreso' => Yii::t('app', 'Fecha de ingreso'),
            'fechaegreso' => Yii::t('app', 'Fecha de egreso'),
            'falla' => Yii::t('app', 'Falla'),
            'observacion' => Yii::t('app', 'Observacion'),
            'id_equipo' => Yii::t('app', 'Id Equipo'),
            'id_estado' => Yii::t('app', 'Estado'),
            'id_proveedor' => Yii::t('app', 'Proveedor'),
        ];
    }

    /**
     * Gets query for [[Equipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipo()
    {
        return $this->hasOne(Equipo::class, ['id' => 'id_equipo']);
    }

    /**
     * Gets query for [[Estado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Estado::class, ['id' => 'id_estado']);
    }

    /**
     * Gets query for [[Proveedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::class, ['id' => 'id_proveedor']);
    }
    /**
    * Gets query for [[Contactohistoricos]].
    *
    * @return \yii\db\ActiveQuery
    */
   public function getContactohistoricos()
   {
       return $this->hasMany(Contactohistorico::class, ['id_log' => 'id']);
   }

}
