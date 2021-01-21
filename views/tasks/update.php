<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = "Карта задачи";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_TASKS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "Карта задачи", 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');

?>
<div class="tasks-update">

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>
