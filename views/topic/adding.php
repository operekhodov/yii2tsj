<?php
use app\models\Topic;
use app\models\Mkd;
use yii\helpers\Html;
use yii\widgets\Pjax;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\bootstrap4\Modal;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

 $this->title = 'Добавить опрос';
 $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Опросы'), 'url' => ['index']];
 $this->params['breadcrumbs'][] = $this->title;
?>
<?php 
$css= <<< CSS
.fileinput-upload.fileinput-upload-button,
.fileinput-remove.fileinput-remove-button{
    display:none; 
}
.mybtn,
.mybtn.focus
{
    background: #fff;
    color: black;
    margin: 5px 10px;
}
.type-btn
{
	margin-top: 5px;
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
		  </div>
    <?php $form = ActiveForm::begin(); ?>
		  <div class="box-body">
			<div class="row">
				<div class="col-md-6">
				    <?= $form->field($model, 'topic')->textInput(['maxlength' => true, 'list' => 'topic_list']) ?>
					<?
					echo '<datalist id="topic_list">';
					echo Html::renderSelectOptions(null,Topic::getTopicNames());    
					echo '</datalist>';
					?>					
				</div>
				<div class="col-md-6">
				    <?
				    if (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL) ) {
						if(\Yii::$app->user->identity->role == 'root') {
							echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkd());
						}else{
					    	echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkdThisArea(\Yii::$app->user->identity->id_org));
						}
				    }else{ $id_mkd = Yii::$app->user->identity->id_a; 
				    	echo "<input type=\"hidden\" id=\"topic-type\" class=\"form-control\" name=\"Topic[id_mkd]\" value=\"$id_mkd\">";
				    }
					?>					
				</div>
			</div>
    <?= $form->field($model, 'quest')->textarea(['rows' => '6']) ?>

	<div class="row">
		<div class="col-md-6">
<?
$model->sttime = date("d.m.yy");

echo $form->field($model, 'sttime')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Enter date ...'],
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd.mm.yyyy'
    ]
]);
?>
		</div>
		<div class="col-md-6">
			<? 
			$arr = [
				'604800'=>'1 неделя',
				'1209600'=>'2 недели',
				'2629743'=>'1 месяц',
				];
			echo $form->field($model, 'deadtime')->dropDownList($arr);
			?>			
		</div>
	</div>

	<?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>    
    
    <?php Pjax::begin(); ?>
<label class="control-label">Тип опроса</label><br>
  <?
	//$span = array('fa-dot-circle-o','fa-check-square-o','fa-star-o','fa-calendar','fa-clock-o'); <i class='fa $span[$num_type]'></i> 
	foreach(Topic::getTypesArray() as $num_type => $name_type){
		$button_color = ($type == $num_type) ? 'warning' : 'success';
		echo Html::a("$name_type", ["topic/adding?key=1&type=$num_type"], ['class' => "btn type-btn btn-$button_color "]).' ';
	}
  ?>
<input type="hidden" id="topic-type" class="form-control" name="Topic[type]" value="<?= $type ?>">
<?
switch ($type) {
    case 2:
        echo "<br><br>";
        echo $form->field($model, 'trating')->radioList( ['["1","2","3","4","5"]' => 'до 5 звёзд', '["1","2","3","4","5","6","7","8","9","10"]' => 'до 10 звёзд'] );
        break;
    case 3:
	    echo '<br><br>';
	    echo DatePicker::widget([
	        'name' => 'tdate',
	        'type' => DatePicker::TYPE_INLINE,
	        'value' => date("d.m.yy"),
	        'type' => DatePicker::TYPE_INLINE,
	        'pluginOptions' => [
	            'format' => 'dd.mm.yyyy',
	            'multidate' => true
	        ]
	    ]);
	    echo '<br><br>';
        break;
    case 4:
		echo "<br><br>";
		$hours = array(
			'00:00'=>'00:00',
			'01:00'=>'01:00',
			'02:00'=>'02:00',
			'03:00'=>'03:00',
			'04:00'=>'04:00',
			'05:00'=>'05:00',
			'06:00'=>'06:00',
			'07:00'=>'07:00',
			'08:00'=>'08:00',
			'09:00'=>'09:00',
			'10:00'=>'10:00',
			'11:00'=>'11:00',
			'12:00'=>'12:00',
			'13:00'=>'13:00',
			'14:00'=>'14:00',
			'15:00'=>'15:00',
			'16:00'=>'16:00',
			'17:00'=>'17:00',
			'18:00'=>'18:00',
			'19:00'=>'19:00',
			'20:00'=>'20:00',
			'21:00'=>'21:00',
			'22:00'=>'22:00',
			'23:00'=>'23:00'
			);
        echo $form->field($model, 'ttime')->checkboxButtonGroup($hours, [
			'class' => 'btn-group-sm',
			'itemOptions' => ['labelOptions' => ['class' => 'btn btn-primary mybtn']]
		]);
		echo "<br><br>";
        break;
    default:
?>
<!-- -->  
	<br><br>
    <?= Html::a("Добавить поле ответа", ["topic/adding?key=$key&type=$type"], ['class' => 'btn btn-primary']) ?>
	<br><br>
	<div class="col-sm-12" style="max-height: 400px;overflow:scroll;">
	<? for ($i=0; $i < $key ; $i++) { ?>
		<div class="col-sm-8">
	    	<?= $form->field($model, "massiv[$i]")->textInput(['maxlength' => true]) ?> 
		</div>
		<div class="col-sm-4" style="padding-top: 23px;">
			<?	$buf = $i - 1;
				echo ($i+1 == $key && $key != 1 ) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ["topic/adding?key=".$buf."&type=".$type], ['class' => 'btn btn-primary']) : ''; 
			?>
		</div>
	<? } ?>
	</div>
<!-- -->
<?
		break;
}
?>		
    <?php Pjax::end(); ?>

<?= $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
	 		'options' => ['multiple' => true],
	 	]);
	?>
		  </div>
		  <div class="box-footer">
		    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
		  </div>
		</div>
	<?php ActiveForm::end(); ?>	
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
