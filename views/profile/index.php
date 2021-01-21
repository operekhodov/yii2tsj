<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\models\Options; 
use newerton\fancybox\FancyBox;
use app\models\Mkd;
use app\models\Area;
use app\models\User;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
 
$this->title = Yii::t('app', 'NAV_PROFILE');
$this->params['breadcrumbs'][] = $this->title;
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
($_GET['wait'] == 1) ? Yii::$app->session->addFlash('warning', 'Для работы в системе, пожалуйста, заполните профиль и дождитесь подтверждения статуса.') : '';

// UPDATE (table name, column values, condition)
//Yii::$app->db->createCommand()->update('user', ['id_org' => 0], 'id_org = 666')->execute();

$a = array('root','admin','moder');
$b = array('government','user','halfuser');
$c = array('boss','dispatcher','spec','agent');

?>
<?$arr_foto = json_decode($model->foto);?>
<?$arr_proof = json_decode($model->proof);?>
<?$area_logo = json_decode($area->logo);?>
<div class="user-profile">

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
        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'LINK_PASSWORD_CHANGE'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </div>

  </div>

  <div class="box-body">
  		<? $role = Yii::$app->user->identity->role; ?>			
		<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
	        [
	            'attribute' => 'status',
	            'value' => User::StatusView($model->status),
	            'format'=> 'html'
	        ],
			'phonenumber',
			[
				'attribute' => 'role',
				'visible' => ( !in_array($role,$b) ),
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
<? if( in_array($role,$b) ) { ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
			<?= ($arr_proof) ? Html::a(Html::img('/'.$arr_proof[0], ['class' => 'img-thumbnail hcs-thumbnail']), '/'.$arr_proof[0], ['rel' => 'fancybox']) : ''?>
		</div>
		<div class="col-md-7">
			<div class="box box-primary">
				<div class="box-body">
				<div class="tab-content">
					<div class="box-header with-border">
						<ul class="nav nav-tabs">
							<li class="nav-item active">
								<a class="nav-link active" href="#tabup1" data-toggle="tab"><h3 class="box-title">Данные: <?= Mkd::getAddressMkdByID($model->id_mkd) ?> </h3></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#tabup2" data-toggle="tab"><h3 class="box-title">Счётчики</h3></a>
							</li>
						</ul>
					</div>
					<div class="tab-pane active" id="tabup1">
						<?= DetailView::widget([
			        'model' => $model,
			        'attributes' => [
						'nexit',
						'nfloor',
						'nroom',
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
					<div class="tab-pane" id="tabup2">
						<div class="box-tools pull-right">
							<p>
							<?= ModalAjax::widget([
								'id' => 'add_numerator',
								'header' => '<h3>'.Yii::t('app', 'Добавить счётчик').'</h3>',
								'toggleButton' => [
									'label' => 'Добавить счётчик',
									'tag' => 'span',
									'class' => 'btn btn-success',
								],
								'url' => Url::to(['add_numerator']), // Ajax view with form to load
								'ajaxSubmit' => true,
							]);?>				
							</p>
						</div>
						<div class="box-body">
							<?php // Pjax::begin([]) ?> 
							    <?= GridView::widget([
							        'dataProvider' => $dataProvider,
							        'filterModel' => $searchModel,
							        'columns' => [
							            ['class' => 'yii\grid\SerialColumn'],
							
							            'type',
							            'num',
							            'note',
										[
										    'class' => 'yii\grid\ActionColumn',
										    'options'=>['class'=>'action-column'],
										    'template'=>'{update}',
										    'buttons'=>[
										        'update' => function($url,$model,$key){
										            $btn = ModalAjax::widget([
																		'id' => 'up_numerator',
																		'toggleButton' => [
																			'label' => '',
																			'tag' => 'span',
																			'class' => 'glyphicon glyphicon-pencil btn btn-primary',
																		],
																		'url' => Url::to(['up_numerator','id'=>$model->id]), // Ajax view with form to load
																		'ajaxSubmit' => true,
																	]);
										            return $btn;
										        }
										    ]
										],

							        ],
							    ]); ?>							
							<?php // Pjax::end() ?> 
<?php
    Modal::begin([
        'header'=>'<h4>Update Model</h4>',
        'id'=>'update-modal',
        'size'=>'modal-lg'
    ]);

    echo "<div id='updateModalContent'></div>";

    Modal::end();
?>							
						</div>
					</div>
				</div>    
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