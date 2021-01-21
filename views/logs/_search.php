<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_a') ?>

    <?= $form->field($model, 'id_u') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'uagent') ?>

    <?php // echo $form->field($model, 'action') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'dop') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
