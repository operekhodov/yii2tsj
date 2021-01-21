<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mkd */

$this->title = Yii::t('app', 'Добавить МКД');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список всех МКД'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mkd-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
