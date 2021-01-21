<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mkd;

class UserSearch extends Model
{
    public $id;
    public $username;
    public $status;
    public $role;
    public $date_from;
    public $date_to;
    public $id_a;
    public $fio;
    public $type;
    public $lname;
    public $locality;
    public $street;
    public $num_house;
    public $nroom;
 
    public function rules()
    {
        return [
            [['id', 'status','id_a','type'], 'integer'],
            [['username'], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['role','fio','locality','lname','street','num_house','nroom'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Yii::t('app', 'USER_CREATED'),
            'updated_at' => Yii::t('app', 'USER_UPDATED'),
            'username' => Yii::t('app', 'USER_USERNAME'),
            'status' => Yii::t('app', 'USER_STATUS'),
            'date_from' => Yii::t('app', 'USER_DATE_FROM'),
            'date_to' => Yii::t('app', 'USER_DATE_TO'),
            'id_a' => Yii::t('app', 'ID_A'),
        ];
    }
    public function search($params)
    {
        $query = User::find();
 
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

			
        if (Yii::$app->getUser()->identity->role == 'root') {
            $query->andFilterWhere([
                'id' => $this->id,
                'status' => $this->status,
                'role' => $this->role,
            ]);
        }else{
            $query->andFilterWhere([
                'id' => $this->id,
                'status' => $this->status,
                'id_a' => User::getIDA(Yii::$app->getUser()->identity->id),
                'role' => [ 'supervisors'=>'supervisors',
                            'user'=>'user',
                            'dispatcher' => 'dispatcher',
                            'government' => 'government',
                            'admin' => 'admin'],
            ]);
        }
 
        $query
        	->andFilterWhere(['in','role',['root','admin','moder','boss','dispatcher','spec','agent']])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'id_a', $this->id_a])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);
 
        return $dataProvider;
    }
    public function searchthismkd($params,$id_mkd,$type)
    {
        $query = User::find();
 
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
 
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'role' => $this->role,
        ]);

		if($type == 'personal') {
	        $query
	            ->andFilterWhere(['like', 'lname', $this->lname])
	            ->andFilterWhere(['=', 'id_org', Mkd::findById($id_mkd)->id_a])
	            ->andFilterWhere(['like', 'nroom', $this->nroom])
        		->andFilterWhere(['or',['role'=> 'dispatcher'],['role'=> 'spec'],['role'=>'agent'],['role'=> 'boss']]);	            
		}elseif($type == 'users'){
	        $query
	            ->andFilterWhere(['like', 'lname', $this->lname])
	            ->andFilterWhere(['=', 'id_a', $id_mkd])
	            ->andFilterWhere(['like', 'nroom', $this->nroom])
	            ->andFilterWhere(['or',['role'=> 'halfuser'],['role'=> 'user']]);
		}elseif($type == 'government'){
	        $query
	            ->andFilterWhere(['like', 'lname', $this->lname])
	            ->andFilterWhere(['=', 'id_a', $id_mkd])
	            ->andFilterWhere(['like', 'nroom', $this->nroom])
	            ->andFilterWhere(['=', 'role', $type]);
		}
 
        return $dataProvider;
    }
    
}