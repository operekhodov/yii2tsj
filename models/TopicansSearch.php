<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Topicans;

class TopicansSearch extends Topicans
{
    public function rules()
    {
		if(Yii::$app->getUser()->identity->role == 'user'){
	        return [
	            [['id','id_q'], 'integer'],
	            [['note'], 'safe'],
	        ];
		}else{
	        return [
	            [['id', 'id_u', 'id_q'], 'integer'],
	            [['answer', 'note'], 'safe'],
	        ];
		}
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Topicans::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => $this->id_u,
            'id_q' => $this->id_q,
        ]);
        $query->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'note', $this->note]);
        return $dataProvider;
    }
    public function donesearch($params,$id)
    {
        $query = Topicans::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if(Yii::$app->getUser()->identity->role == 'user'){
	        $query->andFilterWhere([
	            'id' => $this->id,
	            'id_q' => $id,
	        ]);        	
	        $query->andFilterWhere(['=', 'id_u', Yii::$app->getUser()->identity->id]);
        }else{
	        $query->andFilterWhere([
	            'id' => $this->id,
	            'id_u' => $this->id_u,
	            'id_q' => $id,
	        ]);        	
	        $query->andFilterWhere(['like', 'answer', $this->answer])
	              ->andFilterWhere(['like', 'note', $this->note]);        	
        }
        
        return $dataProvider;
    }    
}
