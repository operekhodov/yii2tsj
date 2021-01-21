<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApimobregController extends ActiveController
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
	public $modelClass = 'app\models\User';
    public function actionMobreg()
    {
    	$bodyParam = \Yii::$app->getRequest()->getBodyParams();
        $user = new User();
        $user->username = Yii::$app->security->generateRandomString(22);
        $user->phonenumber = $bodyParam['phonenumber'];
        $user->setPassword($bodyParam['password']);
        $user->status = User::STATUS_WAIT;
        $user->role = 'user';
        $user->lname = $bodyParam['lname'];
        $user->locality = $bodyParam['locality'];
        $user->street = $bodyParam['street'];
        $user->num_house = $bodyParam['num_house'];
        
		if ($user->save()) {
			return 'done';
		}
    }
    public function actionCheckphone($phonenumber){
		$phonenumber = '7'.preg_replace('/\D+/', '', $phonenumber);
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
		
		return $pin;
    }
    
}
?>