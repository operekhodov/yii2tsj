<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

$css= <<< CSS
.country-phone{
	display: -webkit-box;
	border: none;
}
.country-phone-selector{
	padding-right: 5px;
	padding-top: 3px;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );

$this->registerCssFile('@web/phonecode/phonecode.css');
$this->registerJsFile('@web/phonecode/jquery-1.11.0.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
$this->registerJsFile('@web/phonecode/jquery-ui-1.10.4.custom.min.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
$this->registerJsFile('@web/phonecode/counties.js',['depends' => [\yii\web\JqueryAsset::className()]]); 
$this->registerJsFile('@web/phonecode/phonecode.js',['depends' => [\yii\web\JqueryAsset::className()]]); 

$this->registerJs(
    "
    $(function(){
        $('#tel').phonecode({
            preferCo: 'ru'
        });
    });
	",
    \yii\web\View::POS_END,
    'add_county_code'
);

$this->title = Yii::t('app', 'LF_Title');
if($tik_tak > 3) {
$html = "
<div class='form-group'>
	<div class='row'>
		<div class='col-lg-2'>
		        ".Html::a(Yii::t('app', 'LF_button_pass2mail'), ['pass2mail'], ['class' => 'btn btn-info'])."
		</div>
	</div>
</div>
<div class='form-group'>
	<div class='row'>
		<div class='col-lg-2'>
		
				".Html::beginForm('moblogin', 'post')."
			    <input type='hidden' name='tik_tak' value='$tik_tak'>
			    ".Html::submitButton(Yii::t('app','LF_button_call2phone'), ['class' => 'btn btn-info', 'name' => 'signup-button'])."
			    ".Html::endForm()."
		
		</div>
	</div>
</div>
";
}else{
$html = "
<div class='form-group'>
	<div class='row'>
		<div class='col-lg-2'>".
			Html::a(Yii::t('app', 'LF_button_pass2mail'), ['pass2mail'], ['class' => 'btn btn-info'])."
		</div>
	</div>
</div>";	
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-4">
			<div class="box box-primary">
			  <div class="box-header with-border">
			    <h3 class="box-title"><?=$this->title?></h3>
			  </div>
			  <div class="box-body">
				<div class="site-login">
				    <?php $form = ActiveForm::begin([
				        'id' => 'login-form',
				        'layout' => 'horizontal',
				        'fieldConfig' => [
				            'template' => "<div class=\"col-lg-6\">{label}\n{input}</div>\n<div class=\"col-lg-6\">{error}\n</div>",
				            'labelOptions' => ['class' => 'control-label'],
				        ],
				    ]); ?>
						<input type="hidden" name="tik_tak" value="<?= $tik_tak ?>">
						<?//= $form->field($model, 'phonenumber')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+7 (999) 999-99-99',]) ?>
						<div class="row">
							<div class="col-lg-6">
								<label class="control-label">Номер телефона</label>
								<?= MaskedInput::widget([
							    'class'		=> "form-control",
							    'name'		=> 'tel',
							    'id'		=> 'tel',
							    'mask'		=> '(999) 999-99-99',
								'options' => [
									'oninput' =>'$(\'#phonenumber\').val($(\'.country-phone-selected\').text()+$(this).val());',
									'class'		=> "form-control",
								]
							]);?>
							</div>
							<div class="col-lg-6"></div>
						</div>
				        <?= $form->field($model, 'password')->passwordInput() ?>
				        <input type="hidden" id="phonenumber" name="LoginForm[phonenumber]" value="">
				        <div class="form-group">
				        	<div class='col-lg-2'>
				        	<?= Html::submitButton(Yii::t('app', 'LF_button_form'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
							</div>
				        </div>
				    <?php ActiveForm::end(); ?>
				</div>
			  </div>
			  <div class="box-footer">
				<?= $html?>
			  </div>
			</div>
		</div>
		<div class="col-md-7">
		</div>
	</div>
</div>
