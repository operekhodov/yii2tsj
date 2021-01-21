<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Tasks;
use app\models\Mkd;
use app\models\Options;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;
use kartik\select2\Select2

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

<div class="tasks-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="box box-primary">
  <div class="box-header with-border">
	<h3 class="box-title"><?=$this->title ?></h3>
  </div>
<div class="box-body">
    <?= $form->field($model, 'status')->dropDownList(Tasks::getStatusesArray()) ?>

	<?= $form->field($model, 'tema')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'info')->textarea(['rows' => '6']) ?>
    <?= $form->field($model, 'type')->dropDownList(Tasks::getTypesArray()); ?>    
    <? if (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL) ) {
		if(\Yii::$app->user->identity->role == 'root') {
			echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkd());
		}else{
	    	echo $form->field($model, 'id_mkd')->dropDownList(Mkd::getAllMkdThisArea(\Yii::$app->user->identity->id_org));
		} 
	} ?>
    <?= $form->field($model, 'floor')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'porch')->textInput(['maxlength' => true]) ?>
    <?=
        $form->field($model, 'enddt')->widget(DatePicker::className(), [
		    'pluginOptions' => [
		        'autoclose'=>true,
		    ]        	
		]);
    ?>

<?php

    if (Yii::$app->user->can(AdminRbac::PERMISSION_DISPATCHER_PANEL) ) {
       echo $form->field($model, 'assignedto')->dropDownList(User::getWorkUser(\Yii::$app->user->identity->id_a));
    }

?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

<?    
    
$data = Options::getAllTags();
 
// Tagging support Multiple
$model->tags =  json_decode($model->tags); // initial value
echo $form->field($model, 'tags')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Теги ...', 'multiple' => true],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        'maximumInputLength' => 10
    ],
])->label('Теги задачи');

    
    ?>
</div>
  <div class="box-footer">
    <div class="form-group">
        <?= Html::submitButton( Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
</div>
    <?php ActiveForm::end(); ?>

</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>