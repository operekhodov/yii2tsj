<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Area;

$this->title = Yii::t('app', 'NAV_AREA');
$this->params['breadcrumbs'][] = $this->title;
$css= <<< CSS
tr {
	text-align: center;
}
	.row_link:hover {
	    background-color: #bfeafb!important;
	    cursor: pointer;
	}

CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-9">

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>
    <div class="box-tools pull-right">
		<? if(\Yii::$app->user->can('moder')){ ?>
			<?= Html::a(Yii::t('app', 'B_ADD_AREA'), ['create'], ['class' => 'btn btn-success']) ?>
		<? } ?>
    </div>
  </div>
  <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'rowOptions'   => function ($model, $index, $widget, $grid) {
	        return [
	            'id' => $model['id'], 
	            'onclick' => 'location.href="'
	                . Yii::$app->urlManager->createUrl('area/view') 
	                . '?id="+(this.id);',
	            'class' => 'row_link ',
	        ];
	    },          
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
	            //'id',
	            'title',
	            'info',
	            'inn',
	            'address',
				'about',
            [
                'filter' => Area::getTypesArray(),
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    $value = $model->{$column->attribute};
                    return Area::TypeView($value);
                }
            ],
	            //'createddate',
	            //'status',
	            //'notes',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
  </div>
</div>

		</div>
		<div class="col-md-2">
		</div>
	</div>
</div>


