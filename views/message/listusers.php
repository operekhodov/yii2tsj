<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\grid\LinkColumn;
use yii\widgets\Pjax;
use app\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'CHAT_LIST');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
		    [
		        'attribute' => 'lname',
		        'value' => function (User $data) {
		            return Html::a(Html::encode($data->lname.' '.$data->fname.' '.$data->fio), Url::to(['chat', 'id' => $data->id]));
		        },
		        'format' => 'raw',
		    ],
        [
            'filter' => User::getRolesArray(),
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $column) {
                /** @var User $model */
                /** @var \yii\grid\DataColumn $column */
                $value = $model->{$column->attribute};
                switch ($value) {
                    case User::ROLE_ROOT:
                        $class = 'danger';
                        break;
                    case User::ROLE_ADMIN:
                        $class = 'warning';
                        break;
                    case User::ROLE_USER:
                        $class = 'primary';
                        break;
                    case User::ROLE_DISPATCHER:
                        $class = 'info';
                        break;
                    case User::ROLE_GOVERMENT:
                        $class = 'success';
                        break;                        
                    case User::ROLE_SUPERVISORS:
                        $class = 'info';
                        break; 
                };
                $html = Html::tag('span', Html::encode($model->getRolesName()), ['class' => 'label label-' . $class]);
                return $value === null ? $column->grid->emptyCell : $html;
            }
        ],            
        ],
    ]); ?>

</div>
