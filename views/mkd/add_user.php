<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Mkd;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\SignupForm */
 
$this->title = Yii::t('app','SUF_SIGNUP');

?>
<div class="container-fluid">
	<div class="row">

		<div class="col-md-10">

<?php $form = ActiveForm::begin([

    'action' => \yii\helpers\Url::to(['add_user', 'id_mkd' => $id_mkd, 'role' => $role]),

]); ?>

<input type="hidden" id="id_mkd" name="Add_user[id_mkd]" value="<?= $id_mkd ?>">



<div class="row">
	<div class="col-md-7">
		<?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>
	</div>
	<div class="col-md-5">
		<?= $form->field($model, 'nroom')->textInput(['maxlength' => true]) ?>
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
</div>

<? if($role == 'user') { ?>
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
<? }else{ ?>
<div class="row">
    <div class="col-lg-5 password">
		<?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'id'=>'password3']) ?>
	</div>
</div>	
<div class="row">
    <div class="col-lg-5 password">
		<?= $form->field($model, 'repeatPass')->passwordInput(['maxlength' => true,'id'=>'password4']) ?>
	</div>
	<div class="col-lg-7" style="padding: 2em 0 0;">
		<?= Html::checkbox('reveal-password', false, ['id' => 'reveal-password1']) ?> <?= Html::label('Показать пароль', 'reveal-password1') ?>
		<?php $this->registerJs("jQuery('#reveal-password1').change(function(){jQuery('#password3').attr('type',this.checked?'text':'password');} )");?>
		<?php $this->registerJs("jQuery('#reveal-password1').change(function(){jQuery('#password4').attr('type',this.checked?'text':'password');} )");?>
	</div>	
</div>
<? } ?>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
        	<?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>