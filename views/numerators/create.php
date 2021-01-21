<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Numerators */

$this->title = Yii::t('app', 'Create Numerators');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Numerators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="numerators-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
