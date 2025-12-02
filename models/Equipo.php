<?php

namespace app\models;

use Yii;
use DateTime;
use yii\helpers\Html;

/**
 * This is the model class for table "equipo".
 *
 * @property int $id
 * @property string $nserie
 * @property string|null $fechafabricacion
 * @property string|null $fecharegistro
 * @property string $codigo
 * @property int|null $id_marca
 * @property int|null $id_modelo
 * @property int|null $id_servicio
 * @property int $id_tipoequipo
 * @property int $id_estado
 * @property int|null $ultimo_log
 * @property AdjuntoEquipo[] $adjuntoEquipos
 * @property Adjunto[] $adjuntos
 * @property Adjunto[] $adjuntos0
 * @property Estado $estado
 * @property Log[] $logs
 * @property Marca $marca
 * @property Modelo $modelo
 * @property Servicio $servicio
 * @property Tipoequipo $tipoequipo
 * @property string|null $observacion
* @property bool|null $operativo
 */
class Equipo extends \yii\db\ActiveRecord
{





    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'id_marca', 'id_modelo', 'id_servicio', 'observacion' ,'ultimo_log' ,'codigo'], 'default', 'value' => null],
            [['id_estado'], 'default', 'value' => 1],
            // Validar formato de fecha si usás strings (opcional)
            [['nserie',  'id_tipoequipo'], 'required'],
            [['operativo'], 'boolean'],
            [['operativo'], 'default', 'value' => 1],
            [['nserie', 'codigo', 'observacion'], 'string'],
            [['fechafabricacion', 'fecharegistro'], 'safe'],
            [['id_marca', 'id_modelo', 'id_servicio', 'id_tipoequipo', 'id_estado'], 'default', 'value' => null],
            [['id_marca', 'id_modelo', 'id_servicio', 'id_tipoequipo', 'id_estado' ,'ultimo_log'], 'integer'],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estado::class, 'targetAttribute' => ['id_estado' => 'id']],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['id_marca' => 'id']],
            [['id_modelo'], 'exist', 'skipOnError' => true, 'targetClass' => Modelo::class, 'targetAttribute' => ['id_modelo' => 'id']],
            [['id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::class, 'targetAttribute' => ['id_servicio' => 'id']],
            [['id_tipoequipo'], 'exist', 'skipOnError' => true, 'targetClass' => Tipoequipo::class, 'targetAttribute' => ['id_tipoequipo' => 'id']],
            [['fecharegistro', 'fechafabricacion'], 'validateExclusiveDates', 'skipOnEmpty' => false],

        ];
    }


    /**
    * Validador que asegura que exactamente uno de los dos campos esté completo.
    * Se ejecuta por cada atributo (por eso se añaden errores a ambos si aplica).
    */
   public function validateExclusiveDates($attribute, $params)
   {
       $a = $this->fechafabricacion;
       $b = $this->fecharegistro;

       $emptyA = $a === null || $a === '' ;
       $emptyB = $b === null || $b === '' ;

       if ($emptyA && $emptyB) {
           // ninguno completado -> error
           $this->addError($attribute, 'Debe completar al menos uno: Fecha de fabricación o Fecha de registro.');
       } elseif (!$emptyA && !$emptyB) {
           // ambos completados -> error
           $this->addError($attribute, 'No puede completar ambos campos a la vez; deje uno vacío.');
       }
   }



   /**
      * Aplica el estado y el last_log_id al equipo dentro de una transacción.
      *
      * @param int $idEquipo
      * @param int|null $idEstado
      * @param int|null $idLog
      * @return bool
      * @throws \Throwable
      */
     public static function aplicarEstadoDesdeLog(int $idEquipo, ?int $idEstado, ?int $idLog = null): bool
     {
         $db = Yii::$app->db;
         $trans = $db->beginTransaction();
         try {
             // Bloqueamos la fila del equipo para evitar condiciones de carrera
             $equipo = self::find()->where(['id' => $idEquipo])->one();
             if (!$equipo) {
                 $trans->rollBack();
                 return false;
             }

             $equipo->id_estado = $idEstado ?? EstadoBase::ESTADO_PENDIENTE_REPARACION;
             $equipo->ultimo_log = $idLog;

             // Guardamos solo los atributos necesarios sin validación
             $equipo->save(false, ['id_estado', 'ultimo_log']);

             $trans->commit();
             return true;
         } catch (\Throwable $e) {
             $trans->rollBack();
             throw $e;
         }
     }

     /**
      * Obtiene el último log para este equipo según criterio (fechaingreso DESC, id DESC).
      * Útil si no usás last_log_id.
      *
      * @return Log|null
      */
     public function obtenerUltimoLogPorCriterio()
     {
         return Log::find()
             ->where(['id_equipo' => $this->id])
             ->orderBy(['fechaingreso' => SORT_DESC, 'id' => SORT_DESC])
             ->limit(1)
             ->one();
     }

  /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nserie' => Yii::t('app', 'Nº de serie'),
            'fechafabricacion' => Yii::t('app', 'Fecha de fabricación'),
            'fecharegistro' => Yii::t('app', 'Fecha de registro'),
            'codigo' => Yii::t('app', 'Código'),
            'id_marca' => Yii::t('app', 'Id Marca'),
            'id_modelo' => Yii::t('app', 'Id Modelo'),
            'id_servicio' => Yii::t('app', 'Id Servicio'),
            'id_tipoequipo' => Yii::t('app', 'Id Tipo de equipo'),
            'id_estado' => Yii::t('app', 'Id Estado'),
            'observacion' => Yii::t('app', 'Observación'),
            'operativo' => Yii::t('app', 'Operativo'),
            'ultimo_log' => Yii::t('app', 'Último Log'),
        ];
    }

    // tu método de instancia
       public function getEstiloFecha($tipofecha)
       {
           $color = \app\models\Parametrizacion::obtenerColor(
               $tipofecha,
               \app\models\Parametrizacion::MODELO_EQUIPO
           );
           if($color){
             return "color: " . $color .'; font-weight: bold;' ;

           }else {
              return '';
           }
       }

       public  function getrenderfechaF()
       {
           $estilo = $this->getEstiloFecha($this->fechafabricacion);
           $formatted = Yii::$app->formatter->asDate($this->fechafabricacion, 'php:d/m/Y');
           return Html::tag('span', $formatted, ['style' =>  $estilo]);
       }

       public  function getrenderfechaR()
       {
           $estilo = $this->getEstiloFecha($this->fecharegistro);
           $formatted = Yii::$app->formatter->asDate($this->fecharegistro, 'php:d/m/Y');
           return Html::tag('span', $formatted, ['style' =>  $estilo]);
       }

 public function  attributeColumns(){
   return [

       [
       'class'=>'\kartik\grid\DataColumn',
       'attribute'=>'id',
       ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'nserie',
       ],
       [
           'class' => '\kartik\grid\DataColumn',
           'attribute' => 'fechafabricacion',
           'label' => 'Fecha de fabricación',
           'value' => 'renderfechaF',
           'format' => 'raw',
           'filterInputOptions' => [
               'id' => 'fecha2',
               'class' => 'form-control',
               'autoclose' => true,
               'format' => 'dd/mm/yyyy',
               'startView' => 'year',
               'placeholder' => 'd/m/aaaa'
           ]
       ],
       [
           'class' => '\kartik\grid\DataColumn',
           'attribute' => 'fecharegistro',
           'label' => 'Fecha de registro',
           'value' => 'renderfechaR',
           'format' => 'raw',
           'filterInputOptions' => [
               'id' => 'fecha2',
               'class' => 'form-control',
               'autoclose' => true,
               'format' => 'dd/mm/yyyy',
               'startView' => 'year',
               'placeholder' => 'd/m/aaaa'
           ]
       ],

       [

           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'codigo',
       ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'marca',
           'value'=> 'marca.nombreurl',
           'format' => 'raw',
       ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'modelo',
           'value'=> 'modelo.nombreurl',
           'format' => 'raw',

        ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'servicio',
           'value'=> 'servicio.nombreurl',
           'format' => 'raw',
       ],

       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'tipoequipo',
           'value'=> 'tipoequipo.nombreurl',
           'format' => 'raw',
           'label'=>'Tipo de equipo'
       ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'estado',
           'value'=> 'estado.nombre'
       ],
       [
           'class'=>'\kartik\grid\DataColumn',
           'attribute'=>'observacion',
       ],
       [
           'class'=>'\kartik\grid\BooleanColumn',
           'attribute'=>'operativo',
           'trueLabel' => 'Sí',
           'falseLabel' => 'No',
           'trueIcon' => '<span class="label label-success" ">Sí</span>',
           'falseIcon' => '<span class="label label-danger" ">No</span>',
           'filterInputOptions' => [
              'class' => 'form-control',
               'prompt' => 'Seleccionar'
            ],
       ]
   ];


 }

    /**
     * Gets query for [[AdjuntoEquipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjuntoEquipos()
    {
        return $this->hasMany(AdjuntoEquipo::class, ['id_equipo' => 'id']);
    }

    /**
     * Gets query for [[Adjuntos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjuntos()
    {
        return $this->hasMany(Adjunto::class, ['id_equipo' => 'id']);
    }

    /**
     * Gets query for [[Adjuntos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjuntos0()
    {
        return $this->hasMany(Adjunto::class, ['id' => 'id_adjunto'])->viaTable('adjunto_equipo', ['id_equipo' => 'id']);
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
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::class, ['id_equipo' => 'id']);
    }

    /**
     * Gets query for [[Marca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarca()
    {
        return $this->hasOne(Marca::class, ['id' => 'id_marca']);
    }

    /**
     * Gets query for [[Modelo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelo()
    {
        return $this->hasOne(Modelo::class, ['id' => 'id_modelo']);
    }

    /**
     * Gets query for [[Servicio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::class, ['id' => 'id_servicio']);
    }

    /**
     * Gets query for [[Tipoequipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoequipo()
    {
        return $this->hasOne(Tipoequipo::class, ['id' => 'id_tipoequipo']);
    }

}
