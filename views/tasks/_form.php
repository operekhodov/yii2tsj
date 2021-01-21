<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Mkd;
use app\models\Options;
use app\models\Tasks;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use kartik\select2\Select2;
use kartik\file\FileInput;
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
    <?php $form = ActiveForm::begin(); 
    	$model1 = new Options();
    ?>			
<div class="box box-primary">
  <div class="box-header with-border">
	<h3 class="box-title"><?=$this->title ?></h3>
  </div>

  <div class="box-body">

			
<div class="tasks-form"> 

	<?= $form->field($model, 'tema')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'info')->textarea(['rows' => '6']) ?>
    
    <?= $form->field($model, 'type')->dropDownList(Tasks::getTypesArray()); ?>

<?php

    if (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL) ) {
	if(\Yii::$app->user->identity->role == 'root') {
		echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkd());
	}else{
    	echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkdThisArea(\Yii::$app->user->identity->id_org));
	}
    
    echo $form->field($model, 'assignedto')->dropDownList(User::getWorkUser(\Yii::$app->user->identity->id_a));

    echo $form->field($model, 'floor')->textInput(['maxlength' => true]);
    
    echo $form->field($model, 'porch')->textInput(['maxlength' => true]);
    
	$data = Options::getAllTags();
	
	echo $form->field($model, 'tags')->widget(Select2::classname(), [
	    'data' => $data,
	    'options' => ['placeholder' => 'Теги ...', 'multiple' => true],
	    'pluginOptions' => [
	        'tags' => true,
	        'tokenSeparators' => [',', ' '],
	        'maximumInputLength' => 10
	    ],
	])->label('Теги задачи');
    }
?>

<?
$img = json_decode($model->imagebd);
if($img){
$img[0] = substr_replace($img[0], '/u',0 ,1); }
echo $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
	'options' => ['multiple' => true],
    'pluginOptions' => [
       'initialPreview'=> ($img[0]) ?
            Html::img($img[0], ['class'=>'file-preview-image']) :
            ''
    ]

]);
?>
</div>

  </div>
  <div class="box-footer">
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>  
  </div>
</div>
    <?php ActiveForm::end(); ?>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>