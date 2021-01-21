<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Options */

$this->title = $model->value;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'FIELD_USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->value, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="options-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
