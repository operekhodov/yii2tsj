<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Gantt;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GanttSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'План работ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gantt-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить проект'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
        [
            'filter' => Gantt::getStatusesArray(),
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $column) {
                /** @var User $model */
                /** @var \yii\grid\DataColumn $column */
                $value = $model->{$column->attribute};
                switch ($value) {
                    case Gantt::STATUS_NEW:
                        $class = 'success';
                        break;
                    case Gantt::STATUS_WORK:
                        $class = 'primary';
                        break;
                    case Gantt::STATUS_TEST:
                        $class = 'warning';
                        break;                    	
                    default:
                        $class = 'default';
                };
                $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);
                return $value === null ? $column->grid->emptyCell : $html;
            }
        ],            
            'name',
            //'start',
            //'end',
            //'parent',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
