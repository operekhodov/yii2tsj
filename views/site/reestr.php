<?
$this->title = 'Реестр';
?>
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
<div class="col-md-12">
	
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title?></h3>
  </div>
  <div class="box-body">
	<div class="user-default-signup">

		<div class="row">
			<div class="col-md-12 adr">

				<label class="control-label" for="signupform-adr">Адрес</label>
				<input id="address" name="address" type="text" class="form-control tt-input" />			
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
				<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
				
				<script>
				    $("#address").suggestions({
				        token: "e8afc14f5a7dc3360057cb0ef61e277a090c3b61",
				        type: "party",
				        /* Вызывается, когда пользователь выбирает одну из подсказок */
				        onSelect: function(suggestion) {

							$('#adr1').val(suggestion.data.name.full_with_opf); 
							$('#adr2').val(suggestion.data.okved); 
							$('#adr3').val(suggestion.data.address.data.source); 
							$('#adr5').val(suggestion.data.state.status); 
				        }
				    });
				</script>			
	<br>
			</div>
		</div>
		<br><br><br><br><br><br><br><br>
	    <div class="row">
	    	<div class="col-md-12 adr">
				<input type="text" class="form-control tt-input" id="adr1" name="SignupForm[adr1]" value=""><br>
				<input type="text" class="form-control tt-input" id="adr2" name="SignupForm[adr2]" value=""><br>
				<input type="text" class="form-control tt-input" id="adr3" name="SignupForm[adr3]" value=""><br>
				<input type="text" class="form-control tt-input" id="adr5" name="SignupForm[adr5]" value=""><br>
			</div>
		</div>

	</div>
  </div>
  <div class="box-footer">

  </div>
</div>
</div>