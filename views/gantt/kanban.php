<?
use kartik\sortable\Sortable;
use kartik\sortinput\SortableInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\models\Gantt;
use app\models\Tagsforgantt;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use lo\widgets\modal\ModalAjax;
use app\models\Mkd;

?>

<?php 
$css= <<< CSS
.dragdrop0{
	border-left: 2px solid #3c8dbc!important;
}
.dragdrop1{
	border-left: 2px solid #00a65a!important;
}
.dragdrop2{
	border-left: 2px solid #f39c12!important;
}
.dragdrop3{
	border-left: 2px solid #dd4b39!important;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>
<?
if(Yii::$app->user->identity->role =='government' || Yii::$app->user->identity->role =='user'){
	$data = Mkd::getOneMkdIDThisArea(Yii::$app->user->identity->id_a);
}elseif(Yii::$app->user->identity->role =='root'){
	$data = Mkd::getAllMkd();
}else{
	$data = Mkd::getAllMkdThisArea(Yii::$app->user->identity->id_org);
}

$html_news_title = '';
$html_news_body = '';
foreach($data as $id=>$value){
	$value = explode(',',$value);
	unset($value[0]); 
	$data[$id] = implode(',',$value);
	if($_GET['id_mkd']){ $k = true; }
	if($_GET['id_mkd'] == $id){ $k = false; }
	if($k != true){
		$html_news_title .= "
			<li class='nav-item active'>
				<a class='nav-link active' href='#tab$id' data-toggle='tab'><h3 class='title_chart box-title'>$data[$id]</h3></a>
			</li>		
		";
		$html_news_body .= "<div class='tab-pane active' id='tab$id'>";
		$k = true;
	}else{
		$html_news_title .= "
			<li class='nav-item'>
				<a class='nav-link' href='#tab$id' data-toggle='tab'><h3 class='title_chart box-title'>$data[$id]</h3></a>
			</li>		
		";
		$html_news_body .= "<div class='tab-pane' id='tab$id'>";
	}
	$tak = $this->render('kanban_view', ['id_mkd' => $id,]); 
	$html_news_body .= "
	<div class='col-md-12'>	
		<div class='row'>
			$tak
		</div>
	</div>
</div>	
	";
}

?>
	<div class="box" style="background-color: #ecf0f5;">
		<div class="tabbable" id="tabs">
			<div class="box-header with-border">
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
