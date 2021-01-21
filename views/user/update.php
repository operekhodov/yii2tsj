<?php
 
use yii\helpers\Html;
 
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\User */
 
$this->title = $user->lname.' '.$user->fname.' '.$user->fio;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ADMIN_USERS'), 'url' => ['index']];
if($user->role == 'user' || $user->role == 'government') {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'МКД'), 'url' => ["/mkd/view?id=$user->id_a"]];
}else{
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'МКД'), 'url' => ["/mkd/index"]];
}
$this->params['breadcrumbs'][] = ['label' => $user->lname, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'TITLE_UPDATE');
?>
<div class="user-update">
 
    <?= $this->render('_form', [
        'model' => $model, 'user' => $user
    ]) ?>

</div>
