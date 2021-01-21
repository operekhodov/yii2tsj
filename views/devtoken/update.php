<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Devtoken */

$this->title = 'Update Devtoken: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Devtokens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="devtoken-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
