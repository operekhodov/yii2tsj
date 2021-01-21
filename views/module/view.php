<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIDE_ALL_MODULE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
			        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			        <?= Html::a(Yii::t('app', 'BUTTON_DELETE'), ['delete', 'id' => $model->id], [
			            'class' => 'btn btn-danger',
			            'data' => [
			                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
			                'method' => 'post',
			            ],
			        ]) ?>
			    </div>
			  </div>
			
			  <div class="box-body">
			    <?= DetailView::widget([
			        'model' => $model,
			        'attributes' => [
			            'id',
			            'name',
			            'action',
			            'sub_action',
			            'sub_action_ru',
			        ],
			    ]) ?>
			  </div>
			</div>
		</div>
		<div class="col-md-5">
		</div>
	</div>
</div>