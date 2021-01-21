<?php

namespace app\controllers;

use app\models\User;
use app\models\Area;
use app\models\Mkd;
use app\models\Numerators;
use app\models\NumeratorsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use app\models\PasswordChangeForm;
use yii\web\UploadedFile;
use app\models\ProfileForm;

class ProfileController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
		$user = $this->findModel();
		if($user->id_org && $user->role != 'user') {
			$area = Area::findById( $user->id_org );
			return $this->render('index', [
	            'model' => $user, 'area' => $area
	        ]);			
		}else{ 
			$area = Area::findById( Mkd::findById($user->id_a)->id_a ); 
			$searchModel_n = new NumeratorsSearch();
			$dataProvider_n = $searchModel_n->search(Yii::$app->request->queryParams);
			return $this->render('index', [
	            'model' => $user, 'area' => $area,
				'searchModel' => $searchModel_n,
				'dataProvider' => $dataProvider_n,	            
	        ]);
		}
    }
    public function actionAdd_numerator(){
    	
        $model = new Numerators();
		$model->id_u = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
            return $this->redirect(['index']);
        }

		return $this->renderAjax('add_numerator', [
			'model' => $model,
            //'model' => $model, 'id_mkd' => $id_mkd, 'role' => $role
        ]);
    }
    public function actionUp_numerator($id){
    	$model = Numerators::findById($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
            return $this->redirect(['index']);
        }    	
		return $this->renderAjax('up_numerator', [
            'model' => $model, //'id_mkd' => $id_mkd, 'role' => $role
        ]);    	
    }
	public function actionDelfoto($id,$type)
    {          
        $user = User::find()->where(['id' => $id])->one();
		if ($type == 'foto') {
			$img = json_decode($user->foto);
			if($img){
				$img[0] = substr_replace($img[0], '/u',0 ,1); 
				unlink(Yii::$app->basePath . '/web/' . $img[0]);
				$user->foto = '[]';
				$user->save();
			}
			$success=true;
			return json_encode($success);
		}else{
			$img = json_decode($user->proof);
			if($img){
				$img[0] = substr_replace($img[0], '/u',0 ,1); 
				unlink(Yii::$app->basePath . '/web/' . $img[0]);
				$user->proof = '[]';
				$user->save();
			}
			$success=true;
			return json_encode($success);			
		}
    }
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }
	public function actionChangephone()
	{
		if ($_POST['pin']){
			$pin = ProfileForm::decode($_POST['checkphone'],'q2s3c4');
			if($_POST['pin'] == $pin) {
				$user = $this->findModel();
				$user->phonenumber =$_POST['phonenumber'];
				$user->save();
				Yii::$app->session->addFlash('success', Yii::t('app', 'PF_new_phone'));
				$this->redirect(['/profile/update']);
			}else{
				Yii::$app->session->addFlash('warning', Yii::t('app', 'PF_new_phone_error'));
			}			
		}
	    
	    if ($_POST['phonenumber']) {
			$phonenumber = preg_replace('/\D+/', '', $_POST['phonenumber']);
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
			$this->view->params['phonenumber'] = $phonenumber;
			$pin = ProfileForm::encode($pin,'q2s3c4');
			$this->view->params['checkphone'] = $pin;	    	
	        return $this->renderAjax('mobpin', []);
	    }else{
	    	return $this->renderAjax('mobphone', []);
	    }
	}
	public function actionUpdate() 
	{
		$model = new ProfileForm();
		$user = User::findById(Yii::$app->user->identity->getId());
			
        if ($model->load(Yii::$app->request->post())) {
            if ($model->update_profile(Yii::$app->user->identity->getId())) {
                Yii::$app->getSession()->setFlash('success',  Yii::t('app', 'PF_success'));
                return $this->redirect('index');
            }
        }
        
        return $this->render('update', [
            'model' => $model, 'user' => $user
        ]);		
	}
    public function actionPasswordChange()
    {
        $user = $this->findModel();
        $model = new PasswordChangeForm($user);
 
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }    
}