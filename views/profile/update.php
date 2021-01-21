<?
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\typeahead\Typeahead;
use app\models\Mkd;
use app\models\User;
use yii\bootstrap\Modal;
use lo\widgets\modal\ModalAjax;
use kartik\file\FileInput;

$this->title = $user->lname.' '.$user->fname.' '.$user->fio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_PROFILE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$adr = array_values(Mkd::getMkdData()); 

?>
 <?php 
$css= <<< CSS
.file-preview-image{
    width: 100%;    
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
			<div class="row">
				<div class="col-md-12">
					<?= ModalAjax::widget([
						'id' => 'createCompany',
						'header' => '<h3>'.Yii::t('app', 'PF_new_phone_title').'</h3>',
						'toggleButton' => [
							'label' => '',
							'tag' => 'span',
							'class' => 'glyphicon glyphicon-pencil btn btn-primary pull-right',
							'style' => 'margin: 1.7em 0 0;'
						],
						'url' => Url::to(['changephone']), // Ajax view with form to load
						'ajaxSubmit' => true,
					]);?>					
				</div>				
			</div>
		</div>
		<div class="col-md-7">
<?php $form = ActiveForm::begin(['action' => ['update'],'options' => ['method' => 'post']]) ?>			
			<div class="row">
				<div class="col-md-6">
					<label class="control-label" for="profileform-phonenumber"><?=Yii::t('app', 'PF_phonenumber')?></label>
		        	<?
						switch (strlen($user->phonenumber)) {
							case 10:
						        $mask = '+7 (999) 999-99-99';
						        break;
						    case 11:
						        $mask = '+9 (999) 999-99-99';
						        break;
						    case 12:
						        $mask = '+99 (999) 999-99-99';
						        break;
						    case 13:
						        $mask = '+999 (999) 999-99-99';
						        break;
						}        		
		        	?>					
					<?= $form->field($model, 'phonenumber')->widget(MaskedInput::className(),[
						'name' => 'phonenumber',
						'mask' => $mask,
						'options' => [
							'disabled'=> true,
							'value' => $user->phonenumber,
						],
						'clientOptions'=>[
							'clearIncomplete'=>true,
						]
					])->label(false);?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label class="control-label" for="signupform-name">ФИО</label>
					<input id="fullname" name="fullname" type="text" class="form-control tt-input"  value="<?= $user->lname.' '.$user->fname.' '.$user->fio ?>"/>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
					<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
					<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
					
					<script>
					    $("#fullname").suggestions({
					        token: "e8afc14f5a7dc3360057cb0ef61e277a090c3b61",
					        type: "NAME",
					        /* Вызывается, когда пользователь выбирает одну из подсказок */
					        onSelect: function(suggestion) {
								$('#lname').val(suggestion.data.surname); 
					            $('#fname').val(suggestion.data.name); 
					            $('#fio').val(suggestion.data.patronymic);
					        }
					    });
					</script>
					<br>
				</div>
			</div>
<? if(Yii::$app->user->identity->role == 'user' || Yii::$app->user->identity->role == 'government') { ?>			
			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'type')->dropDownList(User::getTypeArray(),['value'=>$user->type]) ?>
				</div>
				<div class="col-md-6">
					<?= $form->field($model, 'typeuse')->dropDownList(User::getTypeuseArray(),['value'=>$user->typeuse]) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<? /*= $form->field($model, 'adr')->widget(Typeahead::classname(), [
						'pluginOptions' => ['highlight'=>true],
						'options' => [
							'value' => ($user->locality) ? $user->locality.', '.$user->street.', '.$user->num_house : '',
						],
						'dataset' => [
							[
								'local' => $adr,
								'limit' => 10,
							]
						],
						'pluginEvents' => [
							"typeahead:select" => "function (e, datum) { $('#adr').val(datum); }",
						],
					]); */ ?>
					<label class="control-label" for="signupform-adr">Адрес</label>
					<input id="address" name="address" type="text" class="form-control tt-input" value="<?= $user->locality.', '.$user->street.', '.$user->num_house ?>"  />			
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
					<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
					<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
					
					<script>
					    $("#address").suggestions({
					        token: "e8afc14f5a7dc3360057cb0ef61e277a090c3b61",
					        type: "ADDRESS",
					        /* Вызывается, когда пользователь выбирает одну из подсказок */
					        onSelect: function(suggestion) {
					            $('#adr').val(suggestion.data.city+", "+suggestion.data.street+", "+suggestion.data.house); 
					            $('#nroom').val(suggestion.data.flat);
					        }
					    });
					</script>			
					<br>					
					<? /*<input type="hidden" id="adr" name="SignupForm[adr]" value="<?= ($user->locality) ? $user->locality.', '.$user->street.', '.$user->num_house : ''?>"> */ ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<?= $form->field($model, 'nexit')->textInput(['value'=>$user->nexit]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'nfloor')->textInput(['value'=>$user->nfloor]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'nroom')->textInput(['value'=>$user->nroom]) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'nid')->textInput(['value'=>$user->nid]) ?>
				</div>
			</div>			
			<div class="row">
				<div class="col-md-12">
					<?
					$img = json_decode($user->proof);
					if($img){ $img[0] = substr_replace($img[0], '/u',0 ,1); }
					echo $form->field($model, 'proof_img')->widget(FileInput::classname(), [
						'pluginOptions' => [
							'deleteUrl' => Url::toRoute(['profile/delfoto', 'id' => $user->id, 'type' => 'proof']),
							'initialPreview'=> ($img[0]) ? Html::img($img[0], ['class'=>'file-preview-image']) : ''
						]
					]);?> 					
				</div>
			</div>			
			<div class="row">
				<div class="col-md-4">
					<?= $form->field($model, 'space')->textInput(['value'=>$user->space]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'share')->textInput(['value'=>$user->share]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'ncad')->textInput(['value'=>$user->ncad]) ?>
				</div>
			</div>
<? } ?>			
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'email')->widget(MaskedInput::className(),[
						'clientOptions' => [
							'alias' =>  'email',
							'clearIncomplete'=>true,
						],
						'options' =>[
							'value'=> ($user->email) ? json_decode($user->email,true)['email'] : '' ]
					]) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">			
					<? function use_mail($mail) {
							if($mail){
								return (json_decode($mail,true)['use'] == 1) ? true : false ;
							}else{return true;}
						}
					?>
					<?= $form->field($model, 'notice')->checkbox([ 'value' => '1', 'checked ' => use_mail($user->email)])->label(Yii::t('app', 'PF_notice'));?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?
					$img = json_decode($user->foto);
					if($img){ $img[0] = substr_replace($img[0], '/u',0 ,1); }
					echo $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
						'pluginOptions' => [
							'deleteUrl' => Url::toRoute(['profile/delfoto', 'id' => $user->id, 'type' => 'foto']),
							'initialPreview'=> ($img[0]) ? Html::img($img[0], ['class'=>'file-preview-image']) : ''
						]
					]);?> 
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						
						<input type="hidden" id="lname" name="ProfileForm[lname]" value="<?= $user->lname ?>">
						<input type="hidden" id="fname" name="ProfileForm[fname]"  value="<?= $user->fname ?>">
						<input type="hidden" id="fio" name="ProfileForm[fio]"  value="<?= $user->fio ?>">
						<? if($user->role == 'user') { ?>
						<input type="hidden" id="adr" name="ProfileForm[adr]" value="<?= $user->locality.', '.$user->street.', '.$user->num_house ?>">
						<? }else{ ?>
						<input type="hidden" id="adr" name="ProfileForm[adr]" value="Город, Улица, Номер">
						<? } ?>
						
						<?= Html::submitButton(Yii::t('app','BUTTON_PF'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
					</div>
				</div>
			</div>
<?php ActiveForm::end(); ?>			
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
