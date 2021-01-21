<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?
$types_doc  = array('doc','odt','docx','xls','xlsx');
$types_text = array('txt','csv');
$types_pdf  = 'pdf';
$initialPreview = array();
$initialPreviewConfig = array();

    $path = json_decode($model->path);
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

	echo ($model->path) ? 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), 
	
		    [
		        'name' => 'file',
		        'pluginOptions' => [
		        	'initialPreviewDownloadUrl'=> Url::base(true).'{filename}',
		            'initialPreview' => $initialPreview,
		            'initialPreviewAsData' => true,
				    'initialPreviewConfig' => $initialPreviewConfig
		        ]
		    ]
	
	) : 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), []);
	?>

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