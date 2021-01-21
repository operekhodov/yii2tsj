<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Indicat */

$this->title = Yii::t('app', 'indicat_update'). $model->created_at . ' '. User::findById($model->id_u)->lname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'indicat_all'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->created_at, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'indicat_update_word');
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $this->title ?></h3>
  </div>
<?php $form = ActiveForm::begin(); ?>
  <div class="box-body">

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'gvs')->widget(MaskedInput::className(), ['mask' => '99999',]) ?>			
		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'gvs1')->widget(MaskedInput::className(), ['mask' => '99999',]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'hvs')->widget(MaskedInput::className(), ['mask' => '999999',]) ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'hvs1')->widget(MaskedInput::className(), ['mask' => '999999',]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'elday')->widget(MaskedInput::className(), ['mask' => '999999',]) ?>
		</div>
		<div class="col-md-6">
			<?= $form->field($model, 'elnight')->widget(MaskedInput::className(), ['mask' => '999999',]) ?>			
		</div>
	</div>
</div>

  </div>
  <div class="box-footer">
    <?= Html::submitButton(Yii::t('app', 'indicat_send'), ['class' => 'btn btn-success']) ?>
  </div>
<?php ActiveForm::end(); ?>
</div>

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>