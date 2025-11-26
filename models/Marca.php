<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "marca".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $pais
 * @property string|null $sitioweb
 *
 * @property Equipo[] $equipos
 */
class Marca extends \yii\db\ActiveRecord
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
        return 'marca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pais', 'sitioweb'], 'default', 'value' => null],
            [['nombre'], 'required'],
            [['nombre', 'pais', 'sitioweb'], 'string'],
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
            'pais' => 'Pais',
            'sitioweb' => 'Sitio web',
        ];
    }

    /**
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id_marca' => 'id']);
    }
    public function getnombreurl(){
      return Html::a( $this->nombre ,['marca/view',"id"=> $this->id]
        ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
       );
    }



    public static function arrayMarcas(): array {
      $marcas = self::find()
            ->select(['id', 'nombre'])
            ->orderBy(['nombre' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($marcas, 'id', 'nombre');
    }

}
