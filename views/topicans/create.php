<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Topicans */

$this->title = Yii::t('app', 'Create Topicans');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topicans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topicans-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
