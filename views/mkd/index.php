<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Area;
use app\widgets\grid\LinkColumn;
use app\rbac\Rbac as AdminRbac;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Список МКД');
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
		<div class="col-md-7">

<div class="mkd-index">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    
    <div class="box-tools pull-right">
    	<? if(\Yii::$app->user->can('moder')){ ?>
	    <p>
	        <?= Html::a(Yii::t('app', 'Добавить МКД'), ['create'], ['class' => 'btn btn-success']) ?>
	    </p>
	    <? } ?>
	</div>


<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'rowOptions'   => function ($model, $index, $widget, $grid) {
	        return [
	            'id' => $model['id'], 
	            'onclick' => 'location.href="'
	                . Yii::$app->urlManager->createUrl('mkd/view') 
	                . '?id="+(this.id);',
	            'class' => 'row_link ',
	        ];
	    },        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        	//'id',
            [ 
				'filter' => Area::getActiveArea(),
                'attribute'=>'id_a',
                'value'=>'AreaInfo',
                'format'=>'html'
            ],
            [
            	'attribute'=>'city',
            ],
            [
            	'attribute'=>'street',
            ],
            [
            	'attribute'=>'number_house',
            ],
            //'count_porch',
            //'count_apartment',
            //'note',
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>


		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>