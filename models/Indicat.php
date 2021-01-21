<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Area;
use app\models\Mkd;
use dosamigos\chartjs\ChartJs;
use yii\helpers\StringHelper;

class Indicat extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'indications';
    }
    public function rules()
    {
        return [
            [['id_u', 'created_at', 'gvs', 'gvs1', 'hvs', 'hvs1', 'elday', 'elnight'], 'required'],
            [['id_u','gvs', 'gvs1', 'hvs', 'hvs1', 'elday', 'elnight'], 'integer', 'min' => 6],
            [['created_at'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_u' => Yii::t('app', 'indicat_CREATEDBY'),
            'created_at' => Yii::t('app', 'indicat_CREATEDDATE'),
            'gvs' => Yii::t('app', 'indicat_GVS'),
            'gvs1' => Yii::t('app', 'indicat_GVS1'),
            'hvs' => Yii::t('app', 'indicat_HVS'),
            'hvs1' => Yii::t('app', 'indicat_HVS1'),
            'elday' => Yii::t('app', 'indicat_ELDAY'),
            'elnight' => Yii::t('app', 'indicat_ELNIGHT'),
        ];
    }
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }    
    public function getCreatedby0()
    {
        return $this->hasOne(User::className(), ['id' => 'id_u']);
    }
    public function getCreatedby0Username() 
    {
        return $this->createdby0->username;
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_u']);
    }
    public function getUserUsername() 
    {
    	$fname = substr( StringHelper::truncate($this->user->fname, 1) ,0,3);
    	$fio = substr( StringHelper::truncate($this->user->fio, 1) ,0,3);

        return $this->user->lname.' '.$fname.' '.$fio.' ['.$this->user->locality.', '.$this->user->street.', '.$this->user->num_house.', '.$this->user->nroom.']['.$this->user->nid.']';    	
    }
    public function getThisAreaLastIndicatCount($id_a)
    {
		return count( self::find()->where(['id_a' => $id_a])->all() );
    }
    public function sendMail($id,$model)
    {

		$user = User::findById($id);
		$mkd =  Mkd::findById($user->id_a);
		$setTo = Area::getMailArea($mkd->id_a);
		$setSubject = "Показания приборов: [$user->locality, $user->street, $user->num_house, $user->nroom] [$user->nid] [$model->created_at]";
		$setHtmlBody = "
			<p>- Лиц.счёт: $user->nid</p>
			<p>- Адрес абонента: $user->locality, $user->street, $user->num_house, $user->nroom</p>
			<p>- ФИО: $user->lname $user->fname $user->fio</p>
			<p>- Дата передачи: $model->created_at</p>

			<table style='height: 157px;' width='323'>
			<tbody>
			<tr>
			<td style='width: 153.5px;'>ГВС: $model->gvs </td>
			<td style='width: 153.5px;'>ГВС-1: $model->gvs1 </td>
			</tr>
			<tr>
			<td style='width: 153.5px;'>ХВС: $model->hvs </td>
			<td style='width: 153.5px;'>ХВС-1: $model->hvs1 </td>
			</tr>
			<tr>
			<td style='width: 153.5px;'>ЭлДень: $model->elday </td>
			<td style='width: 153.5px;'>ЭлНочь: $model->elnight </td>
			</tr>
			</tbody>
			</table>				
		";
		if ($setTo) {
			Yii::$app->mailer->compose()
			    ->setFrom('tsj@wtot.ru')
			    ->setTo($setTo)
			    ->setSubject($setSubject)
			    ->setHtmlBody($setHtmlBody)
			    ->send();
		}
		if ($user->email && json_decode($user->email,true)['use'] == 1) {
			Yii::$app->mailer->compose()
			    ->setFrom('tsj@wtot.ru')
			    ->setTo(json_decode($user->email,true)['email'])
			    ->setSubject($setSubject)
			    ->setHtmlBody($setHtmlBody)
			    ->send();				
		}		
		return true;
    }
    public function getChartHTML($type)
    {
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(12)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
			    
		switch ($type) {
		    case "gvs":
		        $title = 'ГВС-1';
		        break;
		    case "gvs1":
		        $title = 'ГВС-2';
		        break;
		    case "hvs":
		        $title = 'ХВС-1';
		        break;
		    case "hvs1":
		        $title = 'ХВС-2';
		        break;
		    case "elday":
		        $title = 'ЭлДень';
		        break;
		    case "elnight":
		        $title = 'ЭлНочь';
		        break;		        
		}		
		
		$html = ChartJs::widget([
			    'type' => 'line',
			    'options' => ['height' => 400,'width' => 400],
			    'data' => [
			        'labels' => [
			        	$rows[11]['created_at'], 
			        	$rows[10]['created_at'], 
			        	$rows[9]['created_at'], 
			        	$rows[8]['created_at'], 
			        	$rows[7]['created_at'], 
			        	$rows[6]['created_at'], 
			        	$rows[5]['created_at'], 
			        	$rows[4]['created_at'], 
			        	$rows[3]['created_at'], 
			        	$rows[2]['created_at'], 
			        	$rows[1]['created_at'], 
			        	$rows[0]['created_at']
			        	],
			        'datasets' => [
			            [
			                'label' => "$title",
			                'backgroundColor' => "rgba(255,99,132,0.2)",
			                'borderColor' => "rgba(255,99,132,1)",
			                'pointBackgroundColor' => "rgba(255,99,132,1)",
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => "rgba(255,99,132,1)",
			                'data' => [ $rows[11][$type], 
			                			$rows[10][$type], 
			                			$rows[9][$type], 
			                			$rows[8][$type], 
			                			$rows[7][$type], 
			                			$rows[6][$type], 
			                			$rows[5][$type], 
			                			$rows[4][$type], 
			                			$rows[3][$type], 
			                			$rows[2][$type], 
			                			$rows[1][$type], 
			                			$rows[0][$type]
			                		]
			            ]
			        ]
			    ]
			]);
		return $html;
    }
    public function getChatyHTMLD()
    {
    	
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(2)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
		
		
    	
		$html = ChartJs::widget([
			    'type' => 'doughnut',
			    'options' => ['height' => 'auto','width' => 'auto'],
			    'data' => [
			        'radius' =>  "40%",    	
			        'labels' => [
				        'ГВС-1',
				        'ГВС-2',
				        'ХВС-1',
				        'ХВС-2',
				        'ЭлДень',
				        'ЭлНочь'],
			        'datasets' => [
			            [
			                'data' => [
			                	$rows[0]['gvs']-$rows[1]['gvs'],
			                	$rows[0]['gvs1']-$rows[1]['gvs1'],
			                	$rows[0]['hvs']-$rows[1]['hvs'],
			                	$rows[0]['hvs1']-$rows[1]['hvs1'],
			                	$rows[0]['elday']-$rows[1]['elday'],
			                	$rows[0]['elnight']-$rows[1]['elnight']
			                ],
			                'backgroundColor' => [
			                        '#FF0000',
			                        '#FF7373',
			                    	'#0C5DA5',
			                        '#679FD2',
			                        '#FFEB00',
			                    	'#FFF473'                    
			                ],                
			            ],
			        ]
			    ]
			]);    
		return $html;
    }
    public function getChartHTMLALL()
    {
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(12)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
			    
        $title_gvs = 'ГВС-1';
        $title_gvs1 = 'ГВС-2';
        $title_hvs = 'ХВС-1';
        $title_hvs1 = 'ХВС-2';
        $title_elday = 'ЭлДень';
        $title_elnight = 'ЭлНочь';
			
		$html = ChartJs::widget([
			    'type' => 'bar',
			    'options' => ['height' => 'auto','width' => 'auto'],
			    'data' => [
			        'labels' => [
			        	$rows[11]['created_at'], 
			        	$rows[10]['created_at'], 
			        	$rows[9]['created_at'], 
			        	$rows[8]['created_at'], 
			        	$rows[7]['created_at'], 
			        	$rows[6]['created_at'], 
			        	$rows[5]['created_at'], 
			        	$rows[4]['created_at'], 
			        	$rows[3]['created_at'], 
			        	$rows[2]['created_at'], 
			        	$rows[1]['created_at'], 
			        	$rows[0]['created_at']
			        	],
			        'datasets' => [
			            [
			                'label' => "$title_gvs",
			                'backgroundColor' => '#FF0000',
			                'borderColor' => '#FF0000',
			                'pointBackgroundColor' => '#FF0000',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FF0000',
			                'data' => [ 
			                	$rows[11]['gvs'], 
			                	$rows[10]['gvs'], 
			                	$rows[9]['gvs'], 
			                	$rows[8]['gvs'], 
			                	$rows[7]['gvs'], 
			                	$rows[6]['gvs'], 
			                	$rows[5]['gvs'], 
			                	$rows[4]['gvs'], 
			                	$rows[3]['gvs'], 
			                	$rows[2]['gvs'], 
			                	$rows[1]['gvs'], 
			                	$rows[0]['gvs']
			                		]
			            ],
			            [
			                'label' => "$title_gvs1",
			                'backgroundColor' => '#FF7373',
			                'borderColor' => '#FF7373',
			                'pointBackgroundColor' => '#FF7373',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FF7373',
			                'data' => [ 
			                	$rows[11]['gvs1'], 
			                	$rows[10]['gvs1'], 
			                	$rows[9]['gvs1'], 
			                	$rows[8]['gvs1'], 
			                	$rows[7]['gvs1'], 
			                	$rows[6]['gvs1'], 
			                	$rows[5]['gvs1'], 
			                	$rows[4]['gvs1'], 
			                	$rows[3]['gvs1'], 
			                	$rows[2]['gvs1'], 
			                	$rows[1]['gvs1'], 
			                	$rows[0]['gvs1']
			                		]
			            ],
			            [
			                'label' => "$title_hvs",
			                'backgroundColor' => '#0C5DA5',
			                'borderColor' => '#0C5DA5',
			                'pointBackgroundColor' => '#0C5DA5',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#0C5DA5',
			                'data' => [ 
			                	$rows[11]['hvs'], 
			                	$rows[10]['hvs'], 
			                	$rows[9]['hvs'], 
			                	$rows[8]['hvs'], 
			                	$rows[7]['hvs'], 
			                	$rows[6]['hvs'], 
			                	$rows[5]['hvs'], 
			                	$rows[4]['hvs'], 
			                	$rows[3]['hvs'], 
			                	$rows[2]['hvs'], 
			                	$rows[1]['hvs'], 
			                	$rows[0]['hvs']
			                		]
			            ],
			            [
			                'label' => "$title_hvs1",
			                'backgroundColor' => '#679FD2',
			                'borderColor' => '#679FD2',
			                'pointBackgroundColor' => '#679FD2',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#679FD2',
			                'data' => [ 
			                	$rows[11]['hvs1'], 
			                	$rows[10]['hvs1'], 
			                	$rows[9]['hvs1'], 
			                	$rows[8]['hvs1'], 
			                	$rows[7]['hvs1'], 
			                	$rows[6]['hvs1'], 
			                	$rows[5]['hvs1'], 
			                	$rows[4]['hvs1'], 
			                	$rows[3]['hvs1'], 
			                	$rows[2]['hvs1'], 
			                	$rows[1]['hvs1'], 
			                	$rows[0]['hvs1']
			                		]
			            ],			            
			            [
			                'label' => "$title_elday",
			                'backgroundColor' => '#FFEB00',
			                'borderColor' => '#FFEB00',
			                'pointBackgroundColor' => '#FFEB00',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FFEB00',
			                'data' => [ 
			                	$rows[11]['elday'], 
			                	$rows[10]['elday'], 
			                	$rows[9]['elday'], 
			                	$rows[8]['elday'], 
			                	$rows[7]['elday'], 
			                	$rows[6]['elday'], 
			                	$rows[5]['elday'], 
			                	$rows[4]['elday'], 
			                	$rows[3]['elday'], 
			                	$rows[2]['elday'], 
			                	$rows[1]['elday'], 
			                	$rows[0]['elday']
			                		]
			            ],
			            [
			                'label' => "$title_elnight",
			                'backgroundColor' => '#FFF473',
			                'borderColor' => '#FFF473',
			                'pointBackgroundColor' => '#FFF473',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FFF473',
			                'data' => [ 
			                	$rows[11]['elnight'], 
			                	$rows[10]['elnight'], 
			                	$rows[9]['elnight'], 
			                	$rows[8]['elnight'], 
			                	$rows[7]['elnight'], 
			                	$rows[6]['elnight'], 
			                	$rows[5]['elnight'], 
			                	$rows[4]['elnight'], 
			                	$rows[3]['elnight'], 
			                	$rows[2]['elnight'], 
			                	$rows[1]['elnight'], 
			                	$rows[0]['elnight']
			                		]
			            ],			            
			        ]
			    ]
			]);
		return $html;
    }
    public function getChartHTMLALLwater()
    {
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(12)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
			    
        $title_gvs = 'ГВС-1';
        $title_gvs1 = 'ГВС-2';
        $title_hvs = 'ХВС-1';
        $title_hvs1 = 'ХВС-2';
		$html = ChartJs::widget([
			    'type' => 'bar',
			    'options' => ['height' => 'auto','width' => 'auto'],
			    'data' => [
			        'labels' => [
			        	$rows[11]['created_at'], 
			        	$rows[10]['created_at'], 
			        	$rows[9]['created_at'], 
			        	$rows[8]['created_at'], 
			        	$rows[7]['created_at'], 
			        	$rows[6]['created_at'], 
			        	$rows[5]['created_at'], 
			        	$rows[4]['created_at'], 
			        	$rows[3]['created_at'], 
			        	$rows[2]['created_at'], 
			        	$rows[1]['created_at'], 
			        	$rows[0]['created_at']
			        	],
			        'datasets' => [
			            [
			                'label' => "$title_gvs",
			                'backgroundColor' => '#FF0000',
			                'borderColor' => '#FF0000',
			                'pointBackgroundColor' => '#FF0000',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FF0000',
			                'data' => [ 
			                	$rows[11]['gvs'], 
			                	$rows[10]['gvs'], 
			                	$rows[9]['gvs'], 
			                	$rows[8]['gvs'], 
			                	$rows[7]['gvs'], 
			                	$rows[6]['gvs'], 
			                	$rows[5]['gvs'], 
			                	$rows[4]['gvs'], 
			                	$rows[3]['gvs'], 
			                	$rows[2]['gvs'], 
			                	$rows[1]['gvs'], 
			                	$rows[0]['gvs']
			                		]
			            ],
			            [
			                'label' => "$title_gvs1",
			                'backgroundColor' => '#FF7373',
			                'borderColor' => '#FF7373',
			                'pointBackgroundColor' => '#FF7373',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FF7373',
			                'data' => [ 
			                	$rows[11]['gvs1'], 
			                	$rows[10]['gvs1'], 
			                	$rows[9]['gvs1'], 
			                	$rows[8]['gvs1'], 
			                	$rows[7]['gvs1'], 
			                	$rows[6]['gvs1'], 
			                	$rows[5]['gvs1'], 
			                	$rows[4]['gvs1'], 
			                	$rows[3]['gvs1'], 
			                	$rows[2]['gvs1'], 
			                	$rows[1]['gvs1'], 
			                	$rows[0]['gvs1']
			                		]
			            ],
			            [
			                'label' => "$title_hvs",
			                'backgroundColor' => '#0C5DA5',
			                'borderColor' => '#0C5DA5',
			                'pointBackgroundColor' => '#0C5DA5',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#0C5DA5',
			                'data' => [ 
			                	$rows[11]['hvs'], 
			                	$rows[10]['hvs'], 
			                	$rows[9]['hvs'], 
			                	$rows[8]['hvs'], 
			                	$rows[7]['hvs'], 
			                	$rows[6]['hvs'], 
			                	$rows[5]['hvs'], 
			                	$rows[4]['hvs'], 
			                	$rows[3]['hvs'], 
			                	$rows[2]['hvs'], 
			                	$rows[1]['hvs'], 
			                	$rows[0]['hvs']
			                		]
			            ],
			            [
			                'label' => "$title_hvs1",
			                'backgroundColor' => '#679FD2',
			                'borderColor' => '#679FD2',
			                'pointBackgroundColor' => '#679FD2',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#679FD2',
			                'data' => [ 
			                	$rows[11]['hvs1'], 
			                	$rows[10]['hvs1'], 
			                	$rows[9]['hvs1'], 
			                	$rows[8]['hvs1'], 
			                	$rows[7]['hvs1'], 
			                	$rows[6]['hvs1'], 
			                	$rows[5]['hvs1'], 
			                	$rows[4]['hvs1'], 
			                	$rows[3]['hvs1'], 
			                	$rows[2]['hvs1'], 
			                	$rows[1]['hvs1'], 
			                	$rows[0]['hvs1']
			                		]
			            ],
			        ]
			    ]
			]);
		return $html;
    }    
    public function getChartHTMLALLelectric()
    {
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(12)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
			    
        $title_elday = 'ЭлДень';
        $title_elnight = 'ЭлНочь';
			
		$html = ChartJs::widget([
			    'type' => 'bar',
			    'options' => ['height' => 'auto','width' => 'auto'],
			    'data' => [
			        'labels' => [
			        	$rows[11]['created_at'], 
			        	$rows[10]['created_at'], 
			        	$rows[9]['created_at'], 
			        	$rows[8]['created_at'], 
			        	$rows[7]['created_at'], 
			        	$rows[6]['created_at'], 
			        	$rows[5]['created_at'], 
			        	$rows[4]['created_at'], 
			        	$rows[3]['created_at'], 
			        	$rows[2]['created_at'], 
			        	$rows[1]['created_at'], 
			        	$rows[0]['created_at']
			        	],
			        'datasets' => [
			            [
			                'label' => "$title_elday",
			                'backgroundColor' => '#FFEB00',
			                'borderColor' => '#FFEB00',
			                'pointBackgroundColor' => '#FFEB00',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FFEB00',
			                'data' => [ 
			                	$rows[11]['elday'], 
			                	$rows[10]['elday'], 
			                	$rows[9]['elday'], 
			                	$rows[8]['elday'], 
			                	$rows[7]['elday'], 
			                	$rows[6]['elday'], 
			                	$rows[5]['elday'], 
			                	$rows[4]['elday'], 
			                	$rows[3]['elday'], 
			                	$rows[2]['elday'], 
			                	$rows[1]['elday'], 
			                	$rows[0]['elday']
			                		]
			            ],
			            [
			                'label' => "$title_elnight",
			                'backgroundColor' => '#FFF473',
			                'borderColor' => '#FFF473',
			                'pointBackgroundColor' => '#FFF473',
			                'pointBorderColor' => "#fff",
			                'pointHoverBackgroundColor' => "#fff",
			                'pointHoverBorderColor' => '#FFF473',
			                'data' => [ 
			                	$rows[11]['elnight'], 
			                	$rows[10]['elnight'], 
			                	$rows[9]['elnight'], 
			                	$rows[8]['elnight'], 
			                	$rows[7]['elnight'], 
			                	$rows[6]['elnight'], 
			                	$rows[5]['elnight'], 
			                	$rows[4]['elnight'], 
			                	$rows[3]['elnight'], 
			                	$rows[2]['elnight'], 
			                	$rows[1]['elnight'], 
			                	$rows[0]['elnight']
			                		]
			            ],			            
			        ]
			    ]
			]);
		return $html;
    }     
    public function getChartHTMLALLmoney()
    {
    	$rows = (new \yii\db\Query())
			    ->select(['*'])
			    ->from('indications')
			    ->where(['id_u' => \Yii::$app->user->identity->id])
			    ->limit(12)
			    ->orderBy(['id' => SORT_DESC])
			    ->all();
			    
        $title_gvs = 'Сумма';
			
		$html = ChartJs::widget([
			    'type' => 'line',
			    'options' => ['height' => 'auto','width' => 'auto'],
			    'data' => [
			        'labels' => [
			        	$rows[11]['created_at'], 
			        	$rows[10]['created_at'], 
			        	$rows[9]['created_at'], 
			        	$rows[8]['created_at'], 
			        	$rows[7]['created_at'], 
			        	$rows[6]['created_at'], 
			        	$rows[5]['created_at'], 
			        	$rows[4]['created_at'], 
			        	$rows[3]['created_at'], 
			        	$rows[2]['created_at'], 
			        	$rows[1]['created_at'], 
			        	$rows[0]['created_at']
			        	],
			        'datasets' => [
			            [
			                'label' => "$title_gvs",
                'backgroundColor' => "rgba(179,181,198,0.2)",
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
			                'data' => [ 
			                	$rows[11]['gvs'], 
			                	$rows[10]['gvs'], 
			                	$rows[9]['gvs'], 
			                	$rows[8]['gvs'], 
			                	$rows[7]['gvs'], 
			                	$rows[6]['gvs'], 
			                	$rows[5]['gvs'], 
			                	$rows[4]['gvs'], 
			                	$rows[3]['gvs'], 
			                	$rows[2]['gvs'], 
			                	$rows[1]['gvs'], 
			                	$rows[0]['gvs']
			                		]
			            ],
			        ]
			    ]
			]);
		return $html;
    }    
}
