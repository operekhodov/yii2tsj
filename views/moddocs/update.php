<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Moddocs */

$this->title = Yii::t('app', 'Update: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Moddocs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'BUTTON_UPDATE');
?>
<div class="moddocs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
