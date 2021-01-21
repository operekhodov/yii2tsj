<?php

use app\models\Topic;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;
use yii\bootstrap4\Modal;
?>

<div class="topic-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'topic')->textInput(['maxlength' => true, 'list' => 'topic_list']) ?>
	<?
	echo '<datalist id="topic_list">';
	echo Html::renderSelectOptions(null,Topic::getTopicNames());    
	echo '</datalist>';
	?>    

    <?= $form->field($model, 'quest')->textarea(['rows' => '6']) ?>

    <?= $form->field($model, 'type')->dropDownList(Topic::getTypesArray()); ?>

	<?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>    
    
    <?php Pjax::begin(); ?>
    
    <?= Html::a("Добавить поле ответа", ["topic/adding?key=$key"], ['class' => 'btn btn-primary']) ?>

<br><br>
	<div class="col-sm-12" style="max-height: 400px;overflow:scroll;">
	<? for ($i=0; $i < $key ; $i++) { ?>
		<div class="col-sm-8">
	    	<?= $form->field($model, "massiv[$i]")->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-4">
		</div>
	<? } ?>
	</div>

    <?php Pjax::end(); ?>

<?
	$types_doc  = array('doc','odt','docx','xls','xlsx');
	$types_text = array('txt','csv');
	$types_pdf  = 'pdf';
	$initialPreview = array();
	$initialPreviewConfig = array();

    $path = json_decode($model->imageFiles);
    $path = substr_replace($path[0], '/u',0 ,1); 
    $type = strrpos($path, '.') + 1;
    $type = mb_strcut($path, $type);    

    if (in_array($type, $types_text)) {
        $string = file_get_contents(Url::base(true).$path);
        array_push($initialPreview, $string);
        array_push($initialPreviewConfig, array("type"=> "text", "caption"=> "$title","filename"=>"$path"));
    }elseif (in_array($type, $types_doc)) {
		array_push($initialPreview, Url::base(true).$path);
        array_push($initialPreviewConfig, array("type"=> "office", "caption"=> "$title","filename"=>"$path"));        
    }elseif ($type == 'pdf') {
        array_push($initialPreview, $path);
        array_push($initialPreviewConfig, array("type"=> "pdf", "caption"=> "$title","filename"=>"$path"));  
    }else{
        array_push($initialPreview, $path);
        array_push($initialPreviewConfig, array("type"=> "image", "caption"=> "$title","filename"=>"$path"));  
    }

	echo ($model->imageFiles) ? 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), 
			
		    [
		        'name' => 'file',
		        'options' => ['multiple' => true],
		        'pluginOptions' => [
		        	'initialPreviewDownloadUrl'=> Url::base(true).'{filename}',
		            'initialPreview' => $initialPreview,
		            'initialPreviewAsData' => true,
				    'initialPreviewConfig' => $initialPreviewConfig
		        ]
		    ]
	
	) : 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
	 		'options' => ['multiple' => true],
	 	]);
	?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>	

</div>
