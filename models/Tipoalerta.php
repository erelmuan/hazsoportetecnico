<?php

namespace app\models;

use Yii;
use app\components\behaviors\AuditoriaBehaviors;

/**
 * This is the model class for table "tipoalerta".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Parametrizacion[] $parametrizacions
 */
class Tipoalerta extends \yii\db\ActiveRecord
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
        return 'tipoalerta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string'],
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
        ];
    }

    /**
     * Gets query for [[Parametrizacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParametrizacions()
    {
        return $this->hasMany(Parametrizacion::class, ['id_tipoalerta' => 'id']);
    }

}
