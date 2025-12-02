<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;
use yii\helpers\Html;

/**
 * This is the model class for table "servicio".
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $ninterno
 * @property string|null $correo
 * @property string|null $responsable
 * @property string|null $observacion
 *
 * @property Equipo[] $equipos
 */
class Servicio extends \yii\db\ActiveRecord
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
        return 'servicio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ninterno', 'correo', 'responsable', 'observacion'], 'default', 'value' => null],
            [['nombre'], 'required'],
            [['nombre', 'correo', 'responsable', 'observacion'], 'string'],
            [['nombre'], 'validarUnicoCaseInsensitive'],
            [['ninterno'], 'default', 'value' => null],
            [['ninterno'], 'integer'],
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
            $this->addError($attribute, 'Ya existe un registro con ese nombre.');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'ninterno' => 'Nº de interno',
            'correo' => 'Correo',
            'responsable' => 'Responsable',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * Gets query for [[Equipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipos()
    {
        return $this->hasMany(Equipo::class, ['id_servicio' => 'id']);
    }

    public function getnombreurl(){
      return Html::a( $this->nombre ,['servicio/view',"id"=> $this->id]
        ,[    'class' => 'text-success','role'=>'modal-remote','title'=>'Datos del paciente','data-toggle'=>'tooltip']
       );
    }
    public function beforeSave($insert){
    //DE FORMA INDIVIDUAL
      $this->nombre = strtoupper($this->nombre);
      return parent::beforeSave($insert);
    }


}
