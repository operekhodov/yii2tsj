<?
use yii\helpers\Html;
use app\models\Numerators;
use kartik\form\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<? 
foreach(Numerators::getUsrNums(Yii::$app->getUser()->identity->id) as $num => $note){ ?>
	<?= $form->field($model, "massiv[$num]")->textInput(['maxlength' => true])->label($note) ?> 
<? } ?>
<?= Html::submitButton(Yii::t('app', 'Передать'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>	