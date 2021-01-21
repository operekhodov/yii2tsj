<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = Yii::t('app', 'Редактировать: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIDE_ALL_MODULE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="module-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
