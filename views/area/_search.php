<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'createddate') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'info') ?>

    <?= $form->field($model, 'notes') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'inn') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'about') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
