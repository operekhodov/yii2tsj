<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApimobloginController extends \yii\rest\Controller
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
	
	//public $modelClass = 'app\models\User';

	public function actionPass2login() 
	{
		$bodyParam = \Yii::$app->getRequest()->getBodyParams();
		$model = User::findOne(['phonenumber' => $bodyParam['phonenumber']]);
		
        if ($model != null and $model->validatePassword($bodyParam['password'])) {
			$model->auth_key = Yii::$app->security->generateRandomString(22);
			$model->save();
			$data = "{'id':$model->id,'auth_key':$model->auth_key}";
			return $data;
        } else {
            return 'incorrect';
        }		
		
	}
    public function actionMoblogin($phonenumber)
    {
			$model = User::findOne(['phonenumber' => $phonenumber]);
			if($model){
				//$phonenumber = '7'.$phonenumber;
				$phonenumber = $phonenumber;
			    for($i = 0; $i < 4; $i++) {
			        $pin .= mt_rand(0, 9);
			    }				
				$data = '{"async":1,"dstNumber":"'.$phonenumber.'","pin":'.$pin.'}';
				$methodName = 'call/start-password-call';
				$time = time();
				$keyNewtel = 'dc18b07d53b76bebf9c7c0821c96ab2454174d2623e75816';
				$params = $data;
				$writeKey = '52c76f7c72166c12444307f3fe8dd242b80416297274559e';
				$key = 'Bearer '.$keyNewtel.$time.hash( 'sha256' , $methodName."\n".$time."\n".$keyNewtel."\n".$params."\n".$writeKey);
				$resId = curl_init();
				
				curl_setopt_array($resId, [
				    CURLINFO_HEADER_OUT => true,
				    CURLOPT_HEADER => true,
				    CURLOPT_HTTPHEADER => [
				        'Accept: application/json',
				        'Authorization: '.$key ,
				        'Content-Type: application/json' ,
				    ],
				    CURLOPT_POST => true,
				    CURLOPT_RETURNTRANSFER => true,
				    CURLOPT_SSL_VERIFYPEER => false,
				    CURLOPT_URL => 'https://api.new-tel.net/call/start-password-call',
				    ]);
				 
				    if (1) {
				        curl_setopt($resId, CURLOPT_POSTFIELDS, $data);
				    }
				 
				    $response = curl_exec($resId);
				    $curlInfo = curl_getinfo($resId);
				
				
				$pin = substr(substr(stristr($response,'"pin":"'),7),0,4);
				$model->pin = $pin;
				$model->save();
				return 'done';
				
			}else{return 'not found number';}
    }
    public function actionMobpin()
    {
    	$bodyParam = \Yii::$app->getRequest()->getBodyParams();
    	$model = User::findOne(['phonenumber' => $bodyParam['phonenumber']]);

    	if($model){
	        //return $bodyParam;
	        //$arr = json_decode($bodyParam);
	        if($model->pin == $bodyParam['pin']){
	        	$model->auth_key = Yii::$app->security->generateRandomString(22);
	        	$model->save();
	        	$data = "{'id':$model->id,'auth_key':$model->auth_key}";
	        	return $data;
	        }else{
	        	return "wrong pin";
	        }
    	}
    }
}
?>