<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Add_personal */
/* @var $form ActiveForm */
?>
<div class="mkd-add_personal">

<?php $form = ActiveForm::begin([

    'action' => \yii\helpers\Url::to(['add_personal', 'id_mkd' => $id_mkd, 'id_org' => $id_org]),

]); ?>

<input type="hidden" id="id_mkd" name="Add_personal[id_mkd]" value="<?= $id_mkd ?>">
<input type="hidden" id="id_org" name="Add_personal[id_org]" value="<?= $id_org ?>">

<div class="row">
	<div class="col-md-8">
		<?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-5">
		<?= $form->field($model, 'phonenumber')->widget(MaskedInput::className(),[
				         'name' => 'phonenumber',
				         'mask' => '+ 9{1,3} (999) 999-99-99',
				         'options' => [
				         	'readonly'=> false,
				         ],
				         'clientOptions'=>[
							'clearIncomplete'=>true,
						 ]
				]);?>
	</div>
	<div class="col-md-3">
		<?= $form->field($model, 'role')->dropDownList(User::getRolesPersonalArray()) ?>
	</div>	
</div>

<div class="row">
    <div class="col-lg-5 password">
		<?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'id'=>'password']) ?>
	</div>
</div>	
<div class="row">
    <div class="col-lg-5 password">
		<?= $form->field($model, 'repeatPass')->passwordInput(['maxlength' => true,'id'=>'password2']) ?>
	</div>
	<div class="col-lg-7" style="padding: 2em 0 0;">
		<?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password']) ?> <?= Html::label('Показать пароль', 'reveal-password') ?>
		<?php $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#password').attr('type',this.checked?'text':'password');} )");?>
		<?php $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#password2').attr('type',this.checked?'text':'password');} )");?>
	</div>	
</div>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- mkd-add_personal -->
