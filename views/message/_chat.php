<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use app\models\User;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use nirvana\infinitescroll\InfiniteScrollPager;

/**
 * @var yiiwebView $this
 * @var appmodelsMessage $message
 * @var yiidbActiveQuery $messagesQuery
 */
 $this->title = Yii::t('app', '');
 if (\Yii::$app->user->identity->role != 'user') {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список пользователей'), 'url' => ['listusers']];
}
 
 $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'CHAT_LIST')];
?>
<?php 
$css= <<< CSS
.direct-chat-messages{
	height:450px;
}
#go_last {
	background-color: #cfedff;
}
.img-thumbnail{
	height:50px;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<!-- Construct the box with style you want. Here we are using box-danger -->
			<!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
			<!-- The contextual class should match the box, so we are using direct-chat-danger -->
			<div class="box box-primary direct-chat direct-chat-primary">
				<div class="box-header with-border">
			    <h3 class="box-title">Чат</h3>
			  </div>
				<!-- /.box-header -->
				<div class="box-body">
				<!-- Conversations are loaded here -->
				<div class="direct-chat-messages">
					
					<?php Pjax::begin([
					    'id' => 'list-messages',
					    'enablePushState' => false,
					    'formSelector' => false,
					    'linkSelector' => false
					]) ?>
					<?= $this->render('_list', compact('messagesQuery')) ?>
					<?php Pjax::end() ?>
					    
				</div>
				<!--/.direct-chat-messages-->
				
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<?php  $form = ActiveForm::begin(['options' => ['class' => 'pjax-form form-group input-group', 'style' => 'width:100%']]) ?>
						<div class="input-group">
							<span class="input-group-btn">
								<?= $form->field($message, 'imageFiles')->widget(FileInput::classname(), [
								    'options' => ['accept' => 'image/*'],
								    'pluginOptions' => [
								    	'browseClass' => 'btn btn-success',
								        'showPreview' => false,
								        'showCaption' => false,
								        'showRemove' => false,
								        'showUpload' => false,
										'browseLabel' => '',
								    ]				    	
								])->label(false); ?>								
							</span>
							<?= Html::activeTextinput($message, 'text', ['class' => 'form-control']) ?>
							<span class="input-group-btn" style="vertical-align: top;">
							<?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-flat', 'id' => 'btn_send']) ?>
							</span>							
							
						</div>	
					<?php ActiveForm::end() ?>			                
			  </div>
			<!-- /.box-footer-->
			</div>
			<!--/.direct-chat -->			
		</div>
			
		<div class="col-md-4" >
			
			
		</div>
	</div>
</div>
<?php $this->registerJs(<<<JS
$(document).ready(
	function(){ 
	
	$(".direct-chat-messages").scrollTop($("#list-messages").height()); 
	$(".last-chat-messages").hide();
	function updateList(where) { $.pjax.reload({container: where}); }
	let delay = 5000;	
			
	let timerId = setTimeout(function rek() {
		updateList('#list-messages');
		timerId = setTimeout(rek, delay);
	}, delay);
			

});
JS
) ?>

<?
/*
		var a = $(".direct-chat-messages").height();
		var b = $("#list-messages").height();
		var c = $(".direct-chat-messages").scrollTop();
		var ac = a+c;	

		if ( ( ac+500 >= b ) && ( ac-500 <= b ) ) {
			$(".direct-chat-messages").scrollTop($("#list-messages").height());
			updateList('#list-messages');
			$(".last-chat-messages").hide();
		}else{
	$( "#go_last" ).click(function() {
		$(".last-chat-messages").hide();
		$(".direct-chat-messages").scrollTop($("#list-messages").height());
	});
*/
?>
