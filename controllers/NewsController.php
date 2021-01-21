<?php

namespace app\controllers;

use Yii;
use app\models\News;
use app\models\NewsSearch;
use app\models\User;
use app\models\Mkd;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

class NewsController extends Controller
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
                        'actions' => ['create','update','delete','delfoto'],
                        'roles' => [AdminRbac::PERMISSION_GOVERNMENT_PANEL],
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
				}elseif (User::findById($this->findModel($_GET['id'])->id_u)->id_org != \Yii::$app->user->identity->id_org){
					return $this->redirect(['site/block']);
				}
			}
			return true;
		}
	}
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
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
        $model = new News();
		
        if ($model->load(Yii::$app->request->post()) ){
        	if ($model->push){
        		$model->push = $model->push[0];
        	}
        	if ($model->fpage){
        		$model->fpage = $model->fpage[0];
        	}

	        if(Yii::$app->user->identity->role == 'government'){
	        	$model->mkd_ids = '["'.\Yii::$app->user->identity->id_mkd.'"]';
	        }else{
	        	$model->mkd_ids = json_encode(Yii::$app->request->post( 'News' )['mkd_ids'],JSON_HEX_QUOT);
	        }
	        $model->id_u = \Yii::$app->user->identity->id;;
			$model->datecreated = Yii::$app->formatter->asDate('now', 'dd,MM,yyyy');
	        $model->datetime  = Yii::$app->formatter->asDate('now', 'dd,MM,yyyy');

			$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
			        	
			$arr_img = array();
			foreach ($model->imageFiles as $file) {
				$filename = uniqid().".{$file->extension}";
				$url_filename = "uploads/".$filename;
                $file->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            $model->imageFiles = null;
			$model->imagesmas = json_encode($arr_img,JSON_UNESCAPED_UNICODE);
			$model->save();
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

			$a = (Yii::$app->request->post('News')['fpage']);
			$b = (Yii::$app->request->post('News')['push']);
			$model->fpage = $a ? $a[0] : $model->fpage;
			$model->push = $b ? $b[0] : $model->push;

			$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
			
			if($model->imageFiles){
			
				$arr_img = array();
				foreach ($model->imageFiles as $file) {
					$filename = uniqid().".{$file->extension}";
					$url_filename = "uploads/".$filename;
	                $file->saveAs($url_filename);
	                array_push($arr_img,$url_filename);
	            }
	            $model->imageFiles = null;
	            $arr_old = json_decode($model->imagesmas);
	            $arr_all = array_merge($arr_old, $arr_img);
				$model->imagesmas = json_encode($arr_all,JSON_UNESCAPED_UNICODE);
        	}else{
        		$model->imageFiles = null;
        	}
	        if(Yii::$app->user->identity->role != 'government'){
	        	$model->mkd_ids = json_encode(Yii::$app->request->post( 'News' )['mkd_ids'],JSON_HEX_QUOT);
	        }        	
			$model->save(); $k = true;
        }

        if ($k && $model->save()) {
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
	public function actionDelfoto()
    {	
    	$img_for_del	= $_GET[i];
    	$search 		= str_replace("/uploads/","", $_GET[i]);
    	$news			= News::find()->where(['like', 'imagesmas', $search])->one();
    	if ($news) {
    		// del file
    		unlink(Yii::$app->basePath . '/web' . $img_for_del);
    		// del value from BD
    		$arr_imgs			= (array) json_decode($news->imagesmas);
    		unset( $arr_imgs[ array_search( substr( $img_for_del, 1 ), $arr_imgs ) ] );
    		$arr_imgs			= array_values($arr_imgs);
    		$news->imagesmas	= json_encode($arr_imgs);
    		$news->save();
    		// return success
			$success			= true;
			return json_encode($success);    		
    	}
    }
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
