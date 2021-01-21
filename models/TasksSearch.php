<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

class TasksSearch extends Tasks
{

    public $date_from;
    public $date_to;
    public $date_from1;
    public $date_to1;    

    public function rules()
    {
        return [
            [['id','id_a', 'status', 'assignedto', 'createdby','id_a'], 'integer'],
            [['createddate', 'finishdate', 'info', 'notes','num','tema'], 'safe'],
            [['date_from', 'date_to','date_from1', 'date_to1'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'date_from' => Yii::t('app', 'USER_DATE_FROM'),
            'date_to' => Yii::t('app', 'USER_DATE_TO'),
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Tasks::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],                
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15
            ]            
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
            'createddate' => $this->createddate,
            'finishdate' => $this->finishdate,
            'status' => $this->status,
            'assignedto' => $this->assignedto,
            'createdby' => $this->createdby,
        ]);

        if (Yii::$app->getUser()->identity->role == 'root') { 
	        $query->andFilterWhere(['like', 'info', $this->info])
	            ->andFilterWhere(['like', 'notes', $this->notes])
	            ->andFilterWhere(['like', 'num', $this->num])
	            ->andFilterWhere(['>=', 'createddate', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'createddate', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
	            ->andFilterWhere(['>=', 'finishdate', $this->date_from1 ? strtotime($this->date_from1 . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'finishdate', $this->date_to1 ? strtotime($this->date_to1 . ' 23:59:59') : null]);
        }elseif(Yii::$app->getUser()->identity->role == 'user'){
	        $query->andFilterWhere(['like', 'info', $this->info])
	            ->andFilterWhere(['like', 'notes', $this->notes])
	            ->andFilterWhere(['like', 'num', $this->num])
	            ->andFilterWhere(['>=', 'createddate', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'createddate', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
	            ->andFilterWhere(['>=', 'finishdate', $this->date_from1 ? strtotime($this->date_from1 . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'finishdate', $this->date_to1 ? strtotime($this->date_to1 . ' 23:59:59') : null])
	            ->andFilterWhere(['=', 'id_a', Yii::$app->getUser()->identity->id_a]);
        }else{
	        $query->andFilterWhere(['like', 'info', $this->info])
	            ->andFilterWhere(['like', 'notes', $this->notes])
	            ->andFilterWhere(['like', 'num', $this->num])
	            ->andFilterWhere(['like', 'tema', $this->tema])
	            ->andFilterWhere(['>=', 'createddate', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'createddate', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
	            ->andFilterWhere(['>=', 'finishdate', $this->date_from1 ? strtotime($this->date_from1 . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'finishdate', $this->date_to1 ? strtotime($this->date_to1 . ' 23:59:59') : null])
	            ->andFilterWhere(['=', 'id_a', $this->id_a])
	            ->andFilterWhere(['in', 'id_a', Mkd::getAllMkdIDThisArea(\Yii::$app->user->identity->id_org)]);
        }
        return $dataProvider;
    }
    public function searchmytask($params)
    {
        $query = Tasks::find();

        // add conditions that should always apply here

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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'createddate' => $this->createddate,
            'finishdate' => $this->finishdate,
            'status' => $this->status,
            'assignedto' => $this->assignedto,
            'createdby' => $this->createdby,
        ]);
        
        if (Yii::$app->getUser()->identity->role == 'root') { 
	        $query->andFilterWhere(['like', 'info', $this->info])
	            ->andFilterWhere(['like', 'notes', $this->notes])
	            ->andFilterWhere(['like', 'num', $this->num])
	            ->andFilterWhere(['>=', 'createddate', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'createddate', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
	            ->andFilterWhere(['>=', 'finishdate', $this->date_from1 ? strtotime($this->date_from1 . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'finishdate', $this->date_to1 ? strtotime($this->date_to1 . ' 23:59:59') : null]);
        }else{
	        $query->andFilterWhere(['like', 'info', $this->info])
	            ->andFilterWhere(['like', 'notes', $this->notes])
	            ->andFilterWhere(['like', 'num', $this->num])
	            ->andFilterWhere(['>=', 'createddate', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'createddate', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null])
	            ->andFilterWhere(['>=', 'finishdate', $this->date_from1 ? strtotime($this->date_from1 . ' 00:00:00') : null])
	            ->andFilterWhere(['<=', 'finishdate', $this->date_to1 ? strtotime($this->date_to1 . ' 23:59:59') : null])
	            ->andFilterWhere(['=', 'id_a', Yii::$app->getUser()->identity->id_a]);
        }

        return $dataProvider;
    }    
}
