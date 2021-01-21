<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\News;
use kartik\select2\Select2;
use app\models\Mkd;
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

<?
if (\Yii::$app->user->can('spec')) {
		if(\Yii::$app->user->can('moder')){
			$data = Mkd::getAllMkd();
		}else{
			$data = Mkd::getAllMkdThisArea(Yii::$app->user->identity->id_org);
		}
		
		echo $form->field($model, 'mkd_ids')->widget(Select2::classname(), [
		    'data' => $data,
		    'options' => [
		    	'placeholder' => '...', 
		    	'multiple' => true,
		    	'value' => json_decode($model->mkd_ids),
		    	],
		    'pluginOptions' => [
		        'tags' => true,
		        'tokenSeparators' => [',', ' '],
		        'maximumInputLength' => 10
		    ],
		])->label('Список МКД для которых выводить новость');
}
?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'body')->widget(CKEditor::className(),[
	 'editorOptions' => [
		 'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
		 'inline' => false, //по умолчанию false
		 'height' => 300,
	 ],
	]); ?>

    <?  //= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

<?
	$types_doc  = array('doc','odt','docx','xls','xlsx');
	$types_text = array('txt','csv');
	$types_pdf  = 'pdf';
	$initialPreview = array();
	$initialPreviewConfig = array();
$del_url = array();
	$arr = json_decode($model->imagesmas);
	
	if($arr){
		foreach($arr as $path){
		    $path = substr_replace($path, '/u',0 ,1);
		    $title = substr($path, 9, 5); 
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
		        array_push($initialPreviewConfig, array("type"=> "image", "caption"=> "$title","filename"=>"$path","url"=>"delfoto?i=$path"));  
		        array_push($del_url, array("path"=>"$path")); 
		    }
		}
	}

	echo ($model->imagesmas) ? 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), 
			
		    [
		        'name' => 'file',
		        'options' => ['multiple' => true],
		        'pluginOptions' => [
		        	//'deleteUrl' => Url::toRoute(['news/delfoto', 'img' => '{filename}']),
		        	//'deleteUrl' => Url::to(['news/delfoto/']).'{filename}',
		        	'deleteUrl' => Url::toRoute(['news/delfoto', 'id' => 3]),
		        	'initialPreviewDownloadUrl'=> Url::base(true).'{filename}',
		            'initialPreview' => $initialPreview,
		            'initialPreviewAsData' => true,
			        'overwriteInitial'=>false,		            
				    'initialPreviewConfig' => $initialPreviewConfig
		        ]
		    ]
	
	) : 
	 $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
	 		'options' => ['multiple' => true],
	 	]);
	?>

	<?= $form->field($model, 'type')->dropDownList(News::getTypeuseArray());?>
	<?= $form->field($model, 'fpage')->checkboxList(['1' => 'На главную страницу']);?>
	<?= $form->field($model, 'push')->checkboxList([ '1' => 'Рассылка в мобильное приложение']);?>
        
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