<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Adjunto;

/**
 * AdjuntoSearch represents the model behind the search form about `app\models\Adjunto`.
 */
class AdjuntoSearch extends Adjunto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_equipo'], 'integer'],
            [['nombreoriginal', 'nombreasignado', 'observacion', 'tipocategoria', 'tipoarchivo', 'fechahora'], 'safe'],
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
     public function search($params,$tipocategoria, $id_equipo)
     {
       if($tipocategoria ===Adjunto::TIPOCATEGORIA_OPERATIVO){
         $query = Adjunto::find()
             ->innerJoinWith('equipo', true)
             ->andWhere(['adjunto.id_equipo' => $id_equipo])
             ->orderBy(['adjunto.id' => SORT_DESC]);
       }if($tipocategoria ===Adjunto::TIPOCATEGORIA_REFERENCIA){
         $query = Adjunto::find()
             ->innerJoinWith('adjuntoEquipos', 'adjuntoEquipos.id_adjunto= adjunto.id  ')
             ->andWhere(['adjunto_equipo.id_equipo' => $id_equipo])
             ->orderBy(['adjunto.id' => SORT_DESC]);
       }


         $dataProvider = new ActiveDataProvider([
             'query' => $query,
             'pagination' => ['pageSize' => 50],
             'sort' => [
                 'defaultOrder' => ['fechahora' => SORT_DESC],
             ],
         ]);

         $this->load($params);

         if (!$this->validate()) {
             return $dataProvider;
         }

         // filtros exactos para campos enum / id
         $query->andFilterWhere([
             'id' => $this->id,
             'id_equipo' => $this->id_equipo,
             'fechahora' => $this->fechahora,
         ]);

         // filtros textuales
         $query->andFilterWhere(['ilike', 'nombreoriginal', $this->nombreoriginal])
               ->andFilterWhere(['ilike', 'nombreasignado', $this->nombreasignado])
               ->andFilterWhere(['ilike', 'adjunto.observacion', $this->observacion]);

         // PARA ENUMS: usar comparación exacta (NO ILIKE)
         if ($this->tipocategoria !== null && $this->tipocategoria !== '') {
             $query->andWhere(['tipocategoria' => $this->tipocategoria]);
         }
         if ($this->tipoarchivo !== null && $this->tipoarchivo !== '') {
             $query->andWhere(['tipoarchivo' => $this->tipoarchivo]);
         }

         return $dataProvider;
     }


}
