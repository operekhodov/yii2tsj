<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MkdSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mkd-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_a') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'street') ?>

    <?= $form->field($model, 'number_house') ?>

    <?php // echo $form->field($model, 'count_porch') ?>

    <?php // echo $form->field($model, 'count_apartment') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
