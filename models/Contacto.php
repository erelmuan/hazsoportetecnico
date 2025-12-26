<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;

/**
 * This is the model class for table "contacto".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono
 * @property string|null $email
 * @property int $id_proveedor
 * @property string|null $cargo
 *
 * @property Proveedor $proveedor
 */
class Contacto extends \yii\db\ActiveRecord
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
        return 'contacto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono', 'email', 'cargo'], 'default', 'value' => null],
            // [['id_proveedor'], 'required'], NO VALIDAR PARA PODER CREAR EL PROVEEDOR Y CONTACTOS A LA VEZ.
            [['nombre'], 'required'],
            [['nombre', 'telefono', 'email', 'cargo'], 'string'],
            [['id_proveedor'], 'default', 'value' => null],
            [['id_proveedor'], 'integer'],
            [['id_proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::class, 'targetAttribute' => ['id_proveedor' => 'id']],
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
            'telefono' => Yii::t('app', 'Telefono'),
            'email' => Yii::t('app', 'Email'),
            'id_proveedor' => Yii::t('app', 'Id Proveedor'),
            'cargo' => Yii::t('app', 'Cargo'),
        ];
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
    public function beforeSave($insert){
    //DE FORMA INDIVIDUAL
      $this->nombre = strtoupper($this->nombre);
      return parent::beforeSave($insert);
    }


}
