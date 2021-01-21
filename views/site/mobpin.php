<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
 
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\SignupForm */
 $this->title = Yii::t('app','CPF_PIN_TITLE');
?>
<?php 
$css= <<< CSS
.example {
	margin: 4rem 0;
}
.example span{
	background-color: yellow;
}
a.disabled {
pointer-events: none; /* делаем элемент неактивным для взаимодействия */
cursor: default; /*  курсор в виде стрелки */
color: #888;/* цвет текста серый */
}
.content-header {
	display:none;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-4">
			
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?=$this->title?></h3>
  </div>
  <div class="box-body">
<div class="user-default-signup">
 
    <div class="row">
        <div class="col-lg-12">
        	<?= Html::tag('h4', Html::encode(Yii::t('app','CPF_TEXT'))) ?>
        	<h2 class="example">+7 (999) 999 <span style="background-color: yellow;">99 99</span></h2>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-4">
            <?= Html::beginForm('mobpin', 'post'); ?>
			<?= MaskedInput::widget([
			    'name' => 'pin',
			    'mask' => '9999',
			]); ?>
			<?= Html::hiddenInput('phonenumber', $this->params['phonenumber']); ?>
			<?= Html::hiddenInput('checkphone', $this->params['checkphone']); ?><br>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app','CPF_PIN_BUTTON'), ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
            </div>
            <?= Html::endForm(); ?>
        </div>
    </div>

</div>
  </div>
  <div class="box-footer">
	<div class="row">
		<div class="col-lg-6">
            <?= Html::a(Yii::t('app','CPF_OTHER_PHONE'), ['checkphone'],['class' => 'btn btn-primary']);?>
        </div>
        <div class="col-lg-6">
			<?= Html::beginForm('checkphone', 'post',['id'=>'2call','name'=>'2call']); ?>
			<?= Html::hiddenInput('phonenumber', $this->params['phonenumber']); ?>
			<?= Html::hiddenInput('checkphone', null); ?>
			<?= Html::submitButton(Yii::t('app',''), ['class' => 'btn btn-default', 'id' => 'repeat_call', 'disabled'=>'disabled']) ?>
			<?php Html::endForm(); ?>
			
			<script>
			let timer; // пока пустая переменная
			let x =20; // стартовое значение обратного отсчета
			countdown(); // вызов функции
			function countdown(){  // функция обратного отсчета
			  document.getElementById('repeat_call').innerHTML = '<?= Yii::t('app','CPF_repeat_call') ?> ('+x+')';
			  x--; // уменьшаем число на единицу
			  if (x<0){
			    clearTimeout(timer); // таймер остановится на нуле
			    document.getElementById('repeat_call').classList.remove("btn-default");
			    document.getElementById('repeat_call').classList.add("btn-primary");
			    document.getElementById('repeat_call').disabled = false;
			  }
			  else {
			    timer = setTimeout(countdown, 1000);
			    if(document.getElementById('repeat_call').classList.contains("btn-default")){
			    	document.getElementById('repeat_call').classList.add("btn-default");
			    	document.getElementById('repeat_call').disabled = true;
			    }
			  }
			}
			</script>
			        	
        </div>        
    </div>
  </div>
</div>
		</div>
		<div class="col-md-7">
		</div>
	</div>
</div>