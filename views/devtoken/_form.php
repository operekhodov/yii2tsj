<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Devtoken */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devtoken-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
