<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parametrizacion;

/**
 * ParametrizacionSearch represents the model behind the search form about `app\models\Parametrizacion`.
 */
class ParametrizacionSearch extends Parametrizacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tiempo'], 'integer'],
            [['condicion', 'modelo', 'descripcion', 'color', 'tipotiempo'], 'safe'],
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
        $query = Parametrizacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tiempo' => $this->tiempo,
        ]);

        $query->andFilterWhere(['like', 'condicion', $this->condicion])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'tipotiempo', $this->tipotiempo]);

        return $dataProvider;
    }
}
