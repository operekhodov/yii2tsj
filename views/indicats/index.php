<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'Indicats');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicats-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Indicats'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= ModalAjax::widget([
		'id' => 'add_indi',
		'header' => '<h3>'.Yii::t('app', 'Передать показания').'</h3>',
		'toggleButton' => [
			'label' => 'Передать показания',
			'tag' => 'span',
			'class' => 'btn btn-success',
		],
		'url' => Url::to(['add_indi']),//,'id_mkd' => $model->id,'role' => 'user']), // Ajax view with form to load
		'ajaxSubmit' => true,
	]);?>    

<? 
	$columns = array();
	$columns[0] = 
            [
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => [
                    	'format' => 'mm.yyyy',
                    	'startView'=>'months',
                    	'minViewMode'=>'months',
                    	'autoclose' => true,
                    	'todayHighlight' => true
                    ]
                ]),
                'attribute' => 'created_at',
			    'contentOptions' => ['class' => 'date_column'],
			    'headerOptions' => ['class' => 'date_column']                
            ];	
	
	$i = 1;
	foreach($UsrNums as $num => $note){
		$columns[$i] = 
[
    'attribute' => $note,
    'format' => 'raw',
    'value' => function ($model, $key, $index, $column) use ($num) {
        $value = json_decode($model->indinow, JSON_BIGINT_AS_STRING);
        $html = $value[$num];
        return $value === null ? $column->grid->emptyCell : $html;
    }
];		
		$i++;
	}
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
