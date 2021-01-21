<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Options */

$this->title = Yii::t('app', 'ADD_FIELD_USERS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'FIELD_USERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="options-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
