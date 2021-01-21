<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Tasks;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Options;
use app\models\Mkd;
use app\models\Logs;
use yii\bootstrap\Progress;
use newerton\fancybox\FancyBox;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

$this->title = "Карта задачи";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_TASKS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$css= <<< CSS
.content-header{
	display: list-item;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles1" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">


<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?=$this->title ?></h3>
    <div class="box-tools pull-right">
		<? if(Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)){ ?>
		        <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
		            'class' => 'btn btn-danger',
		            'data' => [
		                'confirm' => 'Are you sure you want to delete this item?',
		                'method' => 'post',
		            ],
		        ]) ?>
		<? } ?>
    </div>

  </div>

  <div class="box-body">
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'num',
            'createddate:datetime',
            //'finishdate:datetime',
            'enddt',
        [
            'attribute' => 'status',
            'value' => $model->StatusView($model->status),
            'format' => 'html',
        ],
            'info',
		[
			'attribute'=>'id_a',
			'value'=>Mkd::getAddressMkdByID($model->id_a),
		],
            'floor',
            'porch',
        [
            'attribute'=>'assignedto',
            'value'=>$model->getUserUsername(),
        ],
            'notes',
        [
        	'attribute'=>'type',
        	'value'=>$model->getTypeName(),
        ],

        ],
    ]) ?>

  </div>    
</div>
<? $createdby = User::findById($model->createdby); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Задача добавленна:</h3>
  </div>
  <div class="box-body">
	<table class="table table-striped table-bordered detail-view">
	<tbody>
	   <tr>
	       <th>
	           ФИО:
	       </th>
	       <td>
	          <?= $createdby->lname.' '.$createdby->fname.' '.$createdby->fio ?>
	       </td>
	   </tr>
	   <tr>
	       <th>
	        Телефон:
	       </th>
	       <td>
	          <?= '+7 '.$createdby->phonenumber ?>
	       </td>
	   </tr>	
	   <? if($createdby->role == 'user'){ ?>
	   <tr>
	       <th>
	           Адрес:
	       </th>
	       <td>
	          <?= $createdby->locality.', '.$createdby->street.', '.$createdby->num_house; ?>
	       </td>
	   </tr>
	   <tr>
	       <th>
	           Доля в общей площади:
	       </th>
	       <td>
	          <?= $createdby->share ?>
	       </td>
	   </tr>
	   <? } ?>
	</tbody> 
	</table>
  </div>
</div>

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
$start_time 	= $model->createddate;
$deadline		= strtotime($model->enddt);
$time_now		= time();
$all_time		= $deadline - $start_time;
$time_in_work	= (secondsToTime( $model->finishdate- $start_time));
$time_work		= $time_now - $start_time;
$percent		= round(($time_work/$all_time)*100);
$time_free		= (secondsToTime($deadline - $time_now));
$time_done_work = ($model->finishdate) ? secondsToTime(($model->finishdate) - $start_time) : secondsToTime($time_work);
?>

<div class="box box-primary">
  <div class="box-body">	
    <table class="table table-striped table-bordered detail-view">
		<tbody>
<? if ($model->status == 1) { ?>			
   		<tr>
   			<th>Задача была в работе:</th>
   			<td><?= $time_in_work['d'].'д '.$time_in_work['h'].'ч' ?></td>
   		</tr>			
<? }
if($model->enddt && $model->status != 1){?>			
   		<tr>
   			<th>Задача в работе:</th>
   			<td><?= $time_done_work['d'].'д '.$time_done_work['h'].'ч' ?></td>
   		</tr>
       <tr>
           <th>
               <h4>До закрытия задачи осталось: </h4>
           </th>
           <td>
            <?= $time_free['d'].'д '.$time_free['h'].'ч'?>
			<? echo Progress::widget([
			    'bars' => [
			        ['percent' => $percent, 'label' => $percent.'%', 'options' => ['class' => 'progress-bar-warning']],
			    ]
			   ]);?>             
           </td>
       </tr>
<?}?>
   </tbody> 
	</table>

    <table class="table table-striped table-bordered detail-view">
   <tbody>
       <tr>
           <th>
               Теги
           </th>
           <td>
			<?
				$model->tags = json_decode($model->tags);
				
				$data = Options::getAllTags();
				if ($model->tags) {
					foreach ($model->tags as $key) {
					   echo '<span class="label label-primary">'.$data[$key]."</span> ";
					}
				}
			?>               
           </td>
       </tr>
   </tbody> 
</table>
  </div>
</div>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">История изменения статуса заявки</h3>
  </div>	
  <div class="box-body">
	<table class="table table-striped table-bordered detail-view">
   <tbody>
       <tr>
           <th>
               Дата
           </th>
           <th>
           	Статус
           </th>
        </tr>
           <? 
            $history = Logs::getStHistory($model->id);
        	foreach($history as $date => $status) { 
                switch ($status) {
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
                if ($status != '') {
                	$html = Html::tag('span', Html::encode(Tasks::getStName($status)), ['class' => 'label label-' . $class]);
                }else{
                	$html = Html::tag('span', Html::encode('Добавлена'), ['class' => 'label label-' . $class]);
                }
           ?>
       <tr><td><?=$date?></td>
           <td><?=$html?></td></tr>
           <? } ?>
       
   </tbody> 
</table>
  </div>
</div>
<?
echo FancyBox::widget([
    'target' => 'a[rel=fancybox]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);

$arr_img = json_decode($model->imagebd);
if($arr_img) {
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<?= Html::a(Html::img('/'.$arr_img[0], ['class' => 'img-thumbnail']), '/'.$arr_img[0], ['rel' => 'fancybox']);?>
		</div>
		<div class="col-md-3">
			<? echo ($arr_img[1]) ? Html::a(Html::img('/'.$arr_img[1], ['class' => 'img-thumbnail']), '/'.$arr_img[1], ['rel' => 'fancybox']) : ''; ?>
		</div>
		<div class="col-md-3">
			<? echo ($arr_img[2]) ? Html::a(Html::img('/'.$arr_img[2], ['class' => 'img-thumbnail']), '/'.$arr_img[2], ['rel' => 'fancybox']) : ''; ?>
		</div>
		<div class="col-md-3">
			<? echo ($arr_img[3]) ? Html::a(Html::img('/'.$arr_img[3], ['class' => 'img-thumbnail']), '/'.$arr_img[3], ['rel' => 'fancybox']) : ''; ?>
		</div>
	</div>
</div>
<?}?>

		</div>
		<div class="col-md-4">
			<? if(Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL)) { ?>			
			<?php Pjax::begin([
			    'timeout' => 3000,
			    'enablePushState' => false,
			    'linkSelector' => false,
			    'formSelector' => '.pjax-form'
			]) ?>
			<?= $this->render('_chat', compact('messagesQuery', 'message')) ?>
			<?php Pjax::end() ?>
			<? } ?>
		</div>
	</div>
</div>