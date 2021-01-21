<?php
 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
 
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\ChangePasswordForm */
 
$this->title = Yii::t('app', 'TITLE_PASSWORD_CHANGE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_PROFILE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
			<div class="user-profile-password-change">
 
    <div class="user-form">
 
        <?php $form = ActiveForm::begin(); ?>
 
        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>
 
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-primary']) ?>
        </div>
 
        <?php ActiveForm::end(); ?>
 
    </div>
 
</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>