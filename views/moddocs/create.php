<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Moddocs */

$this->title = Yii::t('app', 'Create_Moddocs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Moddocs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moddocs-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
