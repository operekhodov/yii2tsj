<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use newerton\fancybox\FancyBox;
use app\models\Area;
/* @var $this yii\web\View */
/* @var $model app\models\User */

//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ADMIN_USERS'), 'url' => ['index']];
if($model->role == 'user' || $model->role == 'government') {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'МКД'), 'url' => ["/mkd/view?id=$model->id_a"]];
}else{
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'МКД'), 'url' => ["/mkd/index"]];
}
$this->params['breadcrumbs'][] = ['label' => $model->lname];

$this->title = 'Профиль: ';
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
$css= <<< CSS
.hcs-thumbnail{
    width: 100px;    
    height: auto;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );
?>
<?$arr_foto = json_decode($model->foto);?>
<?$arr_proof = json_decode($model->proof);?>
<?$area_logo = json_decode($area->logo);?>



<div class="user-view">

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
			<?= ($arr_foto) ? Html::a(Html::img('/'.$arr_foto[0], ['class' => 'img-thumbnail hcs-thumbnail']), '/'.$arr_foto[0], ['rel' => 'fancybox']) : ''?>
		</div>
		<div class="col-md-7">
			
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= $model->lname.' '.$model->fname.' '.$model->fio ?></h3>
    <div class="box-tools pull-right">
	 <p>
	        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	        <?= Html::a(Yii::t('app', 'Права доступа'), ['access_r', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	        <?= Html::a(Yii::t('app', 'BUTTON_DELETE'), ['delete', 'id' => $model->id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
	                'method' => 'post',
	            ],
	        ]) ?>
	    </p>
    </div>
    <!-- /.box-tools -->
  </div>
  <!-- /.box-header -->
  <div class="box-body">

		<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	//'id',
        	'role',
        	'created_at:datetime',
        [
            'attribute' => 'status',
            'value' => $model->StatusView($model->status),
            'format'=> 'html'
        ],
			'phonenumber',
			[
				'attribute' => 'locality',
				'visible' => ($model->role == 'user' || $model->role == 'government'),
			],
			[
				'attribute' => 'street',
				'visible' => ($model->role == 'user' || $model->role == 'government'),
			],
			[
				'attribute' => 'num_house',
				'visible' => ($model->role == 'user' || $model->role == 'government'),
			],
			[
				'attribute' => 'nroom',
				'visible' => ($model->role == 'user' || $model->role == 'government'),
			],			
       [
            'attribute' => 'email',
            'value' => ($model->email) ? json_decode($model->email,true)['email'] : '',
        ],	

        ],
	    ]) ?>
  </div>
</div>	    
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div> 
<? if($model->role == 'user' || $model->role == 'government'){ ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
			<?= ($arr_proof) ? Html::a(Html::img('/'.$arr_proof[0], ['class' => 'img-thumbnail hcs-thumbnail']), '/'.$arr_proof[0], ['rel' => 'fancybox']) : ''?>
		</div>
		<div class="col-md-7">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Данные: </h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">			
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			'nexit',
			'nfloor',
        [
            'attribute' => 'type',
            'value' => $model->getTypeName(),
        ],
			'nid',
			'space',
			'share',
			'ncad',
        [
            'attribute' => 'typeuse',
            'value' => $model->getTypeuseName(),
        ],

        ],
    ]) ?>
  </div>
</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
<? } ?>
<? if($area){ ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
			<?= ($area_logo) ? Html::a(Html::img('/'.$area_logo[0], ['class' => 'img-thumbnail hcs-thumbnail']), '/'.$area_logo[0], ['rel' => 'fancybox']) : ''?>
		</div>
		<div class="col-md-7">
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Обслуживающая организация</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">			
        <?= DetailView::widget([
        'model' => $area,
        'attributes' => [
			'title',
			'info',
			'address',
        ],
    ]) ?>
  </div>
</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
<? } ?>
</div>
