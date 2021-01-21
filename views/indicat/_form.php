<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\User;
use app\models\Mkd;

$gvs = intval($last_indicat->gvs);
$gvs1 = intval($last_indicat->gvs1);
$hvs = intval($last_indicat->hvs);
$hvs1 = intval($last_indicat->hvs1);
$elday = intval($last_indicat->elday);
$elnight = intval($last_indicat->elnight);
$this->registerJs(
    "
	$('#indicat_GVS').on('keyup',function(){
		$('#indicat_GVS_span').text($(this).val() - $gvs + ' м3');
	});
	$('#indicat_GVS1').on('keyup',function(){
		$('#indicat_GVS1_span').text($(this).val() - $gvs1 + ' м3');
	});
	$('#indicat_HVS').on('keyup',function(){
		$('#indicat_HVS_span').text($(this).val() - $hvs + ' м3');
	});
	$('#indicat_HVS1').on('keyup',function(){
		$('#indicat_HVS1_span').text($(this).val() - $hvs1 + ' м3');
	});
	$('#indicat_ELDAY').on('keyup',function(){
		$('#indicat_ELDAY_span').text($(this).val() - $elday + ' кВт.ч');
	});
	$('#indicat_ELNIGHT').on('keyup',function(){
		$('#indicat_ELNIGHT_span').text($(this).val() - $elnight + ' кВт.ч');
	});

	",
    \yii\web\View::POS_END,
    'my-button-handle12r'
);
$css= <<< CSS
.form-group {
    margin-bottom: 0px;
}
input.form-control {
	width: auto;
	margin: 0 auto;
}
td,th {
	vertical-align:middle;
	text-align:center;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
<?php $form = ActiveForm::begin([
        'fieldConfig' => [
             'template' => "{input}",
        ],
    ]); ?>    
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= Yii::t('app', 'indicat_create_title') ?></h3>
    <div class="box-tools pull-right">

			<? 
			if( Yii::$app->getUser()->identity->role == 'root' ){
				echo '<label class="control-label" for="id_u">Передать показания за: </label><div class="form-group">';
				echo Html::dropDownList('id_u', null, User::getActiveUser(), ['class' => 'form-control']);
				echo '</div>';
			}elseif( Yii::$app->getUser()->identity->role == 'government' ) {
				echo '<label class="control-label" for="id_u">Передать показания за: </label><div class="form-group">';
				echo Html::dropDownList('id_u', null, User::getAllUsersNameThisMkd(Yii::$app->getUser()->identity->id_a), ['class' => 'form-control']);
				echo '</div>';
			}elseif( Yii::$app->getUser()->identity->role != 'user' ){
				echo '<label class="control-label" for="id_u">Передать показания за: </label><div class="form-group">';
				echo Html::dropDownList('id_u', null, User::getAllUsersNameThisArea(Yii::$app->getUser()->identity->id_org), ['class' => 'form-control']);
				echo '</div>';	
			}
			?>

    </div>

  </div>

  <div class="box-body">

 	
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th></th>
			<th><?= Yii::t('app', 'indicat_now') ?></th>
			<th><?= Yii::t('app', 'indicat_pred').' '.$last_indicat->created_at ?></th>
			<th><?= Yii::t('app', 'indicat_rashod') ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_GVS') ?></label></td>
			<td>
				<?= $form->
						field($model, 'gvs')->
							widget(MaskedInput::className(),  
								[
									'mask' => '99999', 
									'options'=>
										[
											'value'=>'',
											'size'=>6,
											'id'=>'indicat_GVS',
											'placeholder'=> $last_indicat->gvs
										]
								]) ?>
			</td>
			<td><?= Yii::t('app', 'indicat_GVS').' - '.$last_indicat->gvs.' м3' ?></td>
			<td><span id='indicat_GVS_span'></span></td>
		</tr>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_GVS1') ?></label></td>
			<td><?= $form->field($model, 'gvs1')->widget(MaskedInput::className(),  ['mask' => '99999', 'options'=>['value'=>'','size'=>6,'id'=>'indicat_GVS1','placeholder'=> $last_indicat->gvs1]]) ?></td>
			<td><?= Yii::t('app', 'indicat_GVS1').' - '.$last_indicat->gvs1.' м3' ?></td>
			<td><span id='indicat_GVS1_span'></span></td>
		</tr>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_HVS') ?></label></td>
			<td><?= $form->field($model, 'hvs')->widget(MaskedInput::className(),  ['mask' => '999999', 'options'=>['value'=>'','size'=>6,'id'=>'indicat_HVS','placeholder'=> $last_indicat->hvs]]) ?></td>
			<td><?= Yii::t('app', 'indicat_HVS').' - '.$last_indicat->hvs.' м3' ?></td>
			<td><span id='indicat_HVS_span'></span></td>
		</tr>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_HVS1') ?></label></td>
			<td><?= $form->field($model, 'hvs1')->widget(MaskedInput::className(), ['mask' => '999999', 'options'=>['value'=>'','size'=>6,'id'=>'indicat_HVS1','placeholder'=> $last_indicat->hvs1]]) ?></td>
			<td><?= Yii::t('app', 'indicat_HVS1').' - '.$last_indicat->hvs1.' м3' ?></td>
			<td><span id='indicat_HVS1_span'></span></td>
		</tr>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_ELDAY') ?></label></td>
			<td><?= $form->field($model, 'elday')->widget(MaskedInput::className(),  ['mask' => '999999', 'options'=>['value'=>'','size'=>6,'id'=>'indicat_ELDAY','placeholder'=> $last_indicat->elday]]) ?></td>
			<td><?= Yii::t('app', 'indicat_ELDAY').' - '.$last_indicat->elday.' кВт.ч' ?></td>
			<td><span id='indicat_ELDAY_span'></span></td>
		</tr>
		<tr>
			<td><label class="control-label"><?= Yii::t('app', 'indicat_ELNIGHT') ?></label></td>
			<td><?= $form->field($model, 'elnight')->widget(MaskedInput::className(),  ['mask' => '999999', 'options'=>['value'=>'','size'=>6,'id'=>'indicat_ELNIGHT','placeholder'=> $last_indicat->elnight]]) ?></td>
			<td><?= Yii::t('app', 'indicat_ELNIGHT').' - '.$last_indicat->elnight.' кВт.ч' ?></td>
			<td><span id='indicat_ELNIGHT_span'></span></td>
		</tr>		
	</tbody>
</table>


  </div>
  <div class="box-footer">
	<?= Html::submitButton(Yii::t('app','indicat_send'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
  </div>
</div>

    <?php ActiveForm::end(); ?>


   

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>