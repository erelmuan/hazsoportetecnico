<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Componente;

/**
 * ComponenteSearch represents the model behind the search form about `app\models\Componente`.
 */
class ComponenteSearch extends Componente
{

  public $tipocomponente;
  public  $equipo;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_tipocomponente', 'id_equipo'], 'integer'],
            [['nombre', 'serie', 'observacion', 'fechabaja' ,'tipocomponente','equipo'], 'safe'],
            [['baja'], 'boolean'],
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
        $query = Componente::find()->innerJoinWith('tipocomponente',true)
        ->leftJoin('equipo','componente.id_equipo = equipo.id' )
        ->leftJoin('tipoequipo', 'tipoequipo.id = equipo.id_tipoequipo');

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
            'baja' => $this->baja,
            'fechabaja' => $this->fechabaja,
        ]);

        $query->andFilterWhere(['ilike', 'componente.nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'serie', $this->serie])
            ->andFilterWhere(['ilike', 'tipocomponente.nombre', $this->tipocomponente])
           ->andFilterWhere(['ilike', 'tipoequipo.nombre', $this->equipo])
            ->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
