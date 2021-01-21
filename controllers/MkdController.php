<?php

namespace app\controllers;

use Yii;
use app\models\Add_user;
use app\models\Add_personal;
use app\models\Area;
use app\models\Mkd;
use app\models\MkdSearch;
use app\models\MkdmapslistSearch;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

class MkdController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => [
					'identityClass' => 'app\models\User',
					'enableAutoLogin' => false,
					'loginUrl' => ['site/first'],
				],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view','add_user'],
                        'roles' => [AdminRbac::PERMISSION_GOVERNMENT_PANEL],
                    ],                	
                    [
                        'allow' => true,
                        'actions' => ['index','view','create','update','delete','add_personal'],
                        'roles' => [AdminRbac::PERMISSION_SPEC_PANEL],
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
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			if($action->id == 'delete' || $action->id == 'update' ){
				if(\Yii::$app->user->can('moder')){
					return true;
				}elseif (\Yii::$app->user->can('agent') && $this->findModel($_GET['id'])->id_a == \Yii::$app->user->identity->id_org){
					return true;
				}else{
					return $this->redirect(['site/block']);
				}
			}elseif($action->id == 'view'){
				if(\Yii::$app->user->can('moder')){
					return true;
				}elseif((\Yii::$app->user->can('spec')) && $this->findModel($_GET['id'])->id_a == \Yii::$app->user->identity->id_org){
					return true;
				}elseif(\Yii::$app->user->identity->role == 'government' && $this->findModel($_GET['id'])->id == \Yii::$app->user->identity->id_mkd){
					return true;
				}else{
					return $this->redirect(['site/block']);
				}
			}
			return true;
		}
	}
	public function actionAdd_user($id_mkd,$role) 
	{
		
		$model = new Add_user();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->add_u($role)) {
                return $this->redirect(['view', 'id' => $user->id_a]);
            }
        }		

		return $this->renderAjax('add_user', [
            'model' => $model, 'id_mkd' => $id_mkd, 'role' => $role
        ]);
	}
	public function actionAdd_personal($id_mkd,$id_org) 
	{
		
		$model = new Add_personal();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->add_pf()) {
                return $this->redirect(['view', 'id' => $user->id_a]);
            }
        }		

		return $this->renderAjax('add_personal', [
            'model' => $model, 'id_mkd' => $id_mkd, 'id_org' => $id_org,
        ]);
	}	
    public function actionIndex()
    {
        $searchModel = new MkdSearch();
        //$searchModel = new MkdmapslistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {	
    	$model = $this->findModel($id);
    	$area  = Area::findById( $model->id_a );

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchthismkd(Yii::$app->request->queryParams,$model->id,'users');

        $searchModel_g = new UserSearch();
        $dataProvider_g = $searchModel_g->searchthismkd(Yii::$app->request->queryParams,$model->id,'government');

        $searchModel_p = new UserSearch();
        $dataProvider_p = $searchModel_p->searchthismkd(Yii::$app->request->queryParams,$model->id,'personal');        

        return $this->render('view', [
            'model' => $model, 'area' => $area,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel_g' => $searchModel_g,
            'dataProvider_g' => $dataProvider_g,
            'searchModel_p' => $searchModel_p,
            'dataProvider_p' => $dataProvider_p,
        ]);
    }
    public function actionCreate()
    {
        $model = new Mkd();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Mkd::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
