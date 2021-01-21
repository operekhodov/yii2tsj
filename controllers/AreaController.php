<?php

namespace app\controllers;

use Yii;
use app\models\Area;
use app\models\AreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

class AreaController extends Controller
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
                        'actions' => ['index','view'],
                        'roles' => [AdminRbac::PERMISSION_HALFUSER_PANEL],
                    ],                	
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [AdminRbac::PERMISSION_AGENT_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','delete'],
                        'roles' => [AdminRbac::PERMISSION_MODER_PANEL],
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
			if($action->id == 'update' ){
				if(\Yii::$app->user->can('moder')){
					return true;
				}elseif (\Yii::$app->user->can('agent') && $this->findModel($_GET['id'])->id == \Yii::$app->user->identity->id_org){
					return true;
				}else{
					return $this->redirect(['site/block']);
				}
			}
			return true;
		}
	}    
    public function actionIndex()
    {
        $searchModel = new AreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionCreate()
    {
        $model = new Area();
        $model->createddate = time();
        
        if ($model->load(Yii::$app->request->post()) ){
        	$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
        	$model->module = json_encode(Yii::$app->request->post( 'Area' )['module'],JSON_HEX_QUOT);
			
        	$arr_img = array();
        	foreach ($model->imageFiles as $file) {
        		$filename = uniqid().".{$file->extension}";
        		$url_filename = "uploads/".$filename;
                $file->saveAs($url_filename);
                //$this->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            $model->imageFiles = null;
				$model->logo = json_encode($arr_img);
	        
        	$model->save();
        	
        }


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

        if ($model->load(Yii::$app->request->post()) ){
        	$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
        	$model->module = json_encode(Yii::$app->request->post( 'Area' )['module'],JSON_HEX_QUOT);
			
        	$arr_img = array();
        	foreach ($model->imageFiles as $file) {
        		$filename = uniqid().".{$file->extension}";
        		$url_filename = "uploads/".$filename;
                $file->saveAs($url_filename);
                //$this->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            $model->imageFiles = null;
            if($arr_img){$model->logo = json_encode($arr_img);}
        	$model->save();
        	
        }

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
        if (($model = Area::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
