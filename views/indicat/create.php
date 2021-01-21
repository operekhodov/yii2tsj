<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Indicat */

$this->title = Yii::t('app', 'indicat_create_title');
$this->params['breadcrumbs'][] = (stristr(Url::to(),'ucreate')) ?  ['label' => Yii::t('app', 'indicat_all'), 'url' => ['myindicat']] : ['label' => Yii::t('app', 'indicat_all'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="indicat-create">

    <?= $this->render('_form', [
        'model' => $model, 'last_indicat' => $last_indicat
    ]) ?>


</div>
