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

$prev_access = Options::getLogsetting(Yii::$app->getUser()->identity->id_org,'m');
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
<?php $form = ActiveForm::begin([

    'action' => \yii\helpers\Url::to(['settings']),'options' => ['name' => 'Tree']

]); ?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Логи</h3>
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
