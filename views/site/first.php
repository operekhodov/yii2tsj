<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'FP_Title');

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><?=Yii::t('app', 'FP_name_system')?></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => Yii::t('app', 'FP_LOGIN'), 'icon' => 'sign-in', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => Yii::t('app', 'FP_SIGNUP'), 'icon' => 'registered', 'url' => ['site/checkphone'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
