<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tagsforgantt */

$this->title = Yii::t('app', 'Добавить тег');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Все теги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tagsforgantt-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
