<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\typeahead\Typeahead;
use app\models\Mkd;

$this->title = Yii::t('app','SUF_SIGNUP');
$adr = array_values(Mkd::getMkdData()); 
$css= <<< CSS
.content-header {
	display:none;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
<div class="col-md-4">
	
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title?></h3>
  </div>
  <div class="box-body">
	<div class="user-default-signup">
		<?php $form = ActiveForm::begin(['action' => ['signup'],'options' => ['method' => 'post']]) ?>
	
		<div class="row">		
			<div class="col-lg-6">
				<?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>
			</div>
			<div class="col-lg-6">
				<?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12 adr">
				<?/* = $form->field($model, 'adr')->widget(Typeahead::classname(), [
				    'pluginOptions' => ['highlight'=>true],
				    'dataset' => [
				        [
				            'local' => $adr,
				            'limit' => 10
				        ]
				    ],
				    'pluginEvents' => [
				        "typeahead:select" => "function (e, datum) { $('#adr').val(datum); }",
				    	],
				]); */ ?>
				<label class="control-label" for="signupform-adr">Адрес</label>
				<input id="address" name="address" type="text" class="form-control tt-input" />			
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
				<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
				
				<script>
				    $("#address").suggestions({
				        token: "e8afc14f5a7dc3360057cb0ef61e277a090c3b61",
				        type: "ADDRESS",
				        /* Вызывается, когда пользователь выбирает одну из подсказок */
				        onSelect: function(suggestion) {
				            $('#adr').val(suggestion.data.city+", "+suggestion.data.street+", "+suggestion.data.house); 
				            $('#nroom').val(suggestion.data.flat);
				            $('#geo').val('{"geo":["' + suggestion.data.geo_lat + '","' + suggestion.data.geo_lon + '"]}');
				        }
				    });
				</script>			
	<br>
			</div>
		</div>
	    <div class="row">
	        <div class="col-lg-6">
	        	<?
	        	
					switch (strlen($model->phonenumber)) {
					    case 11:
					        $mask = '+9 (999) 999-99-99';
					        break;
					    case 12:
					        $mask = '+99 (999) 999-99-99';
					        break;
					    case 13:
					        $mask = '+999 (999) 999-99-99';
					        break;
					}        		
	        	?>
				<?= $form->field($model, 'phonenumber')->widget(MaskedInput::className(),[
				         'name' => 'phonenumber',
				         'mask' => $mask,
				         'options' => [
				         	'readonly'=> true,
				         	'value' => $model->phonenumber,
				         ],
				         'clientOptions'=>[
							'clearIncomplete'=>true,
						 ]
				]);?>
			</div>
			<div class="col-lg-6" style="padding: 1.7em 0 0;">
				<?= Html::a(Yii::t('app', 'SUF_change_phone'), ['checkphone'], ['class' => 'btn btn-info']) ?>
			</div>
		</div>
	    <div class="row">
	        <div class="col-lg-6 password">
				<?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'id'=>'password']) ?>
			</div>
		</div>	
	    <div class="row">
	        <div class="col-lg-6 password">
				<?= $form->field($model, 'repeatPass')->passwordInput(['maxlength' => true,'id'=>'password2']) ?>
			</div>
			<div class="col-lg-6" style="padding: 2em 0 0;">
				<?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> <?= Html::label('Показать пароль', 'reveal-password') ?>
				<?php $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#password').attr('type',this.checked?'text':'password');} )");?>
				<?php $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#password2').attr('type',this.checked?'text':'password');} )");?>
			</div>	
		</div>
				<input type="hidden" id="adr" name="SignupForm[adr]" value="">
				<input type="hidden" id="nroom" name="SignupForm[nroom]" value="">			
				<input type="hidden" id="fio" name="SignupForm[fio]"  value="">
				<input type="hidden" id="geo" name="SignupForm[geo]" value=''>
				
		<div class="row">
			<div class="col-md-6">
		        <div class="form-group">
		        	<?= Html::submitButton(Yii::t('app','BUTTON_SIGNUP'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
		        </div>
	        </div>
	        <?php ActiveForm::end(); ?>
	    </div>
	</div>
  </div>
  <div class="box-footer">
    		<p>
    			Нажав кнопку "Регистрация" Вы подтверждаете своё согласие с <a href="#">"Пользовательским соглашением"</a> и <a href="#">"Политикой конфиденциальности"</a>.
    		</p>
  </div>
</div>
</div>