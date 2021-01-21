<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Topic;

class TopicSearch extends Topic
{

    public function rules()
    {
        return [
            [['id', 'id_a'], 'integer'],
            [['topic', 'quest', 'type', 'answermas', 'imagesmas', 'note'], 'safe'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Topic::find();
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
            'id_a' => $this->id_a,
        ]);
		
		if(Yii::$app->getUser()->identity->role == 'root'){
	        $query->andFilterWhere(['like', 'topic', $this->topic])
	            ->andFilterWhere(['like', 'quest', $this->quest])
	            ->andFilterWhere(['like', 'type', $this->type])
	            ->andFilterWhere(['like', 'answermas', $this->answermas])
	            ->andFilterWhere(['like', 'imagesmas', $this->imagesmas])
	            ->andFilterWhere(['like', 'note', $this->note]);
	            
		}elseif(Yii::$app->getUser()->identity->role == 'user' || Yii::$app->getUser()->identity->role == 'government'){
	        $query->andFilterWhere(['like', 'topic', $this->topic])
	            ->andFilterWhere(['like', 'quest', $this->quest])
	            ->andFilterWhere(['like', 'type', $this->type])
	            ->andFilterWhere(['like', 'answermas', $this->answermas])
	            ->andFilterWhere(['like', 'imagesmas', $this->imagesmas])
	            ->andFilterWhere(['like', 'note', $this->note])
	            ->andFilterWhere(['=', 'id_a', Yii::$app->getUser()->identity->id_a]);
		}else{
	        $query->andFilterWhere(['like', 'topic', $this->topic])
	            ->andFilterWhere(['like', 'quest', $this->quest])
	            ->andFilterWhere(['like', 'type', $this->type])
	            ->andFilterWhere(['like', 'answermas', $this->answermas])
	            ->andFilterWhere(['like', 'imagesmas', $this->imagesmas])
	            ->andFilterWhere(['like', 'note', $this->note])
	            ->andFilterWhere(['in', 'id_a', Mkd::getAllMkdIDThisArea(\Yii::$app->user->identity->id_org)]);			
		}

        return $dataProvider;
    }
}
