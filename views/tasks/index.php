<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Tasks;
use app\models\Options;
use app\models\User;
use app\models\Logs;
use app\models\Mkd;
use yii\grid\ActionColumn;
use app\widgets\grid\LinkColumn;
use app\widgets\grid\SetColumn;
use app\widgets\grid\RoleColumn;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$t = Logs::addLog('$controller','$action','$id','','',''); // $controller,$action,$id,$info,$dop,$note

$this->title = Yii::t('app', 'NAV_TASKS');
$this->params['breadcrumbs'][] = $this->title;
?>
 <?php 
$css= <<< CSS
.date_column{
    width: 20%;
    text-align:center;
}
.datepicker{
	z-index:9999!important;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>
    <div class="box-tools pull-right">
        <?= Html::a(Yii::t('app', 'BT_ADD_TASKS'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

  </div>

  <div class="box-body">
<div class="tasks-index">

	<?
    if (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL) ) {	
		$gridColumns = [
			'num',
			'id',
			'tema',
			'createddate:datetime',
			'finishdate:datetime',
	        [
	            'attribute' => 'status',
	            'format' => 'raw',
	            'value' => 'StatusName',
	        ],		
			'info',
	        [ 
	            'attribute'=>'assignedto',
	            'value'=>'UserUsername',
	        ],
			'notes',
	        [ 
	            'attribute'=>'createdby',
	            'value'=>'Createdby0Username',
	        ],
			[
				'attribute'=>'id_a',
				'value'=>'MkdAddress',
			],	        
			'floor',
			'porch',
			'enddt',
		 ];
		echo ExportMenu::widget([
		'dataProvider' => $dataProvider,
		'columns' => $gridColumns,
		'exportConfig' => [
			ExportMenu::FORMAT_HTML => false,
		],
		'container' => ['class'=>'pull-right', 'role'=>'group'],
		'tableOptions' => [
		'class' => 'table table-striped table-responsive'
		],	
		]);
    }
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'num',
	        [
	            'filter' => DatePicker::widget([
	                'model' => $searchModel,
	                'attribute' => 'date_from',
	                'attribute2' => 'date_to',
	                'type' => DatePicker::TYPE_RANGE,
	                'separator' => '-',
	                'pluginOptions' => ['format' => 'yyyy-mm-dd']
	            ]),
	            'attribute' => 'createddate',
	            'format' => 'datetime',
			    'contentOptions' => ['class' => 'date_column'],
			    'headerOptions' => ['class' => 'date_column']  	            
	        ],
            [
                'filter' => Tasks::getStatusesArray(),
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var Tasks $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case Tasks::STATUS_NEW:
                            $class = 'success';
                            break;
                        case Tasks::STATUS_DONE:
                            $class = 'warning';
                            break;
                        case Tasks::STATUS_INWORK:
                            $class = 'primary';
                            break;
                        case Tasks::STATUS_MODER:
                            $class = 'default';
                            break;                            
                        default:
                            $class = 'default';
                    };
                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);

                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],
            [
            	'class' => LinkColumn::className(),
            	'attribute'=>'tema',
            ],            
			'info',
			[
				'filter' => Mkd::getAllMkdThisArea(\Yii::$app->user->identity->id_org),
				'attribute'=>'id_a',
				'value'=>'MkdAddress',
				'visible'=> (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)),
			],
/*
            [   'filter' => User::getWorkUser(\Yii::$app->user->identity->id_a),
                'attribute'=>'assignedto',
                'value'=>'UserUsername',
            ],
            //'notes',
            [   'filter' => User::getAllUsersNameThisArea(\Yii::$app->user->identity->id_a),
                'attribute'=>'createdby',
                'value'=>'Createdby0Username',
                'visible'=> (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)),
            ],
*/
            [
            	'class' => 'yii\grid\ActionColumn',
            	'visible'=> (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)),
            ],
        ],
    ]); ?>
</div>
  </div>
</div>
		</div>
		<div class="col-md-2">
		</div>
	</div>
</div>

