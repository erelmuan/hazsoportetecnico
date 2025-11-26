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
 *
 * @property Equipo $equipo
 * @property Estado $estado
 */
class Log extends \yii\db\ActiveRecord
{

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
            Equipo::aplicarEstadoDesdeLog($this->id_equipo, EstadoBase::ESTADO_PENDIENTE_REPARACION, null);
        }

     }

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
            [['id_equipo', 'id_estado'], 'default', 'value' => null],
            [['id_equipo', 'id_estado'], 'integer'],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['id_equipo' => 'id']],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::class, 'targetAttribute' => ['id_estado' => 'id']],
            [['fechaingreso','fechaegreso'], 'validarSuperposicion' ,'skipOnEmpty'=>false ],
                    ['id_estado', 'validarEstado'],
        ];
    }


    public function validarEstado($attribute, $params)
    {
        // Asegurate de que las constantes sean del mismo tipo (int/string)
        if ($this->id_estado == EstadoBase::REPARADO || $this->id_estado == EstadoBase::IRREPARABLE) {
            // Si no hay fecha de egreso, agregamos error
            if (empty($this->fechaegreso)) {
                // Mostrar el error en el campo fechaegreso (más intuitivo)
                $this->addError('fechaegreso', 'Para transicionar a este estado debe tener fecha de egreso.');
            }
        }
        if ($this->id_estado == EstadoBase::PENDIENTE_DE_REPARACION && !empty($this->fechaegreso)){
          $this->addError('id_estado', 'PENDIENTE EN REPARACION no puede tener fecha de egreso.');

        }
    }

    /**
     * Validador para evitar superposición de logs (formato de fecha sin hora).
     */
    public function validarSuperposicion($attribute, $params)
    {
        // Requerimos id_equipo y fechaingreso para validar
        if (empty($this->id_equipo) || empty($this->fechaingreso)) {
            return;
        }

        // Helper: parsear fecha que puede venir 'd/m/Y' o 'Y-m-d'
        $parseDate = function($value) {
            $value = trim($value);
            if ($value === '') return null;
            // detectar formato con slash (d/m/Y)
            if (strpos($value, '/') !== false) {
                $d = \DateTime::createFromFormat('d/m/Y', $value);
                if ($d) return $d->setTime(0,0,0);
                // intentar formatos alternativos
                $d = \DateTime::createFromFormat('d-m-Y', $value);
                if ($d) return $d->setTime(0,0,0);
                return null;
            }
            // intentar Y-m-d
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if ($d) return $d->setTime(0,0,0);
            // intentar parseo libre (último recurso)
            try {
                $d = new \DateTime($value);
                return $d->setTime(0,0,0);
            } catch (\Exception $e) {
                return null;
            }
        };

        // Parsear ingreso
        $fechaIngresoObj = $parseDate($this->fechaingreso);
        if (!$fechaIngresoObj) {
            $this->addError('fechaingreso', 'Fecha de ingreso inválida (esperado d/m/Y o Y-m-d).');
            return;
        }

        // Parsear egreso (puede ser null)
        $fechaEgresoObj = null;
        if (!empty($this->fechaegreso)) {
            $fechaEgresoObj = $parseDate($this->fechaegreso);
            if (!$fechaEgresoObj) {
                $this->addError('fechaegreso', 'Fecha de egreso inválida (esperado d/m/Y o Y-m-d).');
                return;
            }
        }

        // 1) coherencia interna: egreso >= ingreso (si existe egreso)
        if ($fechaEgresoObj !== null && $fechaEgresoObj < $fechaIngresoObj) {
            $this->addError('fechaegreso', 'La fecha de egreso debe ser mayor o igual a la fecha de ingreso.');
            return;
        }

        // convertir a strings 'Y-m-d' para comparar en SQL
        $thisStart = $fechaIngresoObj->format('Y-m-d');
        $thisEnd   = $fechaEgresoObj ? $fechaEgresoObj->format('Y-m-d') : '9999-12-31';

        // Consulta: buscar otro log del mismo equipo cuya ventana [start,end] se solape
        // Condición de solapamiento para fechas (sin tiempo):
        // other.start <= thisEnd AND (other.end IS NULL OR other.end >= thisStart)
        $query = Log::find()
            ->where(['id_equipo' => $this->id_equipo])
            ->andWhere(['<=', 'fechaingreso', $thisEnd])
            ->andWhere(new \yii\db\Expression("COALESCE(fechaegreso, '9999-12-31') >= :thisStart"), [':thisStart' => $thisStart]);

        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }

        // obtener el registro conflictivo (si existe) para mensaje
        $conflict = $query->one();
        if ($conflict) {
            // construir mensaje con información útil (fecha y id)
            $otherStart = $conflict->fechaingreso;
            $otherEnd = $conflict->fechaegreso ?: 'abierto';
            $this->addError('fechaingreso', "La fecha de ingreso se superpone con el registro (ID: {$conflict->id}) " .
                "con rango {$otherStart} — {$otherEnd}.");
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

}
