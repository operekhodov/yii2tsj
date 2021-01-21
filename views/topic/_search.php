<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TopicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_a') ?>

    <?= $form->field($model, 'topic') ?>

    <?= $form->field($model, 'quest') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'answermas') ?>

    <?php // echo $form->field($model, 'imagesmas') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
