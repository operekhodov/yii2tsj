<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Gantt;

/* @var $this yii\web\View */
/* @var $model app\models\Gantt */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'План работ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gantt-view">


    <p>
        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
        [
            'attribute' => 'status',
            'value' => $model->getStatusName(),
        ],
            'name',
            'start',
            'end',
            'parent',
        [
            'attribute' => 'parent',
            'value' => Gantt::findById($model->parent)->name,
        ],            
            'about',
        ],
    ]) ?>

</div>
