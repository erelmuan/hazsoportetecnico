<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Log;

/**
 * LogSearch represents the model behind the search form about `app\models\Log`.
 */
class LogSearch extends Log
{
  public $estado;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_equipo', 'id_estado'], 'integer'],
            ['fechaingreso', 'date', 'format' => 'dd/MM/yyyy'],
            ['fechaegreso', 'date', 'format' => 'dd/MM/yyyy'],
            [['fechaingreso', 'fechaegreso', 'falla', 'observacion' ,'estado'], 'safe'],
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
    public function search($params, $id_equipo)
    {
        $query = Log::find()->innerJoinWith('estado', true)->andWhere(['id_equipo'=> $id_equipo]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['estado'] = [
               'asc' => ['estado.nombre' => SORT_ASC],
               'desc' => ['estado.nombre' => SORT_DESC],
           ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fechaingreso' => $this->fechaingreso,
            'fechaegreso' => $this->fechaegreso,
            'id_equipo' => $this->id_equipo,
            'id_estado' => $this->id_estado,
        ]);

        $query->andFilterWhere(['ilike', 'falla', $this->falla])
            ->andFilterWhere(['ilike', 'estado.nombre', $this->estado])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
