<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Options;

/**
 * OptionsSearch represents the model behind the search form of `app\models\Options`.
 */
class OptionsSearch extends Options
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_u', 'id_t'], 'integer'],
            [['name', 'value'], 'safe'],
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
        $query = Options::find();

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
            'id_u' => $this->id_u,
            'id_t' => $this->id_t,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
    public function tagssearch($params)
    {
        $query = Options::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('name=tags');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => $this->id_u,
            'id_t' => 0,
            'name' => 'tags',
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);
            

        return $dataProvider;
    }    
    public function fieldsusearch($params)
    {
        $query = Options::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('name=tags');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => $this->id_u,
            'id_t' => $this->id_t,
            'name' => 'fieldsu',
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);
            

        return $dataProvider;
    }
    public function camerasearch($params)
    {
        $query = Options::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('name=tags');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => $this->id_u,
            'id_t' => $this->id_t,
            'name' => 'camera',
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);
            

        return $dataProvider;
    }      
}
