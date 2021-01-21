<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use app\models\Message;
use app\models\MessageSearch;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\Pjax;
use yii\web\UploadedFile;
/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionListusers()
	{
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('listusers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}

    /**
     * Displays a single Message model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionChat($id)
    {
        $currentUserId = Yii::$app->user->identity->getId();
        $messagesQuery = Message::findMessagess($id);
        $lastQuery = Message::findLastmess($currentUserId, $id);
        $message = new Message([
            'from' => $currentUserId,
            'to' => $id,
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
		
        return $this->render('chat', compact('messagesQuery', 'message','lastQuery'));
    }
    
    public function actionVchat($id)
    {
        $currentUserId = Yii::$app->user->identity->getId();
        $messagesQuery = Message::findMessages($currentUserId, $id);
        $message = new Message([
            'from' => $currentUserId,
            'to' => $id,
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
	        $message->text = json_encode($arr_img);
	        $message->save();
	        $message->text = null;

            if (Yii::$app->request->isPjax) {
                return $this->renderAjax('_vchat', compact('messagesQuery', 'message'));
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
		        $message->text .= json_encode($arr_img);
		        $message->save();
	        }

            $message->save();
            $message = new Message([
                'from' => $currentUserId,
            ]);

            if (Yii::$app->request->isPjax) {
                return $this->renderAjax('_vchat', compact('messagesQuery', 'message'));
            }
        }
        if (Yii::$app->request->isPjax) {
            return $this->renderAjax('_vchat', compact('messagesQuery', 'message'));
        }

        return $this->render('vchat', compact('messagesQuery', 'message'));
    }    
    
}
