<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = Yii::t('app', 'ADD_MODULE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIDE_ALL_MODULE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(
    "
    $('H1').text('');
	",
    \yii\web\View::POS_END,
    'del_module_create_title'
);
?>
<div class="module-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
