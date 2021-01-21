<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tasks */

$this->title = Yii::t('app', 'BT_ADD_TASKS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_ALL_TASKS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tasks-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
