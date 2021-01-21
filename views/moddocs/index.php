<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Moddocs;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\widgets\grid\LinkColumn;

$this->title = Yii::t('app', 'Moddocs');
$this->params['breadcrumbs'][] = $this->title;
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
?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?= $this->title ?></h3>
		    <div class="box-tools pull-right">
				<?= Html::a(Yii::t('app', 'Create_Moddocs'), ['create'], ['class' => 'btn btn-success']) ?>
		    </div>

		  </div>

		  <div class="box-body">

<div class="moddocs-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            	'class' => LinkColumn::className(),
            	'attribute'=>'title',
            ],              
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?
$types_doc  = array('doc','odt','docx','xls','xlsx');
$types_text = array('txt','csv');
$types_pdf  = 'pdf';
$initialPreview = array();
$initialPreviewConfig = array();

$arr=Moddocs::getAlldocarea();
foreach($arr as $title => $path){
    $path = json_decode($path);
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
		<div class="col-md-4">
		</div>
	</div>
</div>