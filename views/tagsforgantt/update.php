<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tagsforgantt */

$this->title = Yii::t('app', 'Редактировать тег: {value}', [
    'value' => $model->value,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Все теги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->value, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="tagsforgantt-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
