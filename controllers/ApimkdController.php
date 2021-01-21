<?php
namespace app\controllers;

use Yii;

use app\models\Mkd;

class ApimkdController extends \yii\rest\Controller
{
    public function actionMkdview() {
		
       	$mkd  = Mkd::find()->all();
    	if ($mkd) {
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		return $mkd;
    	}else{ 
    		return 'not found';
    	}		
    }	
}
