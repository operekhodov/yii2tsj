<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Module;
use app\models\Area;
use app\models\User;
use app\models\Mkd;
use app\models\Options;

$css= <<< CSS
.hcs-thumbnail{
    width: 100px;    
    height: auto;
}

CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );


$area = Area::findByID(Yii::$app->getUser()->identity->id_org);
$module_id = json_decode($area->module,true);

$access_a = Options::getTemp_access(Yii::$app->getUser()->identity->id_org,'access_a','m');
$access_d = Options::getTemp_access(Yii::$app->getUser()->identity->id_org,'access_d','m');
$access_g = Options::getTemp_access(Yii::$app->getUser()->identity->id_org,'access_g','m');
$access_u = Options::getTemp_access(Yii::$app->getUser()->identity->id_org,'access_u','m');

?>

<style>
.under {
    padding-left: 15px;
    margin: 5px 0 15px;
    border-bottom: 1px solid 
    black;
}
[name="Tree"] label:after {content: '\A'; white-space: pre;} /* после label идёт как бы br */ 
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
			<div class="box box-primary">
				<div class="tabbable" id="tabs-712944">
					<div class="box-header with-border">
						<ul class="nav nav-tabs">
						<li class="nav-item active">
							<a class="nav-link active" href="#tab1" data-toggle="tab"><h3 class="title_chart box-title">Aдминистратор</h3></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#tab2" data-toggle="tab"><h3 class="title_chart box-title">Диспетчер</h3></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#tab3" data-toggle="tab"><h3 class="title_chart box-title">Правление</h3></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#tab4" data-toggle="tab"><h3 class="title_chart box-title">Пользователь</h3></a>
						</li>								
					</ul>
					</div>
				</div>
				<div class="box-body">
					<div class="tab-content">
					<div class="tab-pane active" id="tab1">
<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['pattern_ar']),'options' => ['name' => 'Tree1']
]); ?>
  <div class="box-body">

<fieldset>
	
<?
if($module_id){

foreach($module_id as $m_id) { 
	$module = Module::findByID($m_id);
	$sub_action_ru = explode(',',$module->sub_action_ru);
	$sub_action = explode(',',$module->sub_action);
?>			
	<label  class="form-check-label"><input type="checkbox" class="form-check-input" <?= ($access_a[$module->action] == $sub_action ) ? 'checked' : '' ?> value="<?= $module->action ?>"><?= $module->name ?></label>
	<fieldset class="under">
	  	<? foreach($sub_action_ru as $key => $ru_name) { ?>
	        <label  class="form-check-label"><input name="<?= $module->action.'_'.$sub_action[$key] ?>" type="checkbox" <?= ($access_a[$module->action] && in_array($sub_action[$key],$access_a[$module->action])) ? 'checked' : '' ?>  value="<?= $sub_action[$key] ?>" > <?= $ru_name ?></label>
	    <? } ?> 
	</fieldset>
<? } } ?>
</fieldset>
        
  </div>
  <div class="box-footer">
  	<input type="hidden" id="lname" name="name" value="access_a">
    <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
  </div>  
<?php ActiveForm::end(); ?>
<script>
var t = document.forms.Tree1;
var fieldset = [].filter.call(t.querySelectorAll('fieldset'), function(element) {return element;});
fieldset.forEach(function(eFieldset) {
  var main = [].filter.call(t.querySelectorAll('[type="checkbox"]'), function(element) {return element.parentNode.nextElementSibling == eFieldset;});
  main.forEach(function(eMain) {
    var all = eFieldset.querySelectorAll('[type="checkbox"]');
    eFieldset.onchange = function() {
      var allChecked = eFieldset.querySelectorAll('[type="checkbox"]:checked').length;
      eMain.checked = allChecked == all.length;
      eMain.indeterminate = allChecked > 0 && allChecked < all.length;
    }
    eMain.onclick = function() {
      for(var i=0; i<all.length; i++)
        all[i].checked = this.checked;
    }
  });
});
</script>
					</div>
					<div class="tab-pane" id="tab2">
<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['pattern_ar']),'options' => ['name' => 'Tree2']
]); ?>
  <div class="box-body">

<fieldset>
	
<?
if($module_id){

foreach($module_id as $m_id) { 
	$module = Module::findByID($m_id);
	$sub_action_ru = explode(',',$module->sub_action_ru);
	$sub_action = explode(',',$module->sub_action);
?>			
	<label  class="form-check-label"><input type="checkbox" class="form-check-input" <?= ($access_d[$module->action] == $sub_action ) ? 'checked' : '' ?> value="<?= $module->action ?>"><?= $module->name ?></label>
	<fieldset class="under">
	  	<? foreach($sub_action_ru as $key => $ru_name) { ?>
	        <label  class="form-check-label"><input name="<?= $module->action.'_'.$sub_action[$key] ?>" type="checkbox" <?= ($access_d[$module->action] && in_array($sub_action[$key],$access_d[$module->action])) ? 'checked' : '' ?>  value="<?= $sub_action[$key] ?>" > <?= $ru_name ?></label>
	    <? } ?> 
	</fieldset>
<? } } ?>
</fieldset>
        
  </div>
  <div class="box-footer">
  	<input type="hidden" id="lname" name="name" value="access_d">
    <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
  </div>  
<?php ActiveForm::end(); ?>
<script>
var t = document.forms.Tree2;
var fieldset = [].filter.call(t.querySelectorAll('fieldset'), function(element) {return element;});
fieldset.forEach(function(eFieldset) {
  var main = [].filter.call(t.querySelectorAll('[type="checkbox"]'), function(element) {return element.parentNode.nextElementSibling == eFieldset;});
  main.forEach(function(eMain) {
    var all = eFieldset.querySelectorAll('[type="checkbox"]');
    eFieldset.onchange = function() {
      var allChecked = eFieldset.querySelectorAll('[type="checkbox"]:checked').length;
      eMain.checked = allChecked == all.length;
      eMain.indeterminate = allChecked > 0 && allChecked < all.length;
    }
    eMain.onclick = function() {
      for(var i=0; i<all.length; i++)
        all[i].checked = this.checked;
    }
  });
});
</script>
					</div>
					<div class="tab-pane" id="tab3">
<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['pattern_ar']),'options' => ['name' => 'Tree3']
]); ?>
  <div class="box-body">

<fieldset>
	
<?
if($module_id){

foreach($module_id as $m_id) { 
	$module = Module::findByID($m_id);
	$sub_action_ru = explode(',',$module->sub_action_ru);
	$sub_action = explode(',',$module->sub_action);
?>			
	<label  class="form-check-label"><input type="checkbox" class="form-check-input" <?= ($access_g[$module->action] == $sub_action ) ? 'checked' : '' ?> value="<?= $module->action ?>"><?= $module->name ?></label>
	<fieldset class="under">
	  	<? foreach($sub_action_ru as $key => $ru_name) { ?>
	        <label  class="form-check-label"><input name="<?= $module->action.'_'.$sub_action[$key] ?>" type="checkbox" <?= ($access_g[$module->action] && in_array($sub_action[$key],$access_g[$module->action])) ? 'checked' : '' ?>  value="<?= $sub_action[$key] ?>" > <?= $ru_name ?></label>
	    <? } ?> 
	</fieldset>
<? } } ?>
</fieldset>
        
  </div>
  <div class="box-footer">
  	<input type="hidden" id="lname" name="name" value="access_g">
    <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
  </div>  
<?php ActiveForm::end(); ?>
<script>
var t = document.forms.Tree3;
var fieldset = [].filter.call(t.querySelectorAll('fieldset'), function(element) {return element;});
fieldset.forEach(function(eFieldset) {
  var main = [].filter.call(t.querySelectorAll('[type="checkbox"]'), function(element) {return element.parentNode.nextElementSibling == eFieldset;});
  main.forEach(function(eMain) {
    var all = eFieldset.querySelectorAll('[type="checkbox"]');
    eFieldset.onchange = function() {
      var allChecked = eFieldset.querySelectorAll('[type="checkbox"]:checked').length;
      eMain.checked = allChecked == all.length;
      eMain.indeterminate = allChecked > 0 && allChecked < all.length;
    }
    eMain.onclick = function() {
      for(var i=0; i<all.length; i++)
        all[i].checked = this.checked;
    }
  });
});
</script>
					</div>
					<div class="tab-pane" id="tab4">
<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['pattern_ar']),'options' => ['name' => 'Tree4']
]); ?>
  <div class="box-body">

<fieldset>
	
<?
if($module_id){

foreach($module_id as $m_id) { 
	$module = Module::findByID($m_id);
	$sub_action_ru = explode(',',$module->sub_action_ru);
	$sub_action = explode(',',$module->sub_action);
?>			
	<label  class="form-check-label"><input type="checkbox" class="form-check-input" <?= ($access_u[$module->action] == $sub_action ) ? 'checked' : '' ?> value="<?= $module->action ?>"><?= $module->name ?></label>
	<fieldset class="under">
	  	<? foreach($sub_action_ru as $key => $ru_name) { ?>
	        <label  class="form-check-label"><input name="<?= $module->action.'_'.$sub_action[$key] ?>" type="checkbox" <?= ($access_u[$module->action] && in_array($sub_action[$key],$access_u[$module->action])) ? 'checked' : '' ?>  value="<?= $sub_action[$key] ?>" > <?= $ru_name ?></label>
	    <? } ?> 
	</fieldset>
<? } } ?>
</fieldset>
        
  </div>
  <div class="box-footer">
  	<input type="hidden" id="lname" name="name" value="access_u">
    <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
  </div>  
<?php ActiveForm::end(); ?>
<script>
var t = document.forms.Tree4;
var fieldset = [].filter.call(t.querySelectorAll('fieldset'), function(element) {return element;});
fieldset.forEach(function(eFieldset) {
  var main = [].filter.call(t.querySelectorAll('[type="checkbox"]'), function(element) {return element.parentNode.nextElementSibling == eFieldset;});
  main.forEach(function(eMain) {
    var all = eFieldset.querySelectorAll('[type="checkbox"]');
    eFieldset.onchange = function() {
      var allChecked = eFieldset.querySelectorAll('[type="checkbox"]:checked').length;
      eMain.checked = allChecked == all.length;
      eMain.indeterminate = allChecked > 0 && allChecked < all.length;
    }
    eMain.onclick = function() {
      for(var i=0; i<all.length; i++)
        all[i].checked = this.checked;
    }
  });
});
</script>
					</div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>

