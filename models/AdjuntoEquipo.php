<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "adjunto_equipo".
 *
 * @property int $id
 * @property int $id_equipo
 * @property int $id_adjunto
 *
 * @property Adjunto $adjunto
 * @property Equipo $equipo
 */
class AdjuntoEquipo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'adjunto_equipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_equipo', 'id_adjunto'], 'required'],
            [['id_equipo', 'id_adjunto'], 'default', 'value' => null],
            [['id_equipo', 'id_adjunto'], 'integer'],
            [['id_equipo', 'id_adjunto'], 'unique', 'targetAttribute' => ['id_equipo', 'id_adjunto']],
            [['id_adjunto'], 'exist', 'skipOnError' => true, 'targetClass' => Adjunto::class, 'targetAttribute' => ['id_adjunto' => 'id']],
            [['id_equipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::class, 'targetAttribute' => ['id_equipo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_equipo' => Yii::t('app', 'Id Equipo'),
            'id_adjunto' => Yii::t('app', 'Id Adjunto'),
        ];
    }

    /**
     * Gets query for [[Adjunto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjunto()
    {
        return $this->hasOne(Adjunto::class, ['id' => 'id_adjunto']);
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

}
