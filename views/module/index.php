<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'SIDE_ALL_MODULE');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    $('H1').text('');
	",
    \yii\web\View::POS_END,
    'del_module_index_title'
);
?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-6">

			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title"><?= $this->title?></h3>
			    <div class="box-tools pull-right">
					<?= Html::a(Yii::t('app', 'ADD_MODULE'), ['create'], ['class' => 'btn btn-success']) ?>
			    </div>
			  </div>
			  <div class="box-body">
				<div class="module-index">
				    <?= GridView::widget([
				        'dataProvider' => $dataProvider,
				        'filterModel' => $searchModel,
				        'columns' => [
				            ['class' => 'yii\grid\SerialColumn'],
				
				            //'id',
				            'name',
				
				            ['class' => 'yii\grid\ActionColumn'],
				        ],
				    ]); ?>
				</div>
			  </div>
			</div>
		</div>
		<div class="col-md-5">
		</div>
	</div>
</div>