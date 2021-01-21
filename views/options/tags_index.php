<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'NAV_TAGS');
$this->params['breadcrumbs'][] = $this->title;
    
$buttons =   ['class' => 'yii\grid\ActionColumn',
	           'buttons'=>[
	                  'view'=>function ($url, $model) {
	                        $customurl=Yii::$app->getUrlManager()->createUrl(['options/tagsview','id'=>$model['id']]); //$model->id для AR
	                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
	                                                ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
	            		},
	                  'update'=>function ($url, $model) {
	                        $customurl=Yii::$app->getUrlManager()->createUrl(['options/tagsupdate','id'=>$model['id']]); //$model->id для AR
	                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
	                                                ['title' => Yii::t('yii', 'Update'), 'data-pjax' => '0']);
	                  },
	                  'delete'=>function ($url, $model) {
	                        $customurl=Yii::$app->getUrlManager()->createUrl(['options/tagsdelete','id'=>$model['id']]); //$model->id для AR
	                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
	                                                ['title' => Yii::t('yii', 'Delete'), 'data-pjax' => '0']);
	                  }
	            ],
	           'template'=>'{view} {update} {delete}'
              ];

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		    <div class="box-tools pull-right">
				<?= Html::a(Yii::t('app', 'ADD_TAGS'), ['addtags'], ['class' => 'btn btn-success']) ?>
		    </div>
		  </div>
		  <div class="box-body">

<div class="options-index">
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'id_u',
            //'id_t',
            //'name',
            'value',

            $buttons,
        ],
    ]); ?>


</div>

		  </div>
		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>