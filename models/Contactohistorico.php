<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contactohistorico".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $cargo
 * @property int $id_log
 * @property int $id_contacto
 *
 * @property Log $log
 */
class Contactohistorico extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contactohistorico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telefono', 'email', 'cargo'], 'default', 'value' => null],
            [['nombre', 'id_log', 'id_contacto'], 'required'],
            [['nombre', 'telefono', 'email', 'cargo'], 'string'],
            [['id_log', 'id_contacto'], 'default', 'value' => null],
            [['id_log', 'id_contacto'], 'integer'],
            [['id_log'], 'exist', 'skipOnError' => true, 'targetClass' => Log::class, 'targetAttribute' => ['id_log' => 'id']],
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
            'cargo' => Yii::t('app', 'Cargo'),
            'id_log' => Yii::t('app', 'Id Log'),
            'id_contacto' => Yii::t('app', 'Id Contacto'),
        ];
    }

    /**
     * Gets query for [[Log]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLog()
    {
        return $this->hasOne(Log::class, ['id' => 'id_log']);
    }

}
