<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php 
$css= <<< CSS
.kv-file-remove {
    display: none;
}
.input-group.file-caption-main {
    display: none;
}
.close.fileinput-remove {
    display: none;
}
.content-header > h1{
    margin-top: 30px;    
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
		    
		    <div class="box-tools pull-right">
				<? if(\Yii::$app->user->can('agent') || $model->id_u == Yii::$app->user->identity->id){ ?>
				        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
				        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
				            'class' => 'btn btn-danger',
				            'data' => [
				                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				                'method' => 'post',
				            ],
				        ]) ?>
				<?}?>
		    </div>
		  </div>
		  <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'body:html',
            [
            	'label' => 'МКД',
            	'attribute' => 'mkd_ids',
            	'format' => 'html',
            	'value' => $model->getHTMLAddressMkdList($model->mkd_ids)
            ],
        ],
    ]) ?>
<?
$types_doc  = array('doc','odt','docx','xls','xlsx');
$types_text = array('txt','csv');
$types_pdf  = 'pdf';
$initialPreview = array();
$initialPreviewConfig = array();

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
	        array_push($initialPreviewConfig, array("type"=> "image", "caption"=> "$title","filename"=>"$path"));  
	    }
	}
	
			echo FileInput::widget(
			    [
			        'name' => 'file',
			        'pluginOptions' => [
			        	'initialPreviewDownloadUrl'=> Url::base(true).'{filename}',
			            'initialPreview' => $initialPreview,
			            'initialPreviewAsData' => true,
					    'initialPreviewConfig' => $initialPreviewConfig
			        ]
			    ]
			);
}
?>    


		  </div>
		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>