<?php

namespace app\controllers;

use Yii;
use app\models\Options;
use app\models\OptionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OptionsController implements the CRUD actions for Options model.
 */
class OptionsController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

	public function actionPattern_ar() {
		if(Yii::$app->request->post()){
			$from = Yii::$app->request->post();
			$name = $from["name"];
			unset($from["name"]);
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
		 	$options = Options::getTemp_access(Yii::$app->getUser()->identity->id_org,$name,'o');
		 	$options->value = json_encode($answer);
		 	$options->save();
		}		
		return $this->render('pattern_ar');
	}

    /**
     * Lists all Options models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OptionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Tags models.
     * @return mixed
     */
    public function actionTagsindex()
    {
        $searchModel = new OptionsSearch();
        $dataProvider = $searchModel->tagssearch(Yii::$app->request->queryParams);

        return $this->render('tags_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Options model.
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
     * Displays a single Options model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTagsview($id)
    {
        return $this->render('tags_view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Creates a new Options model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Options();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Creates a new Tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddtags()
    {
        $model = new Options();
        $model->name = 'tags';
        $model->id_t = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tagsview', 'id' => $model->id]);
        }

        return $this->render('tags_add', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Options model.
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
     * Updates an existing Tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTagsupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tagsview', 'id' => $model->id]);
        }

        return $this->render('tags_update', [
            'model' => $model,
        ]);
    }    

    /**
     * Deletes an existing Options model.
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
     * Deletes an existing Tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTagsdelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['tagsindex']);
    }    

    /**
     * Finds the Options model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Options the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Options::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
