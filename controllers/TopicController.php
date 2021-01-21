<?php

namespace app\controllers;

use Yii;
use app\models\Mkd;
use app\models\User;
use app\models\Area;
use app\models\Module;
use app\models\Topic;
use app\models\Topicans;
use app\models\TopicSearch;
use app\models\TopicansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use app\models\Logs;
use kartik\mpdf\Pdf;

class TopicController extends Controller
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
                        'actions' => ['answer','view','index','adding'],
                        'roles' => [AdminRbac::PERMISSION_USER_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['answer','update','create','mpdfexport'],
                        'roles' => [AdminRbac::PERMISSION_GOVERNMENT_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete','chat','delfoto'],
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
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) { //if ( Mkd::findByID($this->findModel($_GET['id'])->id_a)->id_a
			if($action->id == 'delete' || $action->id == 'update' ||  $action->id == 'view' ){
				if( Yii::$app->user->identity->role == 'user' || Yii::$app->user->identity->role == 'government' ){
					if ( $this->findModel($_GET['id'])->id_a  != \Yii::$app->user->identity->id_a && \Yii::$app->user->identity->role != 'root'){
						return $this->redirect(['site/block']);
					}
				}else{
					if ( Mkd::findByID($this->findModel($_GET['id'])->id_a)->id_a  != \Yii::$app->user->identity->id_org && \Yii::$app->user->identity->role != 'root'){
						return $this->redirect(['site/block']);
					}					
				}
			}

			$controller = Yii::$app->controller->id;
			$action 	= $action->id;
			$id			= $_GET['id'] ? $_GET['id'] : '';
			
			$t = Logs::addLog($controller,$action,$id,'','',''); // $controller,$action,$id,$info,$dop,$note
			
			return true;
		}
	}
	public function actionAnswer($id)
	{
		$model = $this->findModel($id);
        return $this->render('answer', [
        	'model' => $model,
        ]);
	}
	public function actionAdding($key,$type)
	{
		$key = $key + 1;
		$model = new Topic();
			
        if ($model->load(Yii::$app->request->post()) ){
			switch (Yii::$app->request->post( 'Topic' )['type']) {
				case 2:
					$model->answermas = $model->trating;
					break;
				case 3:
					$model->answermas = '["' . str_replace(',','","',$_POST["tdate"]) . '"]' ; //["Красный","Синий","зеленый"]
					break;
				case 4:
					$model->answermas = json_encode($model->ttime,JSON_UNESCAPED_UNICODE);
					break;
				default:
					$new_array = array_diff(Yii::$app->request->post( 'Topic' )['massiv'], array(''));
					$model->answermas = json_encode($new_array,JSON_UNESCAPED_UNICODE);
					break;
			}
			
			$model->avtor		= Yii::$app->user->identity->lname.' '.Yii::$app->user->identity->fname.' '.Yii::$app->user->identity->fio;
			$model->starttime = strtotime($model->sttime);
			$model->id_a = Yii::$app->request->post('Topic')['id_mkd'];       	
			$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
			        	
			$arr_img = array(); $k=0;
			foreach ($model->imageFiles as $file) {
				$filename = uniqid().".{$file->extension}";
				$url_filename = "uploads/Bap_$k-".$filename; $k++;
                $file->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            if($model->answermas == '[]'){
            	$model->answermas = '[';
            	for($i=0;$i<$k;$i++){
            		$model->answermas .= '"Вариант - '.$i.'",';
            	}$model->answermas = substr_replace($model->answermas,']',-1);
            }
            $model->imageFiles = null;
			$model->imagesmas = json_encode($arr_img,JSON_UNESCAPED_UNICODE);
			$model->save();
        }		
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }		
			
		return $this->render('adding', [
			'model'=>$model,'key' => $key, 'type' => $type
		]);
	}
	public function actionDelfoto($file)
	{
		var_dump($file);
	}
    public function actionIndex()
    {
        $searchModel = new TopicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
		if($_POST){
			$model = new Topicans();
			$model->created_at = time();
			$model->id_u = \Yii::$app->user->identity->id;
			$model->id_q = $_POST['id_q'];
			$model->answer = ($_POST['type'] == 0 || $_POST['type'] == 2) ? $_POST['answer'] : json_encode($_POST['answer']);
			$model->note = ($_POST['type'] == 0  || $_POST['type'] == 2) ? $_POST['answer'] : json_encode($_POST['answer']);
			$model->save();
		}    	
        $searchModel = new TopicansSearch();
        $dataProvider = $searchModel->donesearch(Yii::$app->request->queryParams,$id);
        
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $this->findModel($id),
        ]);
    }
	public function actionMpdfexport() 
	{
        $searchModel = new TopicansSearch();
        $dataProvider = $searchModel->donesearch(Yii::$app->request->queryParams,$_POST['id']);
        
        $this->layout = 'pdf';
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        $fname_fheader = '['.$_POST['topic'].'],['.Mkd::getAddressMkdByID($_POST['id_a']).']';
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => Topic::getHtml($_POST['id'],$_POST['img1'],$_POST['img2'],$searchModel,$dataProvider),
	        'methods' => [
	            'SetTitle' => $fname_fheader,
	            'SetHeader' => [$fname_fheader],
	        ]            
        ]);
        return $pdf->render();
    }    
    public function actionUpdate($id,$key,$df,$type)
    {
        $model = $this->findModel($id);
        
        $key = ($key < count(json_decode($model->answermas)) ) ? $key : count(json_decode($model->answermas)) ;
        
        $df = $df + 1;
		
        if ($model->load(Yii::$app->request->post()) ){
        	
			switch (Yii::$app->request->post( 'Topic' )['type']) {
				case 2:
					$model->answermas = $model->trating;
					break;
				case 3:
					$model->answermas = '["' . str_replace(',','","',$_POST["tdate"]) . '"]' ; //["Красный","Синий","зеленый"]
					break;
				case 4:
					$model->answermas = json_encode($model->ttime,JSON_UNESCAPED_UNICODE);
					break;
				default:
					$new_array = array_diff(Yii::$app->request->post( 'Topic' )['massiv'], array(''));
					$model->answermas = json_encode($new_array,JSON_UNESCAPED_UNICODE);
					break;
			}        	
        	
        	
        	//$new_array = array_diff(Yii::$app->request->post( 'Topic' )['massiv'], array(''));
			//$model->answermas = json_encode($new_array,JSON_UNESCAPED_UNICODE);
			$model->id_a = Yii::$app->request->post('Topic')['id_mkd'];         	
			$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
			    
			if($model->imageFiles ){
				$arr_img = array(); $k=0;
				foreach ($model->imageFiles as $file) {
					$filename = uniqid().".{$file->extension}";
					$url_filename = "uploads/Bap_$k-".$filename; $k++;
		            $file->saveAs($url_filename);
		            array_push($arr_img,$url_filename);
		        }
		        $model->imageFiles = null;
				$model->imagesmas = json_encode($arr_img,JSON_UNESCAPED_UNICODE);
			}	
			$model->save();
        }	
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
		
        return $this->render('update', [
            'model' => $model, 'key' => $key, 'df' => $df, 'type' => $type
        ]);
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
