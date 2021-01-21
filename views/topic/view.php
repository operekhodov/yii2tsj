<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\chartjs\ChartJs;
use app\models\Topicans;
use app\models\Topic;
use app\models\User;
use app\models\Mkd;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use yii\bootstrap\ActiveForm;

$this->title = $model->topic;//Yii::t('app', 'Отчет');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Все опросы'), 'url' => ['index']];
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
	.file-actions {
	    display: none;
	}
	.file-drag-handle.drag-handle-init.text-info {
	    display: none;
	}
	.krajee-default.file-preview-frame .file-thumbnail-footer {
	    height: 0px;
	}
	.file-footer-caption {
	    margin: 0 !important;
	}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );

if($model->type == 0 || $model->type == 2) {
	$arr = json_decode($model->answermas);
	$ans_mas = array();				
	foreach($arr as $key => $value) {
		$ans_mas[$key] = 0;
	}
	$arr = Topicans::getCountDA($model->id);
	foreach($arr as $arr_ans){
		if($model->type == 0) {
			$ans_mas[$arr_ans['answer']] = intval($arr_ans['cnt']);
		}else{
			$ans_mas[$arr_ans['answer']-1] = intval($arr_ans['cnt']);
		}
	}
	$max_ans = ($ans_mas) ? max($ans_mas) + 1 : 5;
}else{
	$all_ans = Topicans::getAllSDA($model->id);
	$ans_mas = array();
	for($i=0;$i<count(json_decode($model->answermas));$i++){
		$ans_mas[$i] = 0;
		foreach($all_ans as $value){
			if(in_array($i, json_decode($value))){
				$ans_mas[$i]++;
			}
		}
	}
	$max_ans = ($ans_mas) ? max($ans_mas) + 1 : 5;
}

$DetailView_first = 
DetailView::widget([
    'model' => $model,
    'attributes' => [
    	'topic',
        'quest',
        'avtor',
        'note',
        [
        	'attribute' => 'starttime',
        	'format' => ['DateTime','php:d.m.Y']
        ],
        [
        	'attribute' => 'deadtime',
        	'format' => ['DateTime','php:d.m.Y'],
            'value' => $model->starttime+$model->deadtime      	
        ],
        [
        	'attribute' => 'answermas',
        	'format'	=> 'raw',
        	'value'		=> str_replace(['[',']'],'',$model->answermas)
        ],
    ],
]);

$ChartJs_bar = 
ChartJs::widget([
    'type' => 'bar',
	'clientOptions' => [
        'legend' => [
            'position' => 'top',
            'display' => false,
        ],		
	    'responsive' => true,
	    'scales' => [
	         'yAxes' => [[
	              'ticks' => [
						'stepSize'=> 1,
						'beginAtZero'=> false,'stepSize'=> 1,'max'=> $max_ans,'min'=> 0,
	              ],
	         ]],          
	    ]
	],    
    'options' => [
        'height' => 'auto',
        'width' => 'auto',
    ],
    'data' => [
        'labels' => json_decode($model->answermas),
        'datasets' => [
            [
                'data' => $ans_mas,
	            'backgroundColor'=> [
	                'rgba(255, 99, 132, 0.4)',
	                'rgba(54, 162, 235, 0.4)',
	                'rgba(255, 206, 86, 0.4)',
	                'rgba(75, 192, 192, 0.4)',
	                'rgba(153, 102, 255, 0.4)',
	                'rgba(255, 159, 64, 0.4)',
	            ],
            	'borderColor'=> [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
            ],
            	'borderWidth'=> 1,
			]
        ]
    ],
    'plugins' =>
        new \yii\web\JsExpression('
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = \'rgb(0, 0, 0)\';

                            var fontSize = 16;
                            var fontStyle = \'normal\';
                            var fontFamily = \'Helvetica\';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = \'center\';
                            ctx.textBaseline = \'middle\';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
              
		var img1 = chart.toBase64Image();
		$("#id").val('.$model->id.');
		$("#img1").val(img1);

            }
           
        }]')    
]);
?>
<div class="topicans-index">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">
		<div class="box box-primary">
			<? if(\Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)){ ?>
			  <div class="box-header with-border">
		    	<div class="box-tools pull-right">
			        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id, 'key' => count(json_decode($model->answermas)), 'df' => 0, 'type' => $model->type], ['class' => 'btn btn-primary']) ?>
			        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
			            'class' => 'btn btn-danger',
			            'data' => [
			                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
			                'method' => 'post',
			            ],
			        ]) ?>
		    </div>
		  </div> 
			<? }elseif(!Topicans::getSDA($model->id,\Yii::$app->user->identity->id)){ ?>
			<div class="box-header with-border">
				<div class="box-tools pull-right">
					<? 
						if(intval($model->starttime) < time()) 
						{
							echo ( $model->starttime + $model->deadtime < time() && intval($model->starttime) < time() ) ? '' : Html::a(Yii::t('app', 'Пройти опрос'), ['answer', 'id' => $model->id], ['class' => 'btn btn-primary']);
						}
					?>
				</div>
			</div>
			<? } ?>
		  <div class="box-body">
			    <?= $DetailView_first ?>
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
		<div class="box box-primary">
		  <div class="box-header with-border">
		    <div class="box-tools pull-right">

				<?
				if(\Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)){  
					 $form = ActiveForm::begin(['action' => ['mpdfexport'],'options' => ['method' => 'post']]) ?>
					 <input id="id"   type="hidden" name="id"   value="">
					 <input id="id_a"   type="hidden" name="id_a"   value="<?= $model->id_a ?>">
					 <input id="topic"   type="hidden" name="topic"   value="<?= $model->topic ?>">
					 <input id="img1" type="hidden" name="img1" value="">
					 <input id="img2" type="hidden" name="img2" value="">
					<button type="submit" class="btn btn-success">Скачать результаты в pdf</button>
					 <?php ActiveForm::end();
				}
				?>
		    </div>
		  </div>	
		  
		  <div class="box-body">
		    <?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'filterModel' => $searchModel,
		        'columns' => [
		            ['class' => 'yii\grid\SerialColumn'],
			        [
			        	'attribute' => 'created_at',
			        	'format' => ['DateTime','php:d.m.Y']
			        ],		            
			        [ 
			        	'filter' => User::getAllUsersNameThisMkd($model->id_a),
			            'attribute'=>'id_u',
			            'value'=>'UserUsername',
		    		],
		            [
		            	'filter' => Topic::getAnsforfilter($model->id),
		                'attribute' => 'answer',
		                'format' => 'raw',
		                'value' => function ($model, $key, $index, $column) {
		                    $html = Topic::getTopicAnswer($model->id_q,$model->answer) ;
		                    return $html;
		                }
		            ],    		
		            //'note',
		        ],
		    ]); ?>
		  </div>
		</div>		
		</div>
		
		<div class="col-md-4">
			
		<div class="box box-primary">
		  <div class="box-body">
	<?= $ChartJs_pie ?>
			<?= $ChartJs_bar ?>
			<table id="w0" class="table table-striped table-bordered detail-view">
				<tbody>
					<? foreach(json_decode($model->answermas) as $key => $value){ 
						echo "
						<tr>
							<th>$value</th><td>$ans_mas[$key]</td>
						</tr>";
					} ?>
				</tbody>
			</table>			
		  </div>
		</div>

		<div class="box box-primary">
		  <div class="box-body">
		  	<?  $CountAllUsersThisArea = User::getCountAllUsersThisArea( $model->id_a ); 
		  		$CountUsersAnswer = Topicans::getCountUsersAnswer($model->id);
		  		$a = $CountAllUsersThisArea-$CountUsersAnswer;
		  	?>
			<?= ChartJs::widget([
			    'type' => 'pie',
			    'options' => [
			        'height' => 'auto',
			        'width' => 'auto'
			    ],
			    'data' => [
			        'labels' => [
			        		'Не проголосовали','Проголосовало'
			        	],
			        'datasets' => [
			            [
			            	'barPercentage' => 1,
			            	'categoryPercentage' => 1,
			                'data' => [
			                		"$a","$CountUsersAnswer"
			                	],
			                'backgroundColor' => [
			                        '#ADC3CF',
			                        '#FF9A0A',
			                ],                
			            ],
			        ]
			    ],
    'plugins' =>
        new \yii\web\JsExpression('
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = \'rgb(0, 0, 0)\';

                            var fontSize = 16;
                            var fontStyle = \'normal\';
                            var fontFamily = \'Helvetica\';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = \'center\';
                            ctx.textBaseline = \'middle\';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
              
		var img2 = chart.toBase64Image();
		$("#img2").val(img2);
            }
           
        }]') 			    
			]);
			?>
			
			<table id="w0" class="table table-striped table-bordered detail-view">
				<tbody>
					<tr>
						<th>Всего жителей:</th><td><?= $CountAllUsersThisArea ?></td>
					</tr>
					<tr>
						<th>Количество жителей прошедших голосование:</th><td><?= $CountUsersAnswer ?></td>
					</tr>
					<tr>
						<th>Процент прошедших голосование:</th><td><?= ($CountAllUsersThisArea) ? round(($CountUsersAnswer/$CountAllUsersThisArea)*100) : '0'; ?> %</td>
					</tr>
				</tbody>
			</table>
		  </div>
		</div>		
			
		</div>
	</div>
</div>





</div>
