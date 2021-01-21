<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Numerators;

class NumeratorsSearch extends Numerators
{
    public function rules()
    {
        return [
            [['id', 'id_u', 'type', 'num'], 'integer'],
            [['note'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Numerators::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => Yii::$app->user->identity->id,
            'type' => $this->type,
            'num' => $this->num,
        ]);
        $query->andFilterWhere(['like', 'note', $this->note]);
        return $dataProvider;
    }
}
