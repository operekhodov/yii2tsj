<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IndicatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="indicat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_u') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'gvs') ?>

    <?= $form->field($model, 'gvs1') ?>

    <?php // echo $form->field($model, 'hvs') ?>

    <?php // echo $form->field($model, 'hvs1') ?>

    <?php // echo $form->field($model, 'elday') ?>

    <?php // echo $form->field($model, 'elnight') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
