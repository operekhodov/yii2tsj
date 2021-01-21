<?php

namespace app\controllers;

use Yii;
use app\models\Gantt;
use app\models\GanttSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class GanttController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionKanban()
    {
    	if(Yii::$app->request->post()) {
    		if(Yii::$app->user->identity->role !='user') {
	    		$arr_new  = explode(',',Yii::$app->request->post( 'st_new'  ));
	    		$arr_work = explode(',',Yii::$app->request->post( 'st_work' ));
	    		$arr_test = explode(',',Yii::$app->request->post( 'st_test' ));
	    		$arr_done = explode(',',Yii::$app->request->post( 'st_done' ));
	    		foreach($arr_new as $id){
	    			$model = Gantt::findById($id);
	    			if($model) {$model->status = 0;$model->save();}
	    		}
	    		foreach($arr_work as $id){
	    			$model = Gantt::findById($id);
	    			if($model) {$model->status = 1;$model->save();}
	    		}
	    		foreach($arr_test as $id){
	    			$model = Gantt::findById($id);
	    			if($model) {$model->status = 2;$model->save();}
	    		}
	    		foreach($arr_done as $id){
	    			$model = Gantt::findById($id);
	    			if($model) {$model->status = 3;$model->save();}
	    		}
    		}
    		
			if (Yii::$app->request->isPjax) {
                return $this->renderAjax('kanban');
            }	    		
    	}
    	
        return $this->render('kanban');
    }    

	public function actionUpdate_event($id) {
		$model = $this->findModel($id);
		if($_POST){
			$model->tags = json_encode(Yii::$app->request->post( 'Gantt' )['tags']);
		}		
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['kanban', 'id_mkd' => $model->id_a]);
            }
        }		

		return $this->renderAjax('add_event', [
            'model' => $model, 'id_mkd' => $id_mkd
        ]);
	}

	public function actionAdd_event($id_mkd) {
		
		$model = new Gantt();
		$model->id_u = Yii::$app->user->identity->id;
		if($_POST){
			$model->tags = json_encode(Yii::$app->request->post( 'Gantt' )['tags']);
		}
        if ($model->load(Yii::$app->request->post())) {
        	$model->id_a = $id_mkd;
            if ($model->save()) {
                return $this->redirect(['kanban', 'id_mkd' => $id_mkd]);
            }
        }		

		return $this->renderAjax('add_event', [
            'model' => $model, 'id_mkd' => $id_mkd
        ]);
	}

    public function actionGantt()
    {
    	return $this->render('gantt');
    } /*   
    public function actionAdd()
    {
	    $model = new Gantt();
	
	    if ($model->load(Yii::$app->request->post())) {
	        if ($model->save()) {             
	            if (Yii::$app->request->isAjax) {
	                // JSON response is expected in case of successful save
	                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	                return ['success' => true];
	            }
	            return $this->redirect(['view', 'id' => $model->id]);             
	        }
	    }
	
	    if (Yii::$app->request->isAjax) {
	        return $this->renderAjax('add', [
	            'model' => $model,
	        ]);
	    } else {
	        return $this->render('add', [
	            'model' => $model,
	        ]);
	    }   	
    }          
    public function actionIndex()
    {
        $searchModel = new GanttSearch();
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
        $model = new Gantt();

		$model->id_a = 2;
		
        if ($model->load(Yii::$app->request->post()) ){
        	$model->tags = json_encode(Yii::$app->request->post( 'Gantt' )['tags'],JSON_HEX_QUOT);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
*/
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Gantt::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
