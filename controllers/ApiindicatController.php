<?php
namespace app\controllers;

use Yii;

use app\models\Indicat;
use yii\rest\ActiveController;
use yii\web\Controller;
use app\models\User;

class ApiindicatController extends ActiveController
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
	public $modelClass = 'app\models\Options';

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "indicatview" ] = ['GET' ];
        $verbs[ "indicatcreate" ] = ['POST' ];
        
        return $verbs;
    }

    public function actionIndicatview($id_u) {
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {
       		$myindicat  = Indicat::find()->where('id_u = :id_u', [':id_u'  => $id_u])->all();
    		if ($myindicat) {
    			return $myindicat;
    		}
		}else{
			return 'not enough rights';
		}
    }
    public function actionIndicatcreate($id_u){
		$headers = Yii::$app->request->headers;
		if(User::validBearer($id_u,substr($headers->get('Authorization'),7))) {    	
	    	$bodyParam = \Yii::$app->getRequest()->getBodyParams();
	    	$y_m = substr($bodyParam['created_at'],0,7); 
			$last_indicat  = Indicat::find()->where('id_u = :id_u', [':id_u'  => $id_u])->andWhere(['like', 'created_at', $y_m])->one();
			if($last_indicat) {
				$last_indicat->load($bodyParam, '');
				if ($last_indicat->validate() && $last_indicat->save()) {
				    $response = \Yii::$app->getResponse();
				    $response->setStatusCode(200);
				} else {
				    throw new HttpException(422, json_encode($model->errors));
				}
				return $last_indicat;			
			}else{
				$indicat = new Indicat();
				
				$indicat->load($bodyParam, '');
				$indicat->id_u = $id_u;
				if ($indicat->validate() && $indicat->save()) {
				    $response = \Yii::$app->getResponse();
				    $response->setStatusCode(200);
				} else {
				    throw new HttpException(422, json_encode($model->errors));
				}
				return $indicat;
			}
		}else{
			return 'not enough rights';
		}
    }
}