<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Tasks;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;
$buttons =   ['class' => 'yii\grid\ActionColumn',
            'template' => '{view} {delete}',
            ];
?>
<div class="logs-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'id_a',
        [
            'attribute'=>'id_u',
            'value'=>'UserUsername',
        ],            
            'created_at',
            //'ip',
            //'uagent',
            'action',
            //'dop',
            'note',
            [
                'filter' => Tasks::getStatusesArray(),
                'attribute' => 'info',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var Tasks $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case Tasks::STATUS_NEW:
                            $class = 'success';
                            break;
                        case Tasks::STATUS_DONE:
                            $class = 'warning';
                            break;
                        case Tasks::STATUS_INWORK:
                            $class = 'primary';
                            break;
                        case Tasks::STATUS_MODER:
                            $class = 'default';
                            break;                            
                        default:
                            $class = 'default';
                    };
                    $html = Html::tag('span', Html::encode(Tasks::getStName($model->info)), ['class' => 'label label-' . $class]);

                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],			

            $buttons,
        ],
    ]); ?>
</div>
