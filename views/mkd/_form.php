<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Area;

/* @var $this yii\web\View */
/* @var $model app\models\Mkd */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
<div class="box box-primary">
	<div class="box-header with-border">
  
<div class="mkd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_a')->dropDownList(Area::getActiveArea(),['disabled' => !\Yii::$app->user->can('moder')]) ?>

	<label class="control-label" for="signupform-name">Адрес</label> 
	<input id="fullname" name="fullname" type="text" class="form-control tt-input" value="<?= ($model->city) ? $model->city.', '.$model->street.', '.$model->number_house : '' ?>" <?= (\Yii::$app->user->can('moder') ? '' : 'disabled' ) ?> "/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
	
	<script>
	    $("#fullname").suggestions({
	        token: "e8afc14f5a7dc3360057cb0ef61e277a090c3b61",
	        type: "ADDRESS",
	        /* Вызывается, когда пользователь выбирает одну из подсказок */
	        onSelect: function(suggestion) {
				$('#city').val(suggestion.data.city); 
	            $('#street').val(suggestion.data.street); 
	            $('#number_house').val(suggestion.data.house);
	            $('#geo').val('{"geo":["' + suggestion.data.geo_lat + '","' + suggestion.data.geo_lon + '"]}');
	        }
	    });
	</script>
<br>

	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'count_porch')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'count_floor')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'count_apartment')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'cadastral')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>
		</div>
	</div>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

	<input type="hidden" id="city" name="Mkd[city]" value="<?= ($model->city) ? $model->city : '' ?>">
	<input type="hidden" id="street" name="Mkd[street]" value="<?= ($model->street) ? $model->street : '' ?>">			
	<input type="hidden" id="number_house" name="Mkd[number_house]" value="<?= ($model->number_house) ? $model->number_house : '' ?>">
	<input type="hidden" id="geo" name="Mkd[geo]" value='<?= ($model->geo) ? $model->geo : '' ?>'>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>    

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>