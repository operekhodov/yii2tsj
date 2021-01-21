<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use app\models\Bank;
use app\models\User;
use app\models\Indicat;
use app\models\IndicatSearch;
use app\models\Tasks;
use app\models\TasksSearch;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\UploadForm;
use app\models\Area;
use app\models\Module;
use app\models\Logs;
use app\models\Mkd;
use app\models\MkdmapslistSearch;
use yii\web\UploadedFile;
use yii\httpclient\Client;
use app\rbac\Rbac as AdminRbac;
use yii\helpers\Url;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => [
					'identityClass' => 'app\models\User',
					'enableAutoLogin' => false,
					'loginUrl' => ['site/maps'],
				],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['first','login', 'checkphone', 'signup','moblogin','mobsignup','mobpin','other_login','pass2mail','reestr'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','logout','video','golos','del','cars','docs','block','testchat','delchat','maps'],
                        'roles' => [AdminRbac::PERMISSION_HALFUSER_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['bank','bankstatement','upload'],
                        'roles' => [AdminRbac::PERMISSION_DISPATCHER_PANEL],
                    ],                    
                ],
            ],        	
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionReestr(){
    	return $this->render('reestr');
    }
	public function actionTestchat() 
	{
		return $this->render('test_chat');
	}    
	public function actionDelchat() 
	{
		return $this->render('del_chat');
	}	
	public function actionFirst() 
	{
		return $this->render('first');
	}
	public function beforeAction($action)
	{//var_dump(Yii::$app->controller->id);die();
	//$action->id == 'video' 
	//var_dump($action->id);
		if(!Yii::$app->user->isGuest){
			if($action->id == 'login' || $action->id == 'first'){
				return $this->redirect(['index']); 
			}else{
				$all_action = Module::getAllAction();
				$id_a = Area::findById( Mkd::findById(Yii::$app->user->identity->id_a)->id_a )->id;//User::findById( Yii::$app->user->identity->getId() )->id_a;
				$area_mod = json_decode(Area::findById( $id_a )->module);
				$arr_action = (Module::getArrNameMod($area_mod)) ? Module::getArrNameMod($area_mod) : array();
				
			    if (parent::beforeAction($action)) {
			    	if(!in_array($action->id, $arr_action) && in_array($action->id,$all_action) && \Yii::$app->user->identity->role != 'root'){
			    		return $this->redirect(['block']);
			    	}else{
			    		//$t = Logs::addLog(Yii::$app->controller->id.'/'.$action->id,'','','');
			    		return true;
			    	}
			    }
			}
		}else{
			if($action->id == 'index') {
				return $this->redirect(['maps']); 
			}else{
				return true;
			}
		}
	} 
    public function actionIndex()
    {
    	$role = \Yii::$app->user->identity->role;
		if ( $role == 'root' || $role == 'admin' || $role == 'moder'){
			return $this->render('index_a');
		}elseif( $role == 'halfuser' || $role == 'user' || $role == 'government' ){
			//return $this->render('index_b');
	        //$searchModel = new IndicatSearch();
	        //$dataProvider = $searchModel->searchmyindicat(Yii::$app->request->queryParams);
	        
	        $searchModeltask = new TasksSearch();
	        $dataProvidertask = $searchModeltask->searchmytask(Yii::$app->request->queryParams);
	        
	        return $this->render('index_b', [
	            //'searchModel' => $searchModel,
	            //'dataProvider' => $dataProvider,
	            'searchModeltask' => $searchModeltask,
	            'dataProvidertask' => $dataProvidertask,            
	        ]);			
		}elseif( $role == 'boss' || $role == 'dispatcher' || $role == 'spec' || $role == 'agent' ){
			return $this->render('index_c');
		}
		/*
		else{
	        //$searchModel = new IndicatSearch();
	        //$dataProvider = $searchModel->searchmyindicat(Yii::$app->request->queryParams);
	        
	        $searchModeltask = new TasksSearch();
	        $dataProvidertask = $searchModeltask->searchmytask(Yii::$app->request->queryParams);
	        
	        return $this->render('index', [
	            //'searchModel' => $searchModel,
	            //'dataProvider' => $dataProvider,
	            'searchModeltask' => $searchModeltask,
	            'dataProvidertask' => $dataProvidertask,            
	        ]);
    	}
        */
    }
    public function actionLogin()
    {
		if($_POST){
			$tik_tak = $_POST['tik_tak']+1;
		}else{
			$tik_tak = 0;
		}
        $model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->redirect('index');
        }
        return $this->render('login', [
            'model' => $model, 'tik_tak' => $tik_tak
        ]);
    }
    public function actionPass2mail()
    {
	
		if($_POST){
			$phonenumber = preg_replace('/\D+/', '', $_POST['phonenumber']);
			$phonenumber = substr($phonenumber,1); 
			$user = User::findByPhone_and_mail($phonenumber,$_POST['email']);
			if($user) {
				$new_pass = Yii::$app->security->generateRandomString(5);
				$htmlBody = $new_pass.Yii::t('app','P2M_MAIL_BODY');
				$user->setPassword($new_pass);
				Yii::$app->mailer->compose()
				    ->setFrom('tsj@wtot.ru')
				    ->setTo($_POST['email'])
				    ->setSubject(Yii::t('app','P2M_MAIL_SUBJECT'))
				    ->setHtmlBody($htmlBody)
				    ->send();
				if ($user->save()) {
					Yii::$app->getSession()->setFlash('success', Yii::t('app','P2M_SUCCESS'));
					return $this->redirect('login');
				}
			}else{
				 Yii::$app->getSession()->setFlash('warning', Yii::t('app','P2M_ERROR'));
			}
	}
    	
    	return $this->render('pass2mail');
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public function actionCheckphone()
    {
		if (Yii::$app->user->isGuest) {
			if($_POST['pin']){
				$this->view->params['checkphone'] = $_POST['pin'];
				$this->view->params['phonenumber'] = $_POST['phonenumber'];
				return $this->render('mobpin');				
			}elseif($_POST['phonenumber']){
				//var_dump(stristr($_POST['phonenumber'], '(', true));
				if($_POST['__phone_prefix'] != '' && $_POST['__phone_prefix'] != stristr($_POST['phonenumber'], '(', true)) {
					$_POST['phonenumber'] = $_POST['__phone_prefix'] . stristr($_POST['phonenumber'], '(');
				}
				$phonenumber = preg_replace('/\D+/', '', $_POST['phonenumber']);
				//var_dump($phonenumber);
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
				/*
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
				*/
				$pin = '1111';
				$this->view->params['phonenumber'] = $phonenumber;
				$pin = SignupForm::encode($pin,'q2s3c4');
				$this->view->params['checkphone'] = $pin;
				return $this->render('mobpin');
			}else{
				return $this->render('checkphone');
			}
		}else{
    		return $this->redirect('index');
    	}		
	}
    public function actionSignup()
    {	
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
            	
                Yii::$app->getSession()->setFlash('success',  Yii::t('app', 'SUF_reg_success'));
                return $this->redirect('login');
            }
        }
        return $this->render('signupform', [
            'model' => $model, 
        ]);
    }  
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('upimg', ['model' => $model]);
    }
    public function actionBlock()
    {
    	return $this->render('block');
    }
    public function actionDocs()
    {
    	return $this->render('docs');
    }        
    public function actionCars()
    {
    	return $this->render('cars');
    }       
    public function actionDel()
    {
    	return $this->render('del');
    }    
    public function actionVideo()
    {
    	return $this->render('video');
    }
    public function actionGolos()
    {
    	return $this->render('golos');
    }    
    public function actionMaps()
    {
        if (Yii::$app->request->isPjax) {
        	$mkd = Mkd::findById(Yii::$app->request->post()['city']);
			$data = Mkd::getInfoForMaps(Yii::$app->request->post()['type'],$mkd->city,$mkd->geo);
			$search_bar = ['city'=>$mkd->city,'type'=>Yii::$app->request->post()['type']];
			$searchModel = new MkdmapslistSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			$dataProvider->query->andWhere(['=','city',$mkd->city]);
			if(Yii::$app->request->post()['type'] != 'all'){
				if(Mkd::getAllMkdThisAreaType($search_bar['type'])){
					$dataProvider->query->andWhere(['in','id',Mkd::getAllMkdThisAreaType($search_bar['type'])]);
				}else{
					$dataProvider->query->andWhere(['=','id','a']);
				}
			}
            return $this->renderAjax('maps', [
            	'data'=>$data, 
            	'search_bar'=>$search_bar,
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,            	
            	]);
        }else{
        	$geo		= $mkd = Mkd::findById(1);
			$data		= Mkd::getInfoForMaps('all','Первоуральск',$mkd->geo);
			$search_bar = ['city'=>'Первоуральск','type'=>'all'];
			$searchModel = new MkdmapslistSearch();
			Yii::$app->request->queryParams['city'] = 'Первоуральск';
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			$dataProvider->query->andWhere(['=','city','Первоуральск']);
			return $this->render('maps',[
				'data'=>$data, 
				'search_bar'=>$search_bar,
	            'searchModel' => $searchModel,
	            'dataProvider' => $dataProvider,				
				]);
        }
    }
    public function actionBank()
    {
		$model = Bank::findOne(1);
		$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('GET')
		    ->setUrl('https://enter.tochka.com/sandbox/v1/statement/result/044525999.2019-09-10.2019-09-01.40702810101270000000')
		    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
		    ->send();
		if ($response->isOk) {
		    $newUserId = $response->data['id'];
		    $responseData = $response->getData();
		    $this->view->params['statement'] = $response->content;
		    
		}

    	return $this->render('bank');
    }
    public function actionBankstatement()
    {
    	
    	
		$model = Bank::findOne(1);
		$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('POST')
		    ->setUrl('https://enter.tochka.com/sandbox/v1/statement')
		    ->setFormat(Client::FORMAT_JSON)
		    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
		    ->setData([ 
        		"account_code" => "40702810101270000000",
        		"bank_code" => "044525999",
        		"date_end" => "2019-09-10",
        		"date_start" => "2019-09-01"])
		    ->send();
		if ($response->isOk) {
		    $newUserId = $response->data['id'];
		    $responseData = $response->getData();
		    echo $response->content;
		    
		}

    	return $this->render('bank');
    }
	public function actionMoblogin()
	{
		if (Yii::$app->user->isGuest) {
			if($_POST['phonenumber']){
				if($_POST['__phone_prefix'] != '' && $_POST['__phone_prefix'] != stristr($_POST['phonenumber'], '(', true)) {
					$_POST['phonenumber'] = $_POST['__phone_prefix'] . stristr($_POST['phonenumber'], '(');
				}
				$phonenumber = preg_replace('/\D+/', '', $_POST['phonenumber']);
				//var_dump($phonenumber);
				$model = User::findOne(['phonenumber' => $phonenumber]);//substr($phonenumber,1)]);
				if($model){
					//$phonenumber = preg_replace('/\D+/', '', $_POST['phonenumber']);
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
					$this->view->params['phonenumber'] = $phonenumber;
					return $this->render('mobpin');
				}else{
					Yii::$app->session->addFlash('warning', 'Телефона нет в системе. Пройдите регистрацию.');
					$model = new SignupForm();
					$model->phonenumber = substr($phonenumber,1);
					return $this->render('signupform', [
						'model' => $model, 
					]);
				}
				
	
	
	/*
				$client = new Client();
				$response = $client->createRequest()
				    ->setMethod('POST')
				    ->setUrl('https://api.new-tel.net/call/start-password-call')
				    ->setFormat(Client::FORMAT_JSON)
				    ->addHeaders(['Authorization' => $key])
				    ->setData(["async" => "1","dstNumber" => "$phonenumber"])
				    ->send();
				    
				    var_dump($response);
	
				if ($response->isOk) {
				    $newUserId = $response->data['id'];
				    $responseData = $response->getData();
				    echo $response->content;
				    
				}			*/
			}
			return $this->render('moblogin',['tik_tak' => $_POST['tik_tak']]);
		}else{
    		return $this->redirect('index');
    	}		
	}
	public function actionMobpin()
	{
		if (Yii::$app->user->isGuest) {
			if($_POST['checkphone']){
				$pin = SignupForm::decode($_POST['checkphone'],'q2s3c4');
				if($_POST['pin'] == $pin) {
					$model = new SignupForm();
					$model->phonenumber = $_POST['phonenumber'];//substr($_POST['phonenumber'],1);
					return $this->render('signupform', [
						'model' => $model, 
					]);
				}else{
					Yii::$app->session->addFlash('warning', 'Номер не подтвержден. Попробуйте еще раз.');
					return $this->render('checkphone');
				}
			}elseif($_POST['pin']){
				$pin = $_POST['pin'];
				$phonenumber = $_POST['phonenumber'];
				$model = User::findOne(['phonenumber' => substr($phonenumber,1)]);
				if($pin == $model->pin){
					Yii::$app->user->login($model);
					if($model->status == 1) { 
						return $this->redirect(['index']); 
					}elseif($model->status == 2){
						Yii::$app->session->addFlash('warning', 'Для активации аккаунта заполните Ваш профиль.');
						$this->redirect(['/profile/index']);	
					}
				}else{
					Yii::$app->session->addFlash('warning', 'Пароль неверен.');
					$this->view->params['phonenumber'] = $phonenumber;
					return $this->render('mobpin');
				}
			}			
			
		}else{
			return $this->redirect('index');
		}
    }
}
