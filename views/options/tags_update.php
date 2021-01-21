<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Options */

$this->title = $model->value;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_TAGS'), 'url' => ['tagsindex']];
$this->params['breadcrumbs'][] = ['label' => $model->value, 'url' => ['tagsview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="options-update">

    <?= $this->render('_form_tags', [
        'model' => $model,
    ]) ?>

</div>
