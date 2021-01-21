<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Controller;
use app\models\User;

class ApiusersController extends ActiveController
{
	public $modelClass = 'app\models\User';
	
    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "userview" ] = ['GET' ];
        $verbs[ "userupdate" ] = ['POST' ];
        
        return $verbs;
    }

    public function actionUserview($id_u) {
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {
       		$user  = User::find()->where('id = :id_u', [':id_u'  => $id_u])->all();
    		if ($user) {
    			return $user;
    		}
		}else{
			return 'not enough rights';
		}
    }
    public function actionUserupdate($id_u){
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {    	
	    	$bodyParam = \Yii::$app->getRequest()->getBodyParams();


		}else{
			return 'not enough rights';
		}
    }
}