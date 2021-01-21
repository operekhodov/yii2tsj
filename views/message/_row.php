<?php
use app\models\User;
use yii\helpers\Html;
use newerton\fancybox\FancyBox;
/**
 * @var yiiwebView $this
 * @var appmodelsMessage $model
 */
 
$fio	= User::findById($model->from)->lname.' '.User::findById($model->from)->fname.' '.User::findById($model->from)->fio;
$role	= '( '.User::findById($model->from)->role.' )';
$id 	= \Yii::$app->user->identity->id;
?>
<div class="direct-chat-msg <?= ($model->from == $id) ? 'right':'' ?>">
<div class="direct-chat-info clearfix">
  <span class="direct-chat-name <?= ($model->from == $id) ? 'pull-right':'pull-left' ?>"><?= $fio ?></span>
  <span class="direct-chat-timestamp <?= ($model->from == $id) ? 'pull-right':'pull-left' ?>"><?= $role ?></span>
  <span class="direct-chat-timestamp <?= ($model->from == $id) ? 'pull-left':'pull-right' ?>"><?= ($model->dtime) ? date("Y-m-d H:i:s", $model->dtime) :''; ?></span>
</div>

<? $arr_img = json_decode(User::findById($model->from)->foto);	
if ($arr_img){?>
	<img alt="" src="/<?=$arr_img[0]?>" class="direct-chat-img" />
<? }else{ ?>
	<img class="direct-chat-img" src="/uploads/5dc63e17ab2f7.jpg" alt="">
<? } ?>

<div class="direct-chat-text">
	<? if($model->img){
			$img = '/uploads'.substr($model->img, 10);
			$img = substr($img,0,-2);
			echo FancyBox::widget([
			    'target' => 'a[rel=fancybox]',
			    'helpers' => true,
			    'mouse' => true,
			    'config' => [
			        'maxWidth' => '90%',
			        'maxHeight' => '90%',
			        'playSpeed' => 7000,
			        'padding' => 0,
			        'fitToView' => false,
			        'width' => '70%',
			        'height' => '70%',
			        'autoSize' => false,
			        'closeClick' => false,
			        'openEffect' => 'elastic',
			        'closeEffect' => 'elastic',
			        'prevEffect' => 'elastic',
			        'nextEffect' => 'elastic',
			        'closeBtn' => false,
			        'openOpacity' => true,
			        'helpers' => [
			            'title' => ['type' => 'float'],
			            'buttons' => [],
			            'thumbs' => ['width' => 68, 'height' => 50],
			            'overlay' => [
			                'css' => [
			                    'background' => 'rgba(0, 0, 0, 0.8)'
			                ]
			            ]
			        ],
			    ]
			]);
			echo Html::a(Html::img($img, ['class' => 'img-thumbnail']), $img, ['rel' => 'fancybox', 'class' => 'img_a']);
			echo " |   ";
		}
		echo ($model->text != "~~~~~") ? $model->text : ''; ?>
</div>
</div>
    
    