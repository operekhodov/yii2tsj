<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->value;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_TAGS'), 'url' => ['tagsindex']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
		        <?= Html::a(Yii::t('yii', 'Update'), ['tagsupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		        <?= Html::a(Yii::t('yii', 'Delete'), ['tagsdelete', 'id' => $model->id], [
		            'class' => 'btn btn-danger',
		            'data' => [
		                'confirm' => 'Are you sure you want to delete this item?',
		                'method' => 'post',
		            ],
		        ]) ?>
		    </div>

		  </div>

		  <div class="box-body">

<div class="options-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'id_u',
            //'id_t',
            //'name',
            'value',
        ],
    ]) ?>

</div>
		  </div>
		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>