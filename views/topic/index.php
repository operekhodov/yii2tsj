<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Topic;
use app\models\Topicans;
use app\widgets\grid\LinkColumn;
use app\models\Mkd;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

$this->title = Yii::t('app', 'Опросы');
$this->params['breadcrumbs'][] = $this->title;

$css= <<< CSS
	.row_link:hover {
	    background-color: #bfeafb!important;
	    cursor: pointer;
	}
	.wait {
		background-color: #fffd9a !important;
	}
	.done {
		background-color: #baf499!important;
	}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?=$this->title;?></h3>
		    <div class="box-tools pull-right">
				<?= (Yii::$app->user->identity->status == 1) ? Html::a(Yii::t('app', 'Добавить новый опрос'), ['adding?key=0&type=0'], ['class' => 'btn btn-success']) : '' ?>
		    </div>
		  </div>
		  <div class="box-body">
<div class="topic-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'rowOptions'   => function ($model, $index, $widget, $grid) {
	    	if(\Yii::$app->user->identity->role == 'user' || \Yii::$app->user->identity->role == 'government'){
	    		$class = (Topicans::getSDA($model->id,\Yii::$app->user->identity->id) ) ? 'done' : 'wait';
	    	}
	            return [
	                'id' => $model['id'], 
	                'onclick' => 'location.href="'
	                    . Yii::$app->urlManager->createUrl('topic/view') 
	                    . '?id="+(this.id);',
	                'class' => 'row_link '.$class,
	            ];
	    },        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [	
            	'filter' => Topic::getTopicNames(), 
            	'attribute' => 'topic',
            	'value' => 'topic',
            ],
            [
            	'attribute' => 'quest',
            ],
			[
				'filter' => Mkd::getAllMkdThisArea(\Yii::$app->user->identity->id_org),
				'attribute'=>'id_a',
				'value'=>'MkdAddress',
				'visible'=>(\Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)),
			],
            [
            	'filter'=>Topic::getTypesArrayForSearch(),
            	'attribute' => 'type',
            	'value'=>'TypeName',
            	'format'=>'html',
            ],
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

