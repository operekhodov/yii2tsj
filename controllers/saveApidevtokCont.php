<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\Devtoken;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApidevtokenController extends ActiveController
{
    public $modelClass = 'app\models\Devtoken';

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }
    public function actions()
    {
        return [];
    }

    /**
     * Handle UPDEVTOK
     *
     * @param null $deviceid
     * @return string
     */    
    public function actionUpdevtok($deviceid)
    {
        $devtoken = Devtoken::find()->where(['deviceid' => $deviceid])->one();
        if ($devtoken) {
	        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
	        $devtoken->load($bodyParams, '');
	        if ($devtoken->validate() && $devtoken->save()) {
	            $response = \Yii::$app->getResponse();
	            $response->setStatusCode(200);
	        } else {
	            throw new HttpException(422, json_encode($model->errors));
	        }
	        return $devtoken;
        } else {
            
	        $devtoken = new Devtoken();
	        $bodyParam = \Yii::$app->getRequest()->getBodyParams();
	        $devtoken->load($bodyParam, '');
	        if ($devtoken->validate() && $devtoken->save()) {
	            $response = \Yii::$app->getResponse();
	            $response->setStatusCode(201);
	            $id = implode(',', array_values($devtoken->getPrimaryKey(true)));
	            $response->getHeaders()->set('Location', Url::toRoute([$id], true));
	        } else {
	            // Validation error
	            throw new HttpException(422, json_encode($devtoken->errors));
	        }
	        return $devtoken;
        }
    }
    public function actionView($deviceid){
    	$devtoken = Devtoken::find()->where(['deviceid' => $deviceid])->one();
    	if ($devtoken) {
    		return $devtoken;
    	}
    }
}