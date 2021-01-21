<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Area;

/* @var $this yii\web\View */
/* @var $model app\models\Logs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="logs-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
        [
            'attribute'=>'id_a',
            'value'=>Area::findById($model->id_a)->title,
        ],   
        [
            'attribute'=>'id_u',
            'value'=>User::findById($model->id_u)->lname,
        ],            
            'created_at',
            'ip',
            'uagent',
            'action',
            'info',
            'dop',
            'note',
        ],
    ]) ?>

</div>
