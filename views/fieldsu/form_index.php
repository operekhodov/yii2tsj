<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OptionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'FIELD_USERS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="options-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'value',
			[
			  'attribute' => '',
			  'filter' => false,
			  'format' => 'raw',
			  'value' => function ($data) {                    
			     // Возвращаем значение в поле колонки
			     // return $data->title;
			     $html = "<input id='$model->value' type='text' size='20'>"; 
			     return $html;
			  }
			],            
        ],
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>    
<?php ActiveForm::end(); ?>

</div>
