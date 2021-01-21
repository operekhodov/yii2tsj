<?php
namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Indicat;
use app\models\User;

class IndicatSearch extends Indicat
{
    public $date_from;
    public $date_to;	
    public $arr_date;

    public function rules()
    {
        return [
            [['id_u'], 'integer'],
            [['created_at','date_from', 'date_to'], 'string'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Indicat::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
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
		if ($this->date_from && $this->date_to) {
			$m_from	= intval(substr($this->date_from,0,2));
			$y_from	= intval(substr($this->date_from,-4));
			$m_to	= intval(substr($this->date_to,0,2));
			$y_to	= intval(substr($this->date_to,-4));
			$arr_date = '(';
			for ($i=$y_from; $i < $y_to+1; $i++) {
				$m_end = ($i == $y_to) ? $m_to+1 : 13;
				for ($j=$m_from; $j < $m_end; $j++) {
					$m = ($j < 10) ? "0$j" : $j;
					$arr_date .="$m.$i|";
					$m_from = 1;
					
				}
			}
			$arr_date = substr($arr_date,0,-1).')$';
		}
		if(Yii::$app->getUser()->identity->role == 'root'){
	        $query->andFilterWhere(['REGEXP', 'created_at', $arr_date])
	        	  ->andFilterWhere(['=', 'id_u', $this->id_u])
	        ;
		}elseif(Yii::$app->getUser()->identity->role == 'user'){
	        $query->andFilterWhere(['=', 'id_u', Yii::$app->getUser()->identity->id])
	        	  ->andFilterWhere(['REGEXP', 'created_at', $arr_date ? $arr_date : null])
	        ;			
		}elseif(Yii::$app->getUser()->identity->role == 'government'){
	        $query->andFilterWhere(['in', 'id_u', User::getAllUsersThisMkd(Yii::$app->getUser()->identity->id_a)])
	        	  ->andFilterWhere(['=', 'id_u', $this->id_u])
	        	  ->andFilterWhere(['REGEXP', 'created_at', $arr_date ? $arr_date : null])
	        ;
		}else{
	        $query->andFilterWhere(['in', 'id_u', User::getAllUsersThisArea(Yii::$app->getUser()->identity->id_org)])
	        	  ->andFilterWhere(['=', 'id_u', $this->id_u])
	        	  ->andFilterWhere(['REGEXP', 'created_at', $arr_date ? $arr_date : null])
	        ;				
		}
        return $dataProvider;
    }
 /*   public function searchmyindicat($params)
    {
        $query = Indicat::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 12
            ]             
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
		if ($this->date_from || $this->date_to) {
			$y_from	= intval(substr($this->date_from,0,4));
			$m_from	= intval(substr($this->date_from,-2));
			$y_to	= intval(substr($this->date_to,0,4));
			$m_to	= intval(substr($this->date_to,-2));
			$arr_date = '^(';
			for ($i=$y_from; $i < $y_to+1; $i++) {
				$m_end = ($i == $y_to) ? $m_to+1 : 13;
				for ($j=$m_from; $j < $m_end; $j++) {
					$m = ($j < 10) ? "0$j" : $j;
					$arr_date .="$i,$m|";
					$m_from = 1;
					
				}
			}
			$arr_date = substr($arr_date,0,-1).')';
		}        
	        $query->andFilterWhere(['=', 'id_u', Yii::$app->getUser()->identity->id])
	        	  ->andFilterWhere(['REGEXP', 'created_at', $arr_date ? $arr_date : null])
	        ;
        return $dataProvider;
    }    */
}
