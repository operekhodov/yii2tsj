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
<?php Pjax::begin();?>
<?php  $form = ActiveForm::begin(['options' => ['data' => ['pjax' => true],'class' => 'pjax-form','id' => "kanban_view_$id_mkd"]]) ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title">Новая задача (<?= count(Gantt::Kanban(0,$id_mkd))?>) </h3>
			    <div class="box-tools pull-right">
			      <span class="label label-primary">Новая задача</span>
			    </div>
			  </div>
			  <div class="box-body">
				<?
				echo SortableInput::widget([
				    'name'=>'st_new',
				    'items'=>Gantt::Kanban(0,$id_mkd),
					'sortableOptions' => [
						'connected'=>true,
						'pluginEvents' => ['sortupdate' => "function() { $('#kanban_view_$id_mkd').submit(); }",],
					],				    
				]);
				?>
			  </div>
			  <div class="box-footer">
				<?= (Yii::$app->user->identity->role !='user') ? ModalAjax::widget([
					'id' => "add_event_$id_mkd",
					'header' => '<h3>'.Yii::t('app', 'Добавить задачу').'</h3>',
					'toggleButton' => [
						'label' => 'Добавить',
						'tag' => 'span',
						'class' => 'btn btn-success',
					],
					'url' => Url::to(['add_event','id_mkd' => $id_mkd]),
					'ajaxSubmit' => true,
				]) : '';?>			  	
			  </div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="box box-success">
			  <div class="box-header with-border">
			    <h3 class="box-title">В работе (<?= count(Gantt::Kanban(1,$id_mkd))?>) </h3>
			    <div class="box-tools pull-right">
			      <span class="label label-success">В работе</span>
			    </div>
			  </div>
			  <div class="box-body">
				<?
				echo SortableInput::widget([
				    'name'=>'st_work',
				    'items'=>Gantt::Kanban(1,$id_mkd),
					'sortableOptions' => [
						'connected'=>true,
						'pluginEvents' => ['sortupdate' => "function() { $('#kanban_view_$id_mkd').submit(); }",],
					],
				]);
				?>
			  </div>
			</div>			
		</div>
		<div class="col-md-3">
			<div class="box box-warning">
			  <div class="box-header with-border">
			    <h3 class="box-title">Сдача задачи (<?= count(Gantt::Kanban(2,$id_mkd))?>) </h3>
			    <div class="box-tools pull-right">
			      <span class="label label-warning">Сдача задачи</span>
			    </div>
			  </div>
			  <div class="box-body">
				<?
				echo SortableInput::widget([
				    'name'=>'st_test',
				    'items'=>Gantt::Kanban(2,$id_mkd),
					'sortableOptions' => [
						'connected'=>true,
						'pluginEvents' => ['sortupdate' => "function() { $('#kanban_view_$id_mkd').submit(); }",],
					],
				]);
				?>
			  </div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="box box-danger">
			  <div class="box-header with-border">
			    <h3 class="box-title">Выполнена (<?= count(Gantt::Kanban(3,$id_mkd))?>) </h3>
			    <div class="box-tools pull-right">
			      <span class="label label-danger">Выполнена</span>
			    </div>
			  </div>
			  <div class="box-body">
				<?
				echo SortableInput::widget([
				    'name'=>'st_done',
				    'items'=>Gantt::Kanban(3,$id_mkd),
					'sortableOptions' => [
						'connected'=>true,
						'pluginEvents' => ['sortupdate' => "function() { $('#kanban_view_$id_mkd').submit(); }",],
					],				    
				]);
				?>
			  </div>
			  <div class="box-footer">
			    Удалить список
			  </div>
			</div>			
		</div>
	</div>
</div>
<?php ActiveForm::end() ?>
<?php Pjax::end(); ?>
