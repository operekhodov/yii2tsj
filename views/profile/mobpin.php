<?php
use yii\helpers\Html;
use yii\widgets\MaskedInput;

?>
<?php 
$css= <<< CSS
.example {
	margin: 4rem 0;
}
.example span{
	background-color: yellow;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>
<div class="user-default-signup">
 
    <div class="row">
        <div class="col-lg-12">
        	<?= Html::tag('h4', Html::encode(Yii::t('app','PF_TEXT'))) ?>
        	<h2 class="example">+7 (999) 999 <span style="background-color: yellow;">99 99</span></h2>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
            <?= Html::beginForm('changephone', 'post'); ?>
			<?= MaskedInput::widget([
			    'name' => 'pin',
			    'mask' => '9999',
			]);?>
			<?= Html::hiddenInput('phonenumber', $this->params['phonenumber']); ?>
			<?= Html::hiddenInput('checkphone', $this->params['checkphone']); ?><br>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','PF_PIN_BUTTON'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php Html::endForm(); ?>
        </div>
    </div>
</div>
