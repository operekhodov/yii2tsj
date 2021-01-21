<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?= $this->title ?></h3>
		  </div>
			<?php $form = ActiveForm::begin(); ?>
		  <div class="box-body">
		    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
		  </div>
		  <div class="box-footer">
		    <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
		  </div>
			<?php ActiveForm::end(); ?>
		</div>

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>