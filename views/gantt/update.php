<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Gantt */

$this->title = Yii::t('app', 'Изменить проект: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'План работ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="gantt-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
