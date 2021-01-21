<?php 
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$css= <<< CSS
.content-header {
	display:none;
}
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

if ($tik_tak > 3) { 
$this->title = Yii::t('app','NAV_MOBLOGIN');

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
<div class="user-default-signup">

    <div class="row">
        <div class="col-lg-5">
            <?= Html::beginForm('moblogin', 'post'); ?>

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
			<br>
			<input type="hidden" id="phonenumber" name="phonenumber" value="">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','BUTTON_MOBLOGIN'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
            </div>
            <?php Html::endForm(); ?>
        </div>
    </div>
</div> <? } ?>
  </div>
</div>
		</div>
		<div class="col-md-7">
		</div>
	</div>
</div>