<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;
use app\models\User;

class NewsSearch extends News
{
    public function rules()
    {
        return [
            [['id','id_u'], 'integer'],
            [['title', 'body', 'imagesmas', 'note','type','datetime','datecreated'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = News::find();
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 20
            ]            
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => $this->id_u,
        ]);
		if(\Yii::$app->user->can('moder')){
        	$query->andFilterWhere(['like', 'title', $this->title])
            	  ->andFilterWhere(['like', 'body', $this->body])
            	  ->andFilterWhere(['like', 'datecreated', $this->datecreated])
            	  ->andFilterWhere(['=', 'type', $this->type]);
		}elseif(\Yii::$app->user->can('spec')){
        	$query->andFilterWhere(['like', 'title', $this->title])
            	  ->andFilterWhere(['like', 'body', $this->body])
            	  ->andFilterWhere(['like', 'datecreated', $this->datecreated])
            	  ->andFilterWhere(['in', 'id_u', User::getArrUsrIdThisOrg(Yii::$app->getUser()->identity->id_org)])
            	  ->andFilterWhere(['=', 'type', $this->type]);
		}elseif(\Yii::$app->user->can('halfuser')){
        	$query->andFilterWhere(['like', 'title', $this->title])
            	  ->andFilterWhere(['like', 'body', $this->body])
            	  ->andFilterWhere(['like', 'datecreated', $this->datecreated])
            	  ->andFilterWhere(['like', 'mkd_ids', '"'.Yii::$app->getUser()->identity->id_mkd.'"'])
            	  ->andFilterWhere(['=', 'type', $this->type]);
		}
        return $dataProvider;
    }
}
