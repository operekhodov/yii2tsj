<?php
use yii\helpers\Html;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">'.Yii::t('app', 'FP_name_system_mini').'</span><span class="logo-lg">'.Yii::t('app', 'FP_name_system').'</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
<? if (!Yii::$app->user->isGuest) {
$this_user = \Yii::$app->user->identity;
$fio = $this_user->lname.' '.mb_substr($this_user->fname,0 , 1,'UTF-8').'. '.mb_substr($this_user->fio,0 , 1,'UTF-8').'.';    
$fio_full = $this_user->lname.' '.$this_user->fname.' '.$this_user->fio;    
?>
            <ul class="nav navbar-nav">

                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may
                                        not fit into the page and may cause design problems
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"<?= ($this_user->nid) ? 'style="text-align: center;padding-top: 5px;padding-bottom: 5px;"' : '' ?> >

                        <?$arr_img = json_decode($this_user->foto);
                        if($arr_img) {?>
                            <img src="/<?=$arr_img[0]?>" class="user-image" alt="User Image" <?= ($this_user->nid) ? 'style="margin-top: 5px;"' : '' ?> />
                        <?}else{?>
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" <?= ($this_user->nid) ? 'style="margin-top: 5px;"' : '' ?>/>
                        <?}?>
                        <span class="hidden-xs "><?= $fio ?><br><?= $this_user->nid ?></span>
                        
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?$arr_img = json_decode($this_user->foto);
                            if($arr_img) {?>
                                <img src="/<?=$arr_img[0]?>" class="img-circle" alt="User Image"/>
                            <?}else{?>
                                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                            <?}?>                            

                            <p>
                                <?= $fio_full ?>
                                <small><?= $this_user->nid ?></small>
                                <small><?= $this_user->role ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <?= Html::a(
                                    Yii::t('app', 'CHAT'),
                                    ['message/listusers']
                                ) ?>                                                                    
                            </div>
                            <div class="col-xs-4 text-center">
                                <?= Html::a(
                                    Yii::t('app', 'SIDE_ALL_TASKS'),
                                    ['tasks/index']
                                ) ?> 
                            </div>
                            <div class="col-xs-4 text-center">
                                <?= Html::a(
                                    Yii::t('app', 'SIDE_INDICAT'),
                                    ['indicat/index']
                                ) ?> 
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    Yii::t('app', 'NAV_PROFILE'),
                                    ['profile/index'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('app', 'NAV_LOGOUT'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>


            </ul>
<?}?>

        </div>
    </nav>
</header>
