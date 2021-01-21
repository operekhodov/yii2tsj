<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Новый проект');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'План работ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gantt-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
