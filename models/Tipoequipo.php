<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\Html;

/**
 * This is the model class for table "tipoequipo".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 *
 * @property Equipo[] $equipos
 */
class Tipoequipo extends \yii\db\ActiveRecord
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
        return 'tipoequipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'default', 'value' => null],
            [['nombre'], 'required'],
            [['nombre', 'descripcion'], 'string'],
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
        ];
    }

    /**
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id_tipoequipo' => 'id']);
    }
    public function getnombreurl(){
      return Html::a( $this->nombre ,['tipoequipo/view',"id"=> $this->id]
        ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
       );
    }
}
