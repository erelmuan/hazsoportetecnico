<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Modelo;

/**
 * ModeloSearch represents the model behind the search form about `app\models\Modelo`.
 */
class ModeloSearch extends Modelo
{
  public $marca;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'anio', 'id_marca'], 'integer'],
            [['nombre', 'descripcion' ,'marca'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Modelo::find()->innerJoinWith('marca', true);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $dataProvider->sort->attributes['marca'] = [
               'asc' => ['marca.nombre' => SORT_ASC],
               'desc' => ['marca.nombre' => SORT_DESC],
           ];
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'anio' => $this->anio,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'descripcion', $this->descripcion])
            ->andFilterWhere(['ilike', 'marca.nombre', $this->marca]);

        return $dataProvider;
    }
}
