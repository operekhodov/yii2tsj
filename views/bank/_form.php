<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Bank;

/* @var $this yii\web\View */
/* @var $model app\models\Bank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'client_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'secret_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sandbox')->dropDownList(Bank::getStatusesArray()) ?>   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
