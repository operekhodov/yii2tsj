<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Gantt;
use app\models\Tagsforgantt;
use dosamigos\datepicker\DatePicker;
use yii\widgets\Pjax;
use kartik\select2\Select2;

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?=$this->title ?></h3>
  </div>
<?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin(); ?>  
  <div class="box-body">
    <?= $form->field($model, 'status')->dropDownList(Gantt::getStatusesArray()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'start')->widget(
				    DatePicker::className(), [
				        // inline too, not bad
				         'inline' => true, 
				         // modify template for custom rendering
				        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				        'clientOptions' => [
				            'autoclose' => true,
				            'format' => 'yyyy,mm,dd'
				        ]
				]);?>			
			</div>
			<div class="col-md-6">
				<?= $form->field($model, 'end')->widget(
				    DatePicker::className(), [
				        // inline too, not bad
				         'inline' => true, 
				         // modify template for custom rendering
				        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				        'clientOptions' => [
				            'autoclose' => true,
				            'format' => 'yyyy,mm,dd'
				        ]
				]);?>			
			</div>
		</div>
	</div>

<?php
	$data = Tagsforgantt::getAllTags();
	echo $form->field($model, 'tags')->widget(Select2::classname(), [
	    'data' => $data,
	    'options' => ['placeholder' => 'Теги ...', 'multiple' => true],
	    'pluginOptions' => [
	        'tags' => true,
	        'tokenSeparators' => [',', ' '],
	        'maximumInputLength' => 10
	    ],
	])->label('Теги');

?>

    <?= $form->field($model, 'parent')->dropDownList(Gantt::getWorkGantt()) ?>
    <?= $form->field($model, 'about')->textInput() ?>
  </div>
  <div class="box-footer">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
  </div>
    <?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>  
</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>