<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use dosamigos\chartjs\ChartJs;
use yii\grid\GridView;
use app\models\Tasks;
use app\models\News;
use app\models\User;
use app\models\Indicat;
use app\models\Mkd;
use yii\bootstrap\Carousel;

$this->title = Yii::t('app','FP_name_system');

$css= <<< CSS
.carousel-indicators{
	bottom: -20px;
}
.carousel-indicators > li {
	background-color: black;
	border: 1px solid black;
}
.carousel-control{
	color: black;
	font-size: 50px;
	width: 9%;
	padding: 4% 0;
}
.carousel-control:hover, .carousel-control:active, .carousel-control:focus{
	color: black;
}
#head_news {
	height: 7rem;
	display: table-cell;
	vertical-align: middle;
	float: unset;
}
.content-header {
	display:none;
}
.date_column{
    width: 20%;
    text-align:center;
}
.title_chart {
	font-size: 16px!important;
}
.pdr {
	margin-right: 5px;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );

if(!Yii::$app->user->isGuest):
?>


<div class="container-fluid workplace">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6"> 
<? if(Yii::$app->user->identity->role !='user' && Yii::$app->user->identity->role !='root' ) { 

if(Yii::$app->user->identity->role =='government'){
	$data = Mkd::getOneMkdIDThisArea(Yii::$app->user->identity->id_a);
}else{
	$data = Mkd::getAllMkdThisArea(Yii::$app->user->identity->id_org);
}
foreach($data as $id=>$value){
	$value = explode(',',$value);
	unset($value[0]); 
	$data[$id] = implode(',',$value);
	if($k != true ){
		$html_news_title = "
			<li class='nav-item active'>
				<a class='nav-link active' href='#tab$id' data-toggle='tab'><h3 class='title_chart box-title'>$data[$id]</h3></a>
			</li>		
		";
		$html_news_body = "<div class='tab-pane active' id='tab$id'>";
		$k = true;
	}else{
		$html_news_title .= "
			<li class='nav-item'>
				<a class='nav-link' href='#tab$id' data-toggle='tab'><h3 class='title_chart box-title'>$data[$id]</h3></a>
			</li>		
		";
		$html_news_body .= "<div class='tab-pane' id='tab$id'>";
	}
	$tskCount1	= Tasks::getThisAreaTasksCount($id,1);
	$tskCount3	= Tasks::getThisAreaTasksCount($id,3);
	$tskCount2	= Tasks::getThisAreaTasksCount($id,2);
	$tskCount0	= Tasks::getThisAreaTasksCount($id,0);
	$u0			= User::getThisAreaUsersCount($id,0);
	$u1			= User::getThisAreaUsersCount($id,1);
	$u2			= User::getThisAreaUsersCount($id,2);
	$sum_tsk	= $tskCount0+$tskCount3+$tskCount2;
	$sum_u		= $u1 + $u2;
	$html_news_body .= "
<div class='col-md-12'>	
		<div class='row'>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-aqua'>
		            <span class='info-box-icon'><i class='fa fa-cogs'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Задачи по МКД</span>
		                <span class='info-box-number'>$sum_tsk </span>
		            </div>
					<span class='pull-right-container'>
						<small class='label pull-right bg-red pdr'> $tskCount1</small> 
						<small class='label pull-right bg-gray pdr'> $tskCount3</small> 
						<small class='label pull-right bg-blue pdr'> $tskCount2</small> 
						<small class='label pull-right bg-green pdr'> $tskCount0</small>
					</span>
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-green'>
		            <span class='info-box-icon'><i class='fa fa-bar-chart'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Передано показаний</span>
		                <span class='info-box-number'>41,410</span>
		                <div class='progress'>
		                    <div class='progress-bar' style='width: 70%'></div>
		                </div>
		                <span class='progress-description'>
		    				70% Increase in 30 Days
						</span>
		            </div>
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-yellow'>
		            <span class='info-box-icon'><i class='fa fa-users'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Количество жителей</span>
		                <span class='info-box-number'>$sum_u</span>
		            </div>
					<span class='pull-right-container'>
						<small class='label pull-right bg-red pdr'>$u0</small> 
						<small class='label pull-right bg-gray pdr'>$u2</small> 
						<small class='label pull-right bg-green pdr'>$u1</small>
					</span>                    
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-red'>
		            <span class='info-box-icon'><i class='fa fa-pie-chart'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Задолжность по МКД</span>
		                <span class='info-box-number'>41,410</span>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>	
	";
}

?>
	<div class="box box-primary">
		<div class="tabbable" id="tabs">
			<div class="box-header with-border">
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				</div>
				<div class="box-tools pull-left">
					<ul class="nav nav-tabs">
						<?= $html_news_title ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="tab-content">
				<?= $html_news_body ?>
			</div>
		</div>
	</div>

<? } ?>					
					<div class="row"> <!-- Новости -->
						<div class="col-md-12 news">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">
										<a href="<?= Url::toRoute(['news/index']); ?>">Новости</a> 
									</h3>
								    <div class="box-tools pull-right">
								      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								    </div>									
								</div>
								<div class="box-body">			
								<?
								echo Carousel::widget([
								    'items' => News::getItemsHTML(),
								    'showIndicators' => true,
								    'controls' => ['&lsaquo;', '&rsaquo;'],
								    'clientOptions'=> [
								    	'interval' => '500000',
								    	],
								    
								    
								]);
								?>
								</div>
							</div>
						</div>
					</div>
					<div class="row border">
						<div class="col-md-12"> <!-- Задачи -->
							<div class="box box-primary">
								<div class="box-header with-border">
										<h3 class="box-title">
											<a href="<?= Url::toRoute(['tasks/index']); ?>">Заявки</a> 
										</h3>
									    <div class="box-tools pull-right">
									      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									    </div>
								</div>
								<div class="box-body">
									    <div class="box-tools pull-left">
											<a href="/web/tasks/create" class="btn btn-block btn-primary">
												Оставить заявку
											</a>
										</div>
								</div>	
								<div class="box-body">
									    <?= GridView::widget([
									        'dataProvider' => $dataProvidertask,
									        'filterModel' => $searchModeltask,
									        'columns' => [
										        [
										            'filter' => DatePicker::widget([
										                'model' => $searchModeltask,
										                'attribute' => 'date_from',
										                'attribute2' => 'date_to',
										                'type' => DatePicker::TYPE_RANGE,
										                'separator' => '-',
										                'pluginOptions' => ['format' => 'yyyy-mm-dd']
										            ]),
										            'attribute' => 'createddate',
										            'format' => 'datetime',
							    'contentOptions' => ['class' => 'date_column'],
							    'headerOptions' => ['class' => 'date_column'] 								            
										        ],
									            [
									                'filter' => Tasks::getStatusesArray(),
									                'attribute' => 'status',
									                'format' => 'raw',
									                'value' => function ($model, $key, $index, $column) {
									                    /** @var Tasks $model */
									                    /** @var \yii\grid\DataColumn $column */
									                    $value = $model->{$column->attribute};
									                    switch ($value) {
									                        case Tasks::STATUS_NEW:
									                            $class = 'success';
									                            break;
									                        case Tasks::STATUS_DONE:
									                            $class = 'warning';
									                            break;
									                        case Tasks::STATUS_INWORK:
									                            $class = 'primary';
									                            break;
									                        case Tasks::STATUS_MODER:
									                            $class = 'default';
									                            break;                            
									                        default:
									                            $class = 'default';
									                    };
									                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);
									
									                    return $value === null ? $column->grid->emptyCell : $html;
									                },
							    'contentOptions' => ['class' => 'text-center'],
							    'headerOptions' => ['class' => 'text-center'] 									                
									            ],
									            [
									            	'attribute' =>'info',
							    'contentOptions' => ['class' => 'text-right'],
							    'headerOptions' => ['class' => 'text-right'] 										            	
									            ],
									
									        ],
									    ]); ?>								
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6"> 
					<div class="row"><!-- Показатели -->
						<div class="box box-primary">
							<div class="tabbable" id="tabs-712944">
								<div class="box-header with-border">
								   <div class="box-tools pull-right">
								      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								    </div>
								    <div class="box-tools pull-right">
										<ul class="nav nav-tabs">
											<li class="nav-item active">
												<a class="nav-link active" href="#tab1" data-toggle="tab"><h3 class="title_chart box-title">Водоснабжение</h3></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#tab2" data-toggle="tab"><h3 class="title_chart box-title">Электроэнергия</h3></a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="box-body">
								<div class="tab-content">
								<div class="tab-pane active" id="tab1">
										<?= Indicat::getChartHTMLALLwater(); ?>
								</div>
								<div class="tab-pane" id="tab2">
										<?= Indicat::getChartHTMLALLelectric(); ?>
								</div>
							</div>
							</div>
						</div>
					</div>
					<div class="row"><!-- Сумма по квитанциям -->
						<div class="box box-primary">
						  <div class="box-header with-border">
						    <h3 class="box-title">Сумма всех услуг по квитанции</h3>
						    <div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						    </div>
						  </div>
				
						  <div class="box-body">
						    <?= Indicat::getChartHTMLALLmoney() ?>
						  </div>
						</div>					
					</div>
					<div class="row"><!-- Начислено -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">
									Начислено за декабрь
								</h3>
							   <div class="box-tools pull-right">
							      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							    </div>							
							</div>
							<div class="box-body" style="text-align:center;">
								<h3>
									4 475,67 р
								</h3>
								<button type="button" class="btn btn-primary btn-block btn-md">
									Оплатить квитанцию
								</button><br>
								<a href="/">
								<p>Перейти к истории начислений -></p></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?
endif;
?>