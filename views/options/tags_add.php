<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'ADD_TAGS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_TAGS'), 'url' => ['tagsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="options-create">

    <?= $this->render('_form_tags', [
        'model' => $model,
    ]) ?>

</div>
