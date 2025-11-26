<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\Html;

/**
 * This is the model class for table "modelo".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int|null $anio
 * @property int $id_marca
 * @property Marca $marca
 * @property Equipo[] $equipos
 */
class Modelo extends \yii\db\ActiveRecord
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
        return 'modelo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'anio'], 'default', 'value' => null],
            [['nombre', 'id_marca'], 'required'],
            [['nombre', 'descripcion'], 'string'],
            [['anio', 'id_marca'], 'default', 'value' => null],
            [['anio', 'id_marca'], 'integer'],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['id_marca' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'anio' => 'Año',
            'id_marca' => 'Marca',
        ];
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
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id_modelo' => 'id']);
    }
    public function getnombreurl(){
      return Html::a( $this->nombre ,['modelo/view',"id"=> $this->id]
        ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
       );
    }
}
