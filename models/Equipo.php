<?php

namespace app\models;
use yii\db\Query;
use Yii;
use DateTime;
use yii\helpers\Html;
use app\models\AdjuntoEquipo;
use app\components\behaviors\AuditoriaBehaviors;
use app\models\patronState\EstadoBase;

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
 */

class Equipo extends \yii\db\ActiveRecord
{
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->id_estado = EstadoBase::PENDIENTE_DE_REPARACION;
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
              'id_tipoequipo' => Yii::t('app', 'Tipo de equipo'),
              'id_estado' => Yii::t('app', 'Id Estado'),
              'observacion' => Yii::t('app', 'Observación'),
              'ultimo_log' => Yii::t('app', 'Último Log'),
          ];
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
             $equipo = Equipo::find()->where(['id' => $idEquipo])->one();
             if (!$equipo) {
                 $trans->rollBack();
                 return false;
             }

             $equipo->id_estado = $idEstado ?? EstadoBase::ESTADO_PENDIENTE_REPARACION;
             //ULTIMO LOG ESTA EN VANO LO TENGO QUE SACAR.
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




 public function replicarBibliografia(int $idAdjunto): bool
   {
       // buscar equipos equivalentes (misma marca y modelo)
       $equiposEquivalentes = Equipo::find()
           ->where([
               'id_marca'  => $this->id_marca,
               'id_modelo' => $this->id_modelo,
           ])
           ->all();
       $transaction = Yii::$app->db->beginTransaction();
       try {
           foreach ($equiposEquivalentes as $equipo) {
               // defensa en profundidad: evitar duplicados
               $exists = AdjuntoEquipo::find()
                   ->where([
                       'id_adjunto' => $idAdjunto,
                       'id_equipo'  => $equipo->id,
                   ])->exists();
               if ($exists) {
                   continue;
               }
               $relacion = new AdjuntoEquipo();
               $relacion->id_adjunto = $idAdjunto;
               $relacion->id_equipo  = $equipo->id;
               if (!$relacion->save()) {
                   throw new \RuntimeException(
                       'Error guardando AdjuntoEquipo: ' .
                       implode('; ', $relacion->getFirstErrors())
                   );
               }
           }
           $transaction->commit();
           return true;
       } catch (\Throwable $e) {
           $transaction->rollBack();
           throw new \RuntimeException(
               'No se pudo replicar la bibliografía: ' . $e->getMessage(), 0, $e
           );
       }
   }


   public  function sincronizarAdjuntos(){
       //consultar si tengo adjuntos con el mismo modelo y marca.
       // en la tabla equipo,
       $eq= Equipo::find()
       ->where(["id_marca"=>$this->id_marca, "id_modelo"=>$this->id_modelo])
       ->andWhere(['<>', 'id', $this->id])
       ->one();
       //Si tengo al menos uno, tomo el mismo como referencia
      // y luego tengo obtener todos los adjuntos
      if( $eq !==NULL){
        $adEq= AdjuntoEquipo::find()->where(["id_equipo"=>$eq->id])->all();
        try {
          $transaction = Yii::$app->db->beginTransaction();
          foreach ($adEq as $adjuntoequipo ) {
            $ae= new AdjuntoEquipo();
            $ae->id_equipo= $this->id;
            $ae->id_adjunto= $adjuntoequipo->id_adjunto;
            if (!$ae->save()) {
                // recogemos errores amigables y lanzamos excepción para activar rollback
                throw new \RuntimeException('Error guardando AdjuntoEquipo: ' . implode('; ', $ae->getFirstErrors()));
            }
          }
          $transaction->commit();
        } catch (\Exception $e) {
           $transaction->rollBack();
           throw new \RuntimeException(
               'No se pudo sincronizar los adjuntos',
               0,
               $e
           );

         }
      }
   }


   public function afterSave($insert, $changedAttributes)
      {
          parent::afterSave($insert, $changedAttributes);

          // Solo nos interesa cuando se actualiza (no en insert)
          if (!$insert && (array_key_exists('id_marca', $changedAttributes) || array_key_exists('id_modelo', $changedAttributes))) {
              $oldMarca  = $changedAttributes['id_marca']  ?? $this->id_marca;
              $oldModelo = $changedAttributes['id_modelo'] ?? $this->id_modelo;

              // Ejecutar la sincronización / limpieza
              try {
                  $this->quitarAdjuntosDelGrupoAntiguo($oldMarca, $oldModelo);
                  $this->agregarAdjuntosDelGrupoNuevo();
              } catch (\Throwable $e) {
                  // Loguear y re-lanzar para que el controller decida la respuesta
                  Yii::error("Error al quitar adjuntos del grupo antiguo para equipo {$this->id}: " . $e->getMessage(), __METHOD__);
                  throw $e;
              }
          }
      }

      /**
       * Quita las relaciones adjunto_equipo de este equipo para aquellos adjuntos
       * que están presentes también en otros equipos del (oldMarca, oldModelo).
       *
       * @param int|null $oldMarca
       * @param int|null $oldModelo
       * @return bool
       * @throws \RuntimeException
       */
      protected function quitarAdjuntosDelGrupoAntiguo($oldMarca, $oldModelo): bool
      {
          // Buscar si existen otros equipos en el grupo antiguo (excluyendo este)
          $existenOtros = Equipo::find()
              ->where(['id_marca' => $oldMarca, 'id_modelo' => $oldModelo])
              ->andWhere(['<>', 'id', $this->id])
              ->exists();

          if (!$existenOtros) {
              // No hay otros, no borramos nada
              return true;
          }

          // Obtener ids de adjuntos que pertenecen a los otros equipos (grupo antiguo)
          $adjuntosEnOtros = (new Query())
              ->select('ae.id_adjunto')
              ->from('adjunto_equipo ae')
              ->innerJoin('equipo e', 'e.id = ae.id_equipo')
              ->where(['e.id_marca' => $oldMarca, 'e.id_modelo' => $oldModelo])
              ->distinct()
              ->column();

          if (empty($adjuntosEnOtros)) {
              // No hay adjuntos en los otros -> nada que borrar
              return true;
          }

          $transaction = Yii::$app->db->beginTransaction();
          try {
              // Borramos solo las filas de este equipo cuyo id_adjunto esté en la lista
              Yii::$app->db->createCommand()
                  ->delete('adjunto_equipo', [
                      'and',
                      ['id_equipo' => $this->id],
                      ['id_adjunto' => $adjuntosEnOtros],
                  ])->execute();

              $transaction->commit();
              return true;
          } catch (\Throwable $e) {
              $transaction->rollBack();
              throw new \RuntimeException('No se pudo quitar adjuntos del grupo antiguo: ' . $e->getMessage(), 0, $e);
          }
      }

 /**
  *Se agrega los adjuntos del nuevo grupo en caso de existir
  *al equipo que cambio de modelo y/o marca.
 */
      protected function agregarAdjuntosDelGrupoNuevo(){
          $exist= Equipo::find()->where(["id_marca"=> $this->id_marca , "id_modelo"=>$this->id_modelo])
          ->andWhere(["<>", "id", $this->id])
          ->exists();
          if(!$exist){
            //no se cambia nada
            return true;
          }

          $adjuntosEnOtros = (new Query())
          ->select('ae.id_adjunto')
          ->from('adjunto_equipo ae')
          ->innerJoin('equipo e' , 'e.id=ae.id_equipo')
          ->where(['e.id_marca'=> $this->id_marca , 'e.id_modelo'=>$this->id_modelo])
          ->andWhere(['<>', 'e.id', $this->id])
          ->distinct()
          ->column();

          try {
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($adjuntosEnOtros as $idA ) {
                $ae = new AdjuntoEquipo(['id_equipo'=>$this->id  , 'id_adjunto'=>$idA]);
                if (!$ae->save()) {
                    throw new \RuntimeException(
                        'Error guardando AdjuntoEquipo: ' . implode('; ', $ae->getFirstErrors())
                    );
                }
              }

            $transaction->commit();
            return true;
          } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception(
                "Error al sincronizar adjuntos",
                0,
                $e
            );
          }


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
