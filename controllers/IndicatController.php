<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Indicat;
use app\models\IndicatSearch;
use app\models\Area;
use app\models\User;
use app\models\Logs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

class IndicatController extends Controller
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
                        'actions' => ['create','index'],
                        'roles' => [AdminRbac::PERMISSION_HALFUSER_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view','update','delete'],
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
			if($action->id == 'view' || $action->id == 'delete' || $action->id == 'update'){
				if (User::findById($this->findModel($_GET['id'])->id_u)->id_a != \Yii::$app->user->identity->id_a && \Yii::$app->user->identity->role != 'root'){
					return $this->redirect(['site/block']);
				}
			}
			if(\Yii::$app->user->identity->status != 1 ){
				return $this->redirect(['profile/index','wait'=>1]);
			}else{
				
				$controller = Yii::$app->controller->id;
				$action 	= $action->id;
				$id			= $_GET['id'] ? $_GET['id'] : '';
				
				//$t = Logs::addLog($controller,$action,$id,'','',''); // $controller,$action,$id,$info,$dop,$note
				
				return true;
			}
		}
	}
    public function actionIndex()
    {
        $searchModel = new IndicatSearch();
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
    	$date_now = Yii::$app->formatter->asDate('now', 'dd.MM.yyyy');
		$y_m = substr($date_now,3,7);
		if($_POST['id_u']){
			$id_u = $_POST['id_u'];
		}else{		
			$id_u = \Yii::$app->user->identity->id;
		}
		$last_indicat  = Indicat::find()->where('id_u = :id_u', [':id_u'  => $id_u])->andWhere(['like', 'created_at', $y_m])->one();
		$pred_indicat  = Indicat::find()->where(['id_u' => $id_u])->orderBy(['id'=>SORT_DESC])->one();
		if($last_indicat) {			
			$model = $last_indicat;
			$model->created_at = $date_now;
		}else{
			$model = new Indicat();
			$model->created_at = $date_now;
			$model->id_u = $id_u;
		}
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	$t = Logs::addLog('indicat','create','','','','?id='.$model->id); // $controller,$action,$id,$info,$dop,$note
			Indicat::sendMail($id_u, $model);
			if($_POST['id_u']){
				return $this->redirect(['view', 'id' => $model->id]);	
			}else{
				return $this->redirect(['index','tnx' => 1]);
			}
        }
		
        return $this->render('create', [
            'model' => $model, 'last_indicat' => $pred_indicat
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$t = Logs::addLog('indicat','update',$model->id,'','update','?id='.$model->id); // $controller,$action,$id,$info,$dop,$note
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
        if (($model = Indicat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
