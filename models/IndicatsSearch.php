<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Indicats;

class IndicatsSearch extends Indicats
{
    public $date_from;
    public $date_to;
    
    public function rules()
    {
        return [
            [['id', 'id_u', 'created_at','date_from', 'date_to'], 'integer'],
            [['indinow'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Indicats::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        $query->andFilterWhere([
            'id' => $this->id,
            'id_u' => Yii::$app->getUser()->identity->id,//$this->id_u,
        ])
        ->andFilterWhere(['REGEXP', 'created_at', $arr_date ? $arr_date : null])
        ;
        $query->andFilterWhere(['like', 'indinow', $this->indinow]);
        return $dataProvider;
    }
}
