<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Topicans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topicans-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_u')->textInput() ?>

    <?= $form->field($model, 'id_q')->textInput() ?>

    <?= $form->field($model, 'answer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
