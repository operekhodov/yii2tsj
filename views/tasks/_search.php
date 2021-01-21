<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TasksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tasks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'createddate') ?>

    <?= $form->field($model, 'finishdate') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'assignedto') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <?php // echo $form->field($model, 'createdby') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
