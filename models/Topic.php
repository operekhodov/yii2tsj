<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use dosamigos\chartjs\ChartJs;
use app\models\User;

class Topic extends \yii\db\ActiveRecord
{
	public $massiv = [];
	public $imageFiles;
    const RADIO		= 0;
    const CHECKBOX 	= 1;
    const ORATING	= 2;
    const ODATE 	= 3;
    const OTIME		= 4;
    public $sttime;
    public $id_mkd;
    public $ttime;
    public $trating;

    public static function tableName()
    {
        return 'topic';
    }
    public function rules()
    {
        return [
            [['id_a', 'topic', 'quest', 'type', 'answermas','sttime','starttime'], 'required'],
            [['id_a','deadtime'], 'integer'],
            [['topic', 'quest', 'type', 'answermas', 'imagesmas', 'note'], 'string', 'max' => 255],
            [['massiv'], 'each', 'rule' => ['string']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 6],
            [['id_mkd','ttime','trating'],'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_a' => Yii::t('app', 'T_MKD'),
            'topic' => Yii::t('app', 'Тема'),
            'quest' => Yii::t('app', 'Вопрос'),
            'type' => Yii::t('app', 'Тип'),
            'answermas' => Yii::t('app', 'Варианты'),
            'imagesmas' => Yii::t('app', 'Imagesmas'),
            'note' => Yii::t('app', 'Заметка/доп.описание'),
            'massiv' => Yii::t('app', 'Вариант ответа'),
            'imageFiles' => Yii::t('app', 'Изображение'),
            'id_mkd' => Yii::t('app', 'T_MKD'),
            'deadtime'=> Yii::t('app', 'Срок проведения опроса'),
            'starttime'=> Yii::t('app', 'Дата начала опроса'),
            'sttime'=> Yii::t('app', 'Дата начала опроса'),
            'avtor'=> Yii::t('app', 'Автор'),
            'ttime'=> Yii::t('app', 'Согласование времени'),
            'trating'=> Yii::t('app', 'Тип рейтинг-опрос'),
        ];
    }
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }
    public static function getTypesArrayForSearch()
    {
        return [
            self::RADIO => Yii::t('app', 'Один из многих ответов'),
            self::CHECKBOX => Yii::t('app', 'Несколько из многих'),
            self::ORATING => Yii::t('app', 'Оценка'),
            self::ODATE => Yii::t('app', 'Дата'),
            self::OTIME => Yii::t('app', 'Время'),
        ];
    }	
    public static function getTypesArray()
    {
        return [
            self::RADIO => "<i class='fa fa-dot-circle-o'></i> Один из многих ответов",
            self::CHECKBOX => "<i class='fa fa-check-square-o'></i> Несколько из многих",
            self::ORATING => "<i class='fa fa-star-o'></i> Оценка",
            self::ODATE => "<i class='fa fa-calendar'></i> Дата",
            self::OTIME =>  "<i class='fa fa-clock-o'></i> Время",
        ];
    }
    public static function getTopicNames()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->all(), 'topic','topic');
    }
    public function getMkd()
    {
        return $this->hasOne(Mkd::className(), ['id' => 'id_a']);
    }
    public function getMkdAddress()
    {
		return $this->mkd->city.', '.$this->mkd->street.', '.$this->mkd->number_house;
    }
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function getAnsforfilter($id)
    {
    	$model = self::find()->where(['id' => $id])->one();
    	return json_decode($model->answermas);
    }
    public function getTopicAnswer($id,$answer) 
    {
    	$model		= self::find()->where(['id' => $id])->one();
    	$answermas	= json_decode($model->answermas);
		if($model->type == 0){
        	return Html::tag('span', Html::encode( $answermas[$answer]  ), ['class' => 'label label-primary']);
		}elseif($model->type == 2){
			return Html::tag('span', Html::encode( $answer  ), ['class' => 'label label-primary']);
		}else{
			$html = '';
			$answer = json_decode($answer);
			foreach($answer as $val) {
				$html .= Html::tag('span', Html::encode( $answermas[$val]  ), ['class' => 'label label-primary']).' ';
			}
			return $html;
		}
    }
	public function getCountDoneTopic($id_u,$id_a)
	{
		$arr = ArrayHelper::map(self::find()->orderBy('id')->where(['id_a'=>$id_a])->all(), 'id','id');
		$count = 0;
		foreach($arr as $id){
			if ( !Topicans::getSDA($id,\Yii::$app->user->identity->id) ) 
				{$count++;}
		}		
		return $count;
	}
	public function getHtml($id,$img1,$img2,$searchModel,$dataProvider)
	{
		$model = self::find()->where(['id' => $id])->one();
		$DetailView_first = 
		DetailView::widget([
		    'model' => $model,
		    'attributes' => [
		    	'topic',
		        'quest',
		        'note',
		        [
		        	'attribute' => 'starttime',
		        	'format' => ['DateTime','php:d.m.Y']
		        ],
		        [
		        	'attribute' => 'deadtime',
		        	'format' => ['DateTime','php:d.m.Y'],
		            'value' => $model->starttime+$model->deadtime      	
		        ],
		        [
		        	'attribute' => 'answermas',
		        	'format'	=> 'raw',
		        	'value'		=> str_replace(['[',']'],'',$model->answermas)
		        ],		        
		    ],
		]);
		$GridView =  GridView::widget([
		        'dataProvider' => $dataProvider,
		        'columns' => [
			        [
			        	'attribute' => 'created_at',
			        	'format' => ['DateTime','php:d.m.Y']
			        ],			        	
			        [ 
			            'attribute'=>'id_u',
			            'value'=>'UserUsername',
		    		],
		            [
		                'attribute' => 'answer',
		                'format' => 'raw',
		                'value' => function ($model, $key, $index, $column) {
		                    $value = $model->{$column->attribute};
		                    $html = Topic::getTopicAnswer($model->id_q,$model->answer) ;
		                    return $html;
		                }
		            ],    		
		            //'note',
		        ],
		    ]); 
		$images = json_decode($model->imagesmas);
		if ($images) {
			$htmlimg = "
			<table>
			  <tr>			
			";
			foreach($images as $img){
				$htmlimg .= "
					<td><img src='$img'></td>
				";
			}
			$htmlimg .= "
			  </tr>
			</table>			
			";			
		}
		$htmljs = "
		<table>
		  <tr>
		    <td><img src='$img1'></td><td><img src='$img2'></td>
		  </tr>
		</table>";
		return $DetailView_first.'<br>'.$htmlimg.'<br>'.$htmljs.'<br>'.$GridView;
	}
}
