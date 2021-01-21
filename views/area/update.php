<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Area */

$this->title = Yii::t('app', 'UPDATE_CONTACT') . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIDE_ALL_AREA'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'UPDATE_CONTACT');
?>
<div class="area-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
