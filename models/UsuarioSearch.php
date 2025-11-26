<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Usuario;

/**
 * UsuarioSearch represents the model behind the search form about `app\models\Usuario`.
 */
class UsuarioSearch extends Usuario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombreusuario', 'contrasenia', 'nombreapellido', 'descripcion', 'imagen', 'access_token', 'token_expire_at', 'reset_token', 'reset_token_expire'], 'safe'],
            [['activo', 'must_change_password'], 'boolean'],
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
        $query = Usuario::find();

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
            'activo' => $this->activo,
            'token_expire_at' => $this->token_expire_at,
            'must_change_password' => $this->must_change_password,
            'reset_token_expire' => $this->reset_token_expire,
        ]);

        $query->andFilterWhere(['like', 'nombreusuario', $this->nombreusuario])
            ->andFilterWhere(['like', 'contrasenia', $this->contrasenia])
            ->andFilterWhere(['like', 'nombreapellido', $this->nombreapellido])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'reset_token', $this->reset_token]);

        return $dataProvider;
    }
}
