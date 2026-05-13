<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tipocomponente".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 *
 * @property Componente[] $componentes
 */
class Tipocomponente extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipocomponente';
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
            'id' => Yii::t('app', 'ID'),
            'nombre' => Yii::t('app', 'Nombre'),
            'descripcion' => Yii::t('app', 'Descripcion'),
        ];
    }

    /**
     * Gets query for [[Componentes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComponentes()
    {
        return $this->hasMany(Componente::class, ['id_tipocomponente' => 'id']);
    }

    public static function arrayTipocomponentes(): array {
      $tipocomponentes = self::find()
            ->select(['id', 'nombre'])
            ->orderBy(['nombre' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($tipocomponentes, 'id', 'nombre');
    }

}
