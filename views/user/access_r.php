<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Module;
use app\models\Area;
use app\models\User;
use app\models\Mkd;




$css= <<< CSS
.hcs-thumbnail{
    width: 100px;    
    height: auto;
}

CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );

$user = User::findByID($_GET['id']);
if($user->id_org && $user->role != 'user') {
	$area = Area::findByID($user->id_org);
}else{
	$mkd = Mkd::findByID($user->id_a);
	$area = Area::findByID($mkd->id_a);
}
$module_id = json_decode($area->module);
$arr = Module::findByID($module_id[0]);
if($user->access_r) {
	$prev_access = array();
	$prev_access = json_decode($user->access_r, True);
}
?>

<style>
.under {
    padding-left: 15px;
    margin: 5px 0 15px;
    border-bottom: 1px solid 
    black;
}
[name="Tree"] label:after {content: '\A'; white-space: pre;} /* после label идёт как бы br */ 

/* если нужно скрыть дочерние чекбоксы, если на родителе не стоит флажок или :indeterminate*/
/*
[name="Tree"] fieldset fieldset {padding-left: 20px; display: none;}  
[name="Tree"] [type="checkbox"]:checked + label + fieldset,
[name="Tree"] [type="checkbox"]:indeterminate + label + fieldset {display: block;}
*/

</style>
 
 <div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
<?php $form = ActiveForm::begin([

    'action' => \yii\helpers\Url::to(['access_r', 'id' => $_GET['id']]),'options' => ['name' => 'Tree']

]); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Права доступа</h3>
    <div class="box-tools pull-right">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app','BUTTON_SAVE'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>
    </div>
  </div>
  <div class="box-body">

<fieldset>
	
<?
if($module_id){

foreach($module_id as $m_id) { 
	$module = Module::findByID($m_id);
	$sub_action_ru = explode(',',$module->sub_action_ru);
	$sub_action = explode(',',$module->sub_action);
	//var_dump($sub_action);
?>			
	<label  class="form-check-label"><input type="checkbox" class="form-check-input" <?= ($prev_access[$module->action] == $sub_action ) ? 'checked' : '' ?> value="<?= $module->action ?>"><?= $module->name ?></label>
	<fieldset class="under">
	  	<? foreach($sub_action_ru as $key => $ru_name) { ?>
	        <label  class="form-check-label"><input name="<?= $module->action.'_'.$sub_action[$key] ?>" type="checkbox" <?= ($prev_access[$module->action] && in_array($sub_action[$key],$prev_access[$module->action])) ? 'checked' : '' ?>  value="<?= $sub_action[$key] ?>" > <?= $ru_name ?></label>
	    <? } ?> 
	</fieldset>
<? } } ?>
</fieldset>
        
  </div>
</div>        
    <?php ActiveForm::end(); ?>
    
    
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
<script>
var t = document.forms.Tree;
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
