<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Devtoken */

$this->title = 'Create Devtoken';
$this->params['breadcrumbs'][] = ['label' => 'Devtokens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devtoken-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
