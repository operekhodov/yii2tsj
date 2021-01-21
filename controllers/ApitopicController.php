<?php
namespace app\controllers;

use Yii;

use app\models\Topic;
use yii\rest\ActiveController;
use yii\web\Controller;
use app\models\User;

class ApitopicController extends ActiveController
{
public function behaviors()
{
return [
    [
        'class' => \yii\filters\ContentNegotiator::className(),
        'only' => ['index', 'view'],
        'formats' => [
            'application/json' => \yii\web\Response::FORMAT_JSON,
        ],
    ],
];
}	
	public $modelClass = 'app\models\Topic';

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "topicview" ] = ['GET' ];
        $verbs[ "topicans" ] = ['POST' ];
        
        return $verbs;
    }

    public function actionTopicview($id_u) {
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {
       		$topic = Topic::find()->where('id_a = :id_a', [':id_a'  => User::getIDA($id_u)])->all();
    		if ($topic) {
    			return $topic;
    		}
		}else{
			return 'not enough rights';
		}
    }
    public function actionTopicans($id_q){
		$headers = Yii::$app->request->headers;
		$bodyParam = \Yii::$app->getRequest()->getBodyParams();
		return $bodyParam['id_u'];
		if(User::validBearer($bodyParam['id_u'],substr($headers->get('Authorization'),7))) {    	
	    
			return $bodyParam['id_u'];

		}else{
			return 'not enough rights';
		}
    }
}