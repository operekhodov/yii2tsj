<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Area */

$this->title = Yii::t('app', 'B_ADD_AREA');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'NAV_AREA'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
