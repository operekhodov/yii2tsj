<?php

namespace app\controllers;

use Yii;
use app\models\Usera;
use app\models\User;
use app\models\Mkd;
use app\models\Area;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use yii\web\UploadedFile;
use app\models\ProfileForm;

class UserController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [AdminRbac::PERMISSION_DISPATCHER_PANEL],
                    ],
                ],
            ],        	
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];

    }

/*	public function beforeAction($action)
	{
		$all_action = Module::getAllAction();
		$id_a = Area::findById( Mkd::findById(Yii::$app->user->identity->id_a)->id_a )->id;
		$area_mod = json_decode(Area::findById( $id_a )->module);
		$arr_action = (Module::getArrNameMod($area_mod)) ? Module::getArrNameMod($area_mod) : array();
		
	    if (parent::beforeAction($action)) {
	    	if(!in_array($action->id, $arr_action) && in_array($action->id,$all_action) && \Yii::$app->user->identity->role != 'root'){
	    		return $this->redirect(['block']);
	    	}else{
	    		return true;
	    	}
	    }
	} */

	public function actionAccess_r($id)
	{
		if(Yii::$app->request->post()){
			$from = Yii::$app->request->post();
		 	unset($from["_csrf"]);
		 	unset($from["signup-button"]);
		
		 	$last_key = '123'; 
		 	foreach($from as $key => $value) {
				$new_key = str_replace('_'.$value,'',$key);
				if($new_key == $last_key || $last_key == '123'){
					$buf[]=$value;
					$last_key = $new_key;
				}else{
					$answer[$last_key] = $buf;
					$buf = array();
					$buf[]=$value;
					$last_key = $new_key; 			
				}
		 	}
		 	$answer[$last_key] = $buf;
		 	$user = $this->findModel($id); 
		 	$user->access_r = json_encode($answer);
		 	$user->save();
		}
		return $this->render('access_r');
	}
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
		$user = $this->findModel($id);
		if($user->id_org && $user->role != 'user') {
			$area = Area::findById( $user->id_org );
		}else{ $area = Area::findById( Mkd::findById($user->id_a)->id_a ); }		
		return $this->render('view', [
			'model' => $user, 'area' => $area
		]);		
	/*	if($user->id_org && $user->role != 'user') {
			$area = Area::findById( $user->id_org );
		}else{ $area = Area::findById( Mkd::findById($user->id_a)->id_a ); }
		
		if (Yii::$app->getUser()->identity->role == 'root') {
			return $this->render('view', [
				'model' => $user, 'area' => $area
			]);
		}elseif($user->id_a == Yii::$app->getUser()->identity->id_a && $user->role != 'root'){
			return $this->render('view', [
				'model' => $user, 'area' => $area
			]);
		}else{return $this->redirect(['site/block']);} */
    }
    public function actionCreate()
    {
        $user = new User();
        $model = new ProfileForm();
		
        if ($model->load(Yii::$app->request->post())) {
			if(!User::findByPhonenumber(substr(str_replace(['(', ')', '-','+',' '], '', $model->phonenumber),1))){
				$user->save();
			}
            if ($model->update_profile($user->id)) {
                Yii::$app->getSession()->setFlash('success',  Yii::t('app', 'PF_success'));
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }
        
        return $this->render('update', [
            'model' => $model, 'user' => $user
        ]);
    }
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        //if (Yii::$app->getUser()->identity->role == 'root') {
	        $model = new ProfileForm();
			if ($model->load(Yii::$app->request->post())) {
	            if ($model->update_profile($id)) {
	                Yii::$app->getSession()->setFlash('success',  Yii::t('app', 'PF_success'));
	                return $this->redirect(['view', 'id' => $id]);
	            }
	        }
	        return $this->render('update', ['model' => $model, 'user' => $user]);
        /*}elseif($user->id_a == User::getIDA(Yii::$app->getUser()->identity->id)){
	        $model = new ProfileForm();
			if ($model->load(Yii::$app->request->post())) {
	            if ($model->update_profile($id)) {
	                Yii::$app->getSession()->setFlash('success',  Yii::t('app', 'PF_success'));
	                return $this->redirect(['view', 'id' => $id]);
	            }
	        }
	        return $this->render('update', ['model' => $model, 'user' => $user]);        	
        }else{return $this->redirect(['site/block']);} */
	}
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/mkd/index']);
    }
    protected function findModel($id)
    {
        if (($model = Usera::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
