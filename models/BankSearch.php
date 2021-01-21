<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bank;

/**
 * BankSearch represents the model behind the search form of `app\models\Bank`.
 */
class BankSearch extends Bank
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['client_id', 'secret_id', 'code', 'response_type', 'authorization_code', 'refresh_token', 'access_token'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Bank::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'client_id', $this->client_id])
            ->andFilterWhere(['like', 'secret_id', $this->secret_id])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'response_type', $this->response_type])
            ->andFilterWhere(['like', 'authorization_code', $this->authorization_code])
            ->andFilterWhere(['like', 'refresh_token', $this->refresh_token])
            ->andFilterWhere(['like', 'access_token', $this->access_token]);

        return $dataProvider;
    }
}
