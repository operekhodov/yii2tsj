<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\grid\LinkColumn;
use yii\widgets\Pjax;

$this->title = 'Камеры видеонаблюдения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?= $this->title ?></h3>
		    <div class="box-tools pull-right">
				<?= Html::a('Добавить камеру', ['create'], ['class' => 'btn btn-success']) ?>
		    </div>

		  </div>

		  <div class="box-body">

<div class="options-index">
	<?php Pjax::begin([]) ?>
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end() ?> 
    
</div>

		  </div>
		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>