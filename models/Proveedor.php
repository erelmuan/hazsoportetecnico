<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;

/**
 * This is the model class for table "proveedor".
 *
 * @property int $id
 * @property string $razonsocial
 * @property string|null $sitioweb
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $observacion
 *
 * @property Contacto[] $contactos
 * @property Log[] $logs
 */
class Proveedor extends \yii\db\ActiveRecord
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
        return 'proveedor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sitioweb', 'telefono', 'email', 'observacion'], 'default', 'value' => null],
            [['razonsocial'], 'required'],
            [['razonsocial', 'sitioweb', 'telefono', 'email', 'observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'razonsocial' => Yii::t('app', 'Razonsocial'),
            'sitioweb' => Yii::t('app', 'Sitioweb'),
            'telefono' => Yii::t('app', 'Telefono'),
            'email' => Yii::t('app', 'Email'),
            'observacion' => Yii::t('app', 'Observacion'),
        ];
    }

    /**
     * Gets query for [[Contactos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContactos()
    {
        return $this->hasMany(Contacto::class, ['id_proveedor' => 'id']);
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::class, ['id_proveedor' => 'id']);
    }
    public function beforeSave($insert){
    //DE FORMA INDIVIDUAL
      $this->razonsocial = strtoupper($this->razonsocial);
      return parent::beforeSave($insert);
    }


}
