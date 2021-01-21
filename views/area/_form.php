<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Area;
use app\models\Module;
use kartik\select2\Select2;
use kartik\file\FileInput;
use yii\widgets\MaskedInput;

?>
<?php 
$css= <<< CSS
.file-preview-image{
    width: 100%;    
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>​
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>

  </div>
  <div class="box-body">

			<div class="area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Area::getStatusesArray()); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

	<?
	$img = json_decode($model->logo);
	if($img){
	$img[0] = substr_replace($img[0], '/u',0 ,1); }
	echo $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
	
	    'pluginOptions' => [
	       'initialPreview'=> ($img[0]) ?
	            Html::img($img[0], ['class'=>'file-preview-image']) :
	            ''
	    ]
	
	]);
	?>	

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'about')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Area::getTypesArray()); ?>

	<?= $form->field($model, 'email')->widget(MaskedInput::className(), [
	    'clientOptions' => [
	        'alias' =>  'email'
	    ],
	]) ?>

	<?= $form->field($model, 'useemail')->checkbox([ 'value' => '1'])->label('');?>
	
	<?
	$data = Module::getAllModule();
	$model->module = json_decode($model->module);
	echo $form->field($model, 'module')->widget(Select2::classname(), [
	    'data' => $data,
	    'options' => ['placeholder' => 'Модули ...', 'multiple' => true],
	    'pluginOptions' => [
	        'tags' => true,
	        'tokenSeparators' => [',', ' '],
	        'maximumInputLength' => 10,
	        'disabled' => !\Yii::$app->user->can('moder')
	    ],
	])->label('Модули');
	?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

  </div>
</div>

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>