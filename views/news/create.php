<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('app', 'Добавить объявление');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Новости'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
