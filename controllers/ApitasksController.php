<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Tasks;
use yii\rest\ActiveController;

class ApitasksController extends ActiveController
{
    public $modelClass = 'app\models\Tasks';

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "taskview" ] = ['GET' ];
        $verbs[ "taskcreate" ] = ['POST' ];
        
        return $verbs;
    }

    public function actionTaskview($id_u) {
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {
       		$mytasks  = Tasks::find()->where('createdby = :createdby', [':createdby'  => $id_u])->all();
    		if ($mytasks) {
    			return $mytasks;
    		}
		}else{
			return 'not enough rights';
		}
    }
    public function actionTaskcreate($id_u){
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {    	
	    	$bodyParam = \Yii::$app->getRequest()->getBodyParams();

		}else{
			return 'not enough rights';
		}
    }
}