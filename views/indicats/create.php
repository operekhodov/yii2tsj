<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Indicats */

$this->title = Yii::t('app', 'Create Indicats');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Indicats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicats-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
