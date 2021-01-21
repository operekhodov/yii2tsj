<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\rating\StarRating;

$this->title = $model->quest;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Опросы'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $model->topic;
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
		    <h3 class="box-title"><?=$this->title ?></h3>
		  </div>
		  <div class="box-body">
<div class="topic-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'topic',
            'note',
        ],
    ]) ?>
			<table id="w0" class="table table-striped table-bordered detail-view">
				<tbody>
					<tr>
						<th><span class="glyphicon glyphicon-time"></span> До окончания опроса осталось:</th><td>
						<?
function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    return $obj;
}

if ($model->starttime + $model->deadtime < time() ) {
	echo "Время голосования истекло";
}else{
	$proshlo = time() -$model->starttime;
	$ostalos = secondsToTime($model->deadtime -$proshlo);
	echo ($ostalos[d]) ? $ostalos[d].' дней ' : '';
	echo ($ostalos[h]) ? $ostalos[h].' часов ' : '';
	echo ($ostalos[m]) ? $ostalos[m].' минут ' : '';
}

							
						?></td>
					</tr>
				</tbody>
			</table>    
	<?= Html::beginForm('view?id='.$model->id, 'post'); ?>    
	<?
	$items = json_decode($model->answermas); 
	if($model->type == 0){
		echo Html::radioList('answer', null, $items, ['separator'=>"<hr />",'encode'=>false]);
	}elseif($model->type == 2){
		
		$col = count(json_decode($model->answermas));
		
		// A disabled star rating input with initial value
		echo '<label class="control-label">Ваша оценка</label>';
		echo StarRating::widget([
		    'name' => 'answer',
		    //'value' => 3,
		    'pluginOptions' => [
		    	//'showCaption' => false,
				'defaultCaption' => '{rating} звезд',
		        'starCaptions' => [
		            1 => '1 звезда',
		            2 => '2 звезды',
		            3 => '3 звезды',
		            4 => '4 звезды',
		            5 => '5 звезд',
		        ],				
		        'starCaptionClasses' => [
		            1 => 'label label-danger badge-danger',
		            2 => 'label label-danger badge-danger',
		            3 => 'label label-warning badge-warning',
		            4 => 'label label-warning badge-warning',
		            5 => 'label label-info badge-info',
		            6 => 'label label-info badge-info',
		            7 => 'label label-primary badge-primary',
		            8 => 'label label-primary badge-primary',
		            9 => 'label label-success badge-success',
		            10 => 'label label-success badge-success',
		        ], 
		        
		        'showClear' => false,
		    	'size' => 'xl',
		        'stars' => $col, 
		        'min' => 0,
		        'max' => $col,
		        'step' => 1,		    	
		    ]
		]);	
	}else{
		echo Html::checkboxList('answer', null, $items, ['separator'=>"<br /><hr />",'encode'=>false]);
	}
	?>
	<?= Html::hiddenInput('type', $model->type) ?>
	<?= Html::hiddenInput('id_q', $model->id) ?>
	<br>
	<div class="form-group">
	    <?= Html::submitButton('Проголосовать', ['class' => 'btn btn-success']) ?>
	</div>
	
	<?php Html::endForm(); ?>	



<br>
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
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>