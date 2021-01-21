<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use dosamigos\chartjs\ChartJs;

/* @var $this yii\web\View */
/* @var $model app\models\Bank */

$json = json_decode($this->params['list'], true);
if($json) {
	foreach($json as $key => $arr){
		$key_amount = $arr["account_code"].'|'.$arr["bank_code"];;
		$arr_amount[$key_amount] = $arr["account_code"];
	}
}

if(!$this->params['result']):
	$this->title = 'Получение выписки по номеру счета';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= Html::beginForm('', 'post'); ?>
	<div class="row">
	<div class="form-group col-md-6">
	    <?= Html::label('Номер счета', 'account_code', ['class' => 'control-label']) ?>
	    <?= Html::dropDownList('key_amount', '', [$arr_amount], ['class' => 'form-control',]); ?>
	    <div class="hint-block">Выберите значение</div>
	</div>
	<div class="col-md-6">	
	<?
	// Usage without a model (with default initial value)
	
	echo '<label class="control-label">Период выписки</label>';
	echo DatePicker::widget([
		'name' => 'date_start',
		'name2' => 'date_end',
		'value' => 'ГГГГ-ММ-ДД',
		'value2' => 'ГГГГ-ММ-ДД',
	    'attribute' => 'from_date',
	    'attribute2' => 'to_date',
	    'options' => ['placeholder' => 'Начало периода','required' => 'true'],
	    'options2' => ['placeholder' => 'Конец периода','required' => true],
	    'type' => DatePicker::TYPE_RANGE,	
		'pluginOptions' => [
			'autoclose' => true,
			'format' => 'yyyy-mm-dd'
		]
	]);	
		?>
	</div>	
	</div>	
	
	<div class="form-group">
	    <?= Html::submitButton('Сгенерировать', ['class' => 'btn btn-success']) ?>
	</div>
	
	<?php Html::endForm(); ?>
    
</div>
<?php 
endif;
if($this->params['result']):
	$this->title = 'Ответ от банка';
	$this->params['breadcrumbs'][] = $this->title;	
?>
<div class="bank-list">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::beginForm('', 'post'); ?>
    
    <?= Html::hiddenInput('request_id_result', $this->params['statement']); ?>
    
	<div class="form-group">
	    <?= Html::submitButton('Получить выписку', ['class' => 'btn btn-success']) ?>
	</div>
	
	<?php Html::endForm(); 
	
	$json = json_decode($this->params['result'], true);
	//var_dump($json);
	?>
Сумма на момент начала выписки: <?= $json["balance_opening"]?>	
Сумма на момент закрытия выписки: <?= $json["balance_closing"]?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
<?= ChartJs::widget([
    'type' => 'doughnut',
    'options' => [
        'height' => 100,
        'width' => 100
    ],
    'data' => [
        'labels' => [
	        'Электричество',
	        'Вода',
	        'Содержание жилья'],
        'datasets' => [
            [
                'backgroundColor' => "rgba(255,0,0,1)",
                'backgroundColor' => "rgba(0,0,255,1)",
                'backgroundColor' => "rgba(0,255,0,1)",
                'data' => [65,50,90]
            ],
        ]
    ]
]);
?>			
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>


	<?
	if($json){
		foreach($json["payments"] as $value){
			$counterparty_name = $value['counterparty_name'];
			$operation_type = $value['operation_type'];
			$payment_amount = $value['payment_amount'];
			$payment_date = $value['payment_date'];
			$payment_purpose = $value['payment_purpose'];
			$html .= "

<div class=\"container-fluid\">
	<div class=\"row\">
		<div class=\"col-md-10\">
			<h4>
				$payment_date
			</h4>
			<dl>
				<dt>
					$counterparty_name
				</dt>
				<dd>
					$payment_purpose
				</dd>
			</dl>
		</div>
		<div class=\"col-md-2\">
			<h3 class=\"text-warning\">
				$payment_amount
			</h3>
		</div>
	</div>
</div>
				
			";
		}		
	}
	echo $html;
	
	
	?>    
</div>
<?
endif;