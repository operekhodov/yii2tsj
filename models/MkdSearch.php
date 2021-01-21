<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mkd;

class MkdSearch extends Mkd
{
    public function rules()
    {
        return [
            [['id', 'id_a'], 'integer'],
            [['city', 'street', 'number_house'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Mkd::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],                
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 5
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'id_a' => $this->id_a,
        ]);
		
		if(Yii::$app->getUser()->identity->role == 'root') {
	        $query	->andFilterWhere(['like', 'city', $this->city])
		            ->andFilterWhere(['like', 'street', $this->street])
		            ->andFilterWhere(['like', 'number_house', $this->number_house]);
		}else{
	        $query	->andFilterWhere(['like', 'city', $this->city])
		            ->andFilterWhere(['like', 'street', $this->street])
		            ->andFilterWhere(['like', 'number_house', $this->number_house])
		            ->andFilterWhere(['=', 'id_a', Yii::$app->getUser()->identity->id_org]);
		}
        return $dataProvider;
    }
}
