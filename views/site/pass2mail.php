<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

$css= <<< CSS
.content-header {
	display:none;
}

CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );

$this->title = Yii::t('app','P2M_TITLE');
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
			        <div class="col-lg-7">
			            <?= Html::beginForm('pass2mail', 'post'); ?>
						<label class="control-label"><?=Yii::t('app','P2M_PHONE')?></label>
						<?= MaskedInput::widget([
						    'name' => 'phonenumber',
						    'mask' => '+7 (999) 999-99-99',
						]);?><br>
						<label class="control-label"><?=Yii::t('app','P2M_MAIL')?></label>
						<?= MaskedInput::widget([
						    'name' => 'email',
						    'clientOptions' => [
						        'alias' =>  'email'
						    ],
						]);?>
						<br>
			            <div class="form-group">
			                <?= Html::submitButton(Yii::t('app','P2M_BUTTON'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
			            </div>
			            <?php Html::endForm(); ?>
			        </div>
			    </div>
			</div>
			  </div>
			</div>
		</div>
		<div class="col-md-7">
		</div>
	</div>
</div>