<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mkd */

$this->title = Yii::t('app', 'Редактировать: {name}', [
    'name' => $model->city.', '.$model->street.', '.$model->number_house,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список всех МКД'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city.', '.$model->street.', '.$model->number_house, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="mkd-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
