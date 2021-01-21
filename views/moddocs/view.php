<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\helpers\Url;
use lesha724\documentviewer\MicrosoftDocumentViewer;
/* @var $this yii\web\View */
/* @var $model app\models\Moddocs */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Moddocs'), 'url' => ['index']];
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
		    <div class="box-tools pull-right">
			    <p>
			        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			        <?= Html::a(Yii::t('app', 'BUTTON_DELETE'), ['delete', 'id' => $model->id], [
			            'class' => 'btn btn-danger',
			            'data' => [
			                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
			                'method' => 'post',
			            ],
			        ]) ?>
			    </p>
		    </div>

		  </div>

		  <div class="box-body">


<div class="moddocs-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'title',
            //'path',
            //'id_a',
        ],
    ]) ?>

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

?>

</div>


		  </div>

		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>