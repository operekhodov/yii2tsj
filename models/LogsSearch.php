<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Logs;

/**
 * LogsSearch represents the model behind the search form of `app\models\Logs`.
 */
class LogsSearch extends Logs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_a', 'id_u'], 'integer'],
            [['created_at', 'ip', 'uagent', 'action', 'info', 'dop', 'note'], 'safe'],
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
        $query = Logs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],            
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
            'id_a' => $this->id_a,
            'id_u' => $this->id_u,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'uagent', $this->uagent])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'dop', $this->dop])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
