<?php

namespace app\controllers;

use Yii;
use app\models\Logs;
use app\models\LogsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Logsetting;
use app\models\Options;

class LogsController extends Controller
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
    public function actionIndex()
    {
        $searchModel = new LogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	public function actionSettings() {
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
		 	$options = Options::getLogsetting(Yii::$app->getUser()->identity->id_org,'o');
		 	$options->value = json_encode($answer);
		 	$options->save();
		}
		return $this->render('setting');
	}
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionCreate()
    {
        $model = new Logs();

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
        if (($model = Logs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
