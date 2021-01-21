<?php

namespace app\controllers;

use Yii;
use app\models\Indicats;
use app\models\IndicatsSearch;
use app\models\Numerators;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class IndicatsController extends Controller
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
        $searchModel = new IndicatsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'UsrNums' => Numerators::getUsrNums(\Yii::$app->user->identity->id),
        ]);
    }
    public function actionAdd_indi()
    {
		$model = new Indicats();
        if ($model->load(Yii::$app->request->post())) {
        	$model->id_u		= \Yii::$app->user->identity->id;
        	$model->created_at	= time();
        	$model->indinow		= json_encode($model->massiv);// explode(',',Yii::$app->request->post( 'st_new'  ));
        	$model->save();
			return $this->redirect(['index']);
        } 

		return $this->renderAjax('add_indi', [
            'model' => $model
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
        $model = new Indicats();

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
        if (($model = Indicats::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
