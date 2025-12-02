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
            [['nombre'], 'validarUnicoCaseInsensitive'],
            [['anio', 'id_marca'], 'default', 'value' => null],
            [['anio', 'id_marca'], 'integer'],
            [['id_marca'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['id_marca' => 'id']],
        ];
    }
    public function validarUnicoCaseInsensitive($attribute)
{
    $valor = mb_strtolower(trim((string)$this->$attribute), 'UTF-8');

    $query = self::find()
        ->where(new \yii\db\Expression('LOWER(nombre) = :n'), [':n' => $valor]);

    // Cuando es un update, excluir el propio ID
    if (!$this->isNewRecord) {
        $query->andWhere(['<>', 'id', $this->id]);
    }

    if ($query->exists()) {
        $this->addError($attribute, 'Ya existe un registro con ese nombre (ignorando mayúsculas/minúsculas).');
    }
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'id' => Yii::t('app', 'ID'),
          'nombre' => Yii::t('app', 'Nombre'),
          'descripcion' => Yii::t('app', 'Descripcion'),
          'anio' => Yii::t('app', 'Anio'),
          'id_marca' => Yii::t('app', 'Id Marca'),
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

    public function beforeSave($insert){
    //DE FORMA INDIVIDUAL
      $this->nombre = strtoupper($this->nombre);
      return parent::beforeSave($insert);
    }

}
