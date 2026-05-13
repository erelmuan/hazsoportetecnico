<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "componente".
 *
 * @property int $id
 * @property string $nombre
 * @property int $id_tipocomponente
 * @property string|null $serie
 * @property string|null $observacion
 * @property bool $baja
 * @property int|null $id_equipo
 * @property string|null $fechabaja
 *
 * @property Equipo $equipo
 * @property Tipocomponente $tipocomponente
 */
class Componente extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'componente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serie', 'observacion', 'id_equipo', 'fechabaja'], 'default', 'value' => null],
            [['baja'], 'default', 'value' => 0],
            [['nombre', 'id_tipocomponente'], 'required'],
            [['nombre', 'serie', 'observacion'], 'string'],
            [['id_tipocomponente', 'id_equipo'], 'default', 'value' => null],
            [['id_tipocomponente', 'id_equipo'], 'integer'],
            [['baja'], 'boolean'],
            [['fechabaja'], 'safe'],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['id_equipo' => 'id']],
            [['id_tipocomponente'], 'exist', 'skipOnError' => true, 'targetClass' => Tipocomponente::class, 'targetAttribute' => ['id_tipocomponente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'id_tipocomponente' => Yii::t('app', 'Tipo de componente'),
            'serie' => Yii::t('app', 'Serie'),
            'observacion' => Yii::t('app', 'Observacion'),
            'baja' => Yii::t('app', 'Baja'),
            'id_equipo' => Yii::t('app', 'Equipo'),
            'fechabaja' => Yii::t('app', 'Fecha de baja'),
        ];
    }
    public function attributeColumns()
    {
        return [
          [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'id',
          ],
          [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'nombre',
          ],
          [
            'class'=> '\kartik\grid\DataColumn',
            'attribute'=> 'serie'
          ],
          [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'id_equipo',
            'value'=>function($model){
                if( $model->equipo !==null){
                    return $model->equipo->nserie." ".$model->equipo->tipoequipo->nombre;
                }else {
                    return "No posee equipo asociado";
                }
            },
            'label'=>'Equipo asociado:'
          ]

        ];
    }

    public function getMedicourl(){

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
     * Gets query for [[Tipocomponente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipocomponente()
    {
        return $this->hasOne(Tipocomponente::class, ['id' => 'id_tipocomponente']);
    }

}
