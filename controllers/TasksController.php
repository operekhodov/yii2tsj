<?php

namespace app\controllers;

use Yii;
use app\models\Tasks;
use app\models\Message;
use app\models\Options;
use app\models\TasksSearch;
use app\models\User;
use app\models\Logs;
use app\models\Mkd;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

class TasksController extends Controller
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
                        'actions' => ['index','view','create'],
                        'roles' => [AdminRbac::PERMISSION_USER_PANEL],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','delete','chat'],
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
		if (parent::beforeAction($action)) {
			if($action->id == 'delete' || $action->id == 'update' ||  $action->id == 'view' ){
				if ( \Yii::$app->user->identity->role != 'root' && !in_array($this->findModel($_GET['id'])->id_a,Mkd::getAllMkdIDThisArea(\Yii::$app->user->identity->id_org)) ){
					return $this->redirect(['site/block']);
				}
			}
			if(\Yii::$app->user->identity->status != 1 ){
				return $this->redirect(['profile/index','wait'=>1]);
			}else{
				$access_r = json_decode(\Yii::$app->user->identity->access_r, True);
				if(array_key_exists($action->controller->id,$access_r) && in_array($action->id,$access_r[$action->controller->id])){
					$controller = Yii::$app->controller->id;
					$action 	= $action->id;
					$id			= $_GET['id'] ? $_GET['id'] : '';
					$t = Logs::addLog($controller,$action,$id,'','',''); // $controller,$action,$id,$info,$dop,$note
					
					return true;
				}else{
					return $this->redirect(['site/block']);
				}
			}
		}
	}
    public function actionIndex()
    {
        $searchModel = new TasksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {	
		$model = $this->findModel($id);
        $currentUserId = Yii::$app->user->identity->getId();
        $messagesQuery = Message::findMessagess($model->createdby);
        $message = new Message([
            'from' => $currentUserId,
            'to' => $model->createdby,
            'dtime' => time()
        ]);

		$img = UploadedFile::getInstances($message, 'imageFiles');
        if($img){
        	$arr_img = array();
        	foreach ($img as $file) {
        		$filename = uniqid().".{$file->extension}";
        		$url_filename = "uploads/".$filename;
                $file->saveAs($url_filename);
                //$this->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            $message->imageFiles = null;
	        $message->img = json_encode($arr_img);
	        $message->text = (Yii::$app->request->post('Message')['text']) ? Yii::$app->request->post('Message')['text']:"~~~~~";
	        $message->save();
	        $message->text = null;

            if (Yii::$app->request->isPjax) {
                return $this->renderAjax('_chat', compact('messagesQuery', 'message','lastQuery'));
            }	        
        }        
        
        if ($message->load(Yii::$app->request->post()) && $message->validate()) {
			
			$img = UploadedFile::getInstances($message, 'imageFiles');
			if($img){
	        	$arr_img = array();
	        	foreach ($img as $file) {
	        		$filename = uniqid().".{$file->extension}";
	        		$url_filename = "uploads/".$filename;
	                $file->saveAs($url_filename);
	                //$this->saveAs($url_filename);
	                array_push($arr_img,$url_filename);
	            }
	            $message->imageFiles = null;
		        $message->img = json_encode($arr_img);
	        	$message->text = (Yii::$app->request->post('Message')['text']) ? Yii::$app->request->post('Message')['text']:"~~~~~";
		        $message->save();
	        }
				
            $message->save();
            $message = new Message([
                'from' => $currentUserId,
            ]);
				
            if (Yii::$app->request->isPjax) {
                return $this->renderAjax('_chat', compact('messagesQuery', 'message','lastQuery'));
            }
        }
        if (Yii::$app->request->isPjax) {
            return $this->renderAjax('_chat', compact('messagesQuery', 'message','lastQuery'));
        }
        return $this->render('view', compact('messagesQuery', 'message','model'));    	
    	
    }
    public function actionTask($id)
    {	
    	$task = $this->findModel($id);
    	var_dump($task->createdby);   
    	
        return $this->render('view_and_chat', ['model' => $this->findModel($id),]);
    }
	public function actionChat($id)
    {
		$form = null;
        return $this->render('view_and_chat', ['form' => $form]);
    }
    public function actionCreate()
    {
        $model = new Tasks();
		$model->createddate = time();
        $model->createdby = \Yii::$app->user->identity->id;
        if (\Yii::$app->user->identity->role == 'user') {
        	$model->id_a = \Yii::$app->user->identity->id_a;
        }else{
        	$model->id_a = Yii::$app->request->post('Tasks')['id_mkd'];
        }
        
        if ($model->load(Yii::$app->request->post()) ){
        	$model->tags = json_encode(Yii::$app->request->post( 'Tasks' )['tags'],JSON_HEX_QUOT);
        	$model->imageFiles  = UploadedFile::getInstances($model, 'imageFiles');
			
        	$arr_img = array();
        	foreach ($model->imageFiles as $file) {
        		$filename = uniqid().".{$file->extension}";
        		$url_filename = "uploads/".$filename;
                $file->saveAs($url_filename);
                array_push($arr_img,$url_filename);
            }
            $model->imageFiles = null;
	        $model->imagebd = json_encode($arr_img);
	        $model->num = Tasks::NumberT($model->type);
        	$model->save();
        	
	        $t = Logs::addLog('tasks','create','','','create','?id='.$model->id); // $controller,$action,$id,$info,$dop,$note
        	
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	

        	
            return $this->redirect(['view', 'id' => $model->id]);

			$token = 'f6PQGOceUTU:APA91bEQ_mKkeg8C5h13KltU-fDcEGML-YrfAXRwu-0fcdXPIFbu2AVyA-HT3Cx5dvbbpSkZ9Yl89GXsonkMlHtWi20UcevnlJ3GKWI04_qgPIr9UR2HDYgSaS9Tr4xORd5zJn2769C-';
			$fcm = Yii::$app->fcm;
			$result = $fcm
			    ->createRequest()
			    ->setTarget(\aksafan\fcm\source\builders\legacyApi\MessageOptionsBuilder::TOKEN, $token)
			    ->setData(['a' => '1', 'b' => '2'])
			    ->setNotification('Send push-notification to a single token (device)', 'Test description')
			    ->send();
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
      
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

    		if ($model->load(Yii::$app->request->post())){
	            if ($model->status == 1){
	            	$model->finishdate = time();
	            }elseif($model->status == 0){
	            	$model->finishdate = null;
	            }
    		}    
		    if ($model->load(Yii::$app->request->post()) && $model->save()) {

		    	$model->tags = json_encode(Yii::$app->request->post( 'Tasks' )['tags']);
		    	$model->save();
		    	$t = Logs::addLog('tasks','update',$model->id,$model->status,'update','?id='.$model->id); // $controller,$action,$id,$info,$dop,$note
		    	
				$token = 'f6PQGOceUTU:APA91bEQ_mKkeg8C5h13KltU-fDcEGML-YrfAXRwu-0fcdXPIFbu2AVyA-HT3Cx5dvbbpSkZ9Yl89GXsonkMlHtWi20UcevnlJ3GKWI04_qgPIr9UR2HDYgSaS9Tr4xORd5zJn2769C-';
				    $fcm = Yii::$app->fcm;
				$result = $fcm
				    ->createRequest()
				    ->setTarget(\aksafan\fcm\source\builders\legacyApi\MessageOptionsBuilder::TOKEN, $token)
				    ->setData(['a' => '1', 'b' => '2'])
				    ->setNotification('Send push-notification to a single token (device)', 'Test description')
				    ->send();            

	            return $this->redirect(['view', 'id' => $model->id]);
	            
	        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        
		$access_r = json_decode(\Yii::$app->user->identity->access_r, True);
		if(in_array('delete',$access_r['tasks'])){
			$this->findModel($id)->delete();
        	return $this->redirect(['index']);
		}else{
			return $this->redirect(['site/block']);
		}        	
    }
    protected function findModel($id)
    {
        if (($model = Tasks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
