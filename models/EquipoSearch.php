<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Equipo;

/**
 * EquipoSearch represents the model behind the search form about `app\models\Equipo`.
 */
class EquipoSearch extends Equipo
{
 public $marca;
 public $modelo;
 public $tipoequipo;
 public $servicio;
 public $estado;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_marca', 'id_modelo', 'id_servicio', 'id_tipoequipo' ,'ultimo_log'], 'integer'],
            [['nserie', 'fechafabricacion', 'fecharegistro', 'codigo'], 'safe'],
            ['fechafabricacion', 'date', 'format' => 'dd/MM/yyyy'],
            ['fecharegistro', 'date', 'format' => 'dd/MM/yyyy'],
            [['marca','modelo','tipoequipo','servicio', 'estado' ,'observacion'], 'safe'],
            [['operativo'], 'boolean'],

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
      $query = Equipo::find()
      ->select(['equipo.*'])
      ->innerJoinWith('tipoequipo',true)
      ->leftJoin('marca', 'marca.id = equipo.id_marca')
      ->leftJoin('modelo', 'modelo.id = equipo.id_modelo')
      ->leftJoin('servicio', 'servicio.id = equipo.id_servicio')
      ->leftJoin('estado', 'estado.id = equipo.id_estado')
      ->distinct();                     // <-- elimina filas duplicadas

      // ->orderBy(['equipo.id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['marca'] = [
               'asc' => ['marca.nombre' => SORT_ASC],
               'desc' => ['marca.nombre' => SORT_DESC],
           ];
       $dataProvider->sort->attributes['modelo'] = [
              'asc' => ['modelo.nombre' => SORT_ASC],
              'desc' => ['modelo.nombre' => SORT_DESC],
          ];
      $dataProvider->sort->attributes['servicio'] = [
             'asc' => ['servicio.nombre' => SORT_ASC],
             'desc' => ['servicio.nombre' => SORT_DESC],
         ];
       $dataProvider->sort->attributes['tipoequipo'] = [
              'asc' => ['tipoequipo.nombre' => SORT_ASC],
              'desc' => ['tipoequipo.nombre' => SORT_DESC],
          ];
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
            'equipo.id' => $this->id,
            'fechafabricacion' => $this->fechafabricacion,
            'fecharegistro' => $this->fecharegistro,
             'operativo' => $this->operativo,
        ]);

        $query->andFilterWhere(['ilike', 'nserie', $this->nserie])
            ->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike','marca.nombre',$this->marca])
            ->andFilterWhere(['ilike','modelo.nombre', $this->modelo])
            ->andFilterWhere(['ilike','servicio.nombre',$this->servicio])
            ->andFilterWhere(['ilike','tipoequipo.nombre',$this->tipoequipo])
            ->andFilterWhere(['ilike', 'equipo.observacion', $this->observacion])
            ->andFilterWhere(['ilike','estado.nombre',$this->estado]);

        return $dataProvider;
    }
}
