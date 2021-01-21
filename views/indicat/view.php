<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Indicat */

$this->title = Yii::t('app', 'indicat_Title');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'indicat_all'), 'url' => ['index']];
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
    <h3 class="box-title"><?= $this->title ?></h3>
    <div class="box-tools pull-right">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'indicat_del'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
  </div>
  <div class="box-body">
  	
<div class="indicat-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        [
            'attribute'=>'id_u',
            'value'=>$model->createdby0->lname.' '.$model->createdby0->fname.' '.$model->createdby0->fio,
        ],
        [
            'attribute'=>'',
            'value'=>'['.$model->createdby0->locality.', '.$model->createdby0->street.', '.$model->createdby0->num_house.', '.$model->createdby0->nroom.']['.$model->createdby0->nid.']',
        ],        
            'created_at',
            'gvs',
            'gvs1',
            'hvs',
            'hvs1',
            'elday',
            'elnight',
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