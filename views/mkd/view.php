<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use newerton\fancybox\FancyBox;
use yii\grid\GridView;
use app\models\User;
use app\models\Area;
use yii\grid\ActionColumn;
use app\widgets\grid\LinkColumn;
use app\widgets\grid\SetColumn;
use app\widgets\grid\RoleColumn;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;

$css= <<< CSS
tr {
	text-align: center;
}
.row_link:hover {
	    background-color: #bfeafb!important;
	    cursor: pointer;
	}

CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
$this->title = 'МКД: ';
if(\Yii::$app->user->can('spec')){
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список всех МКД'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
?>
<?$area_logo = json_decode($area->logo);?>
			

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

<div class="box box-primary">
  <div class="box-header with-border">
	<h3 class="box-title"><?= $model->city.', '.$model->street.', '.$model->number_house ?></h3>  	

    <div class="box-tools pull-right">
	    <p>
			<? if(\Yii::$app->user->can('agent')){ ?>	    	
				<?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<? } ?>
				<? if(\Yii::$app->user->can('moder')){ ?>
			        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
			            'class' => 'btn btn-danger',
			            'data' => [
			                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
			                'method' => 'post',
			            ],
			        ]) ?>
			<? } ?>
	    </p>
    </div>

  </div>

<div class="box-body">
	<div class="tab-content">
		<div class="box-header with-border">
			<ul class="nav nav-tabs">
				<li class="nav-item active">
					<a class="nav-link active" href="#tabup1" data-toggle="tab"><h3 class="box-title">Жилой фонд</h3></a>
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
            //'id',
            //'id_a',
            //'city',
            //'street',
            //'number_house',
		        [
		            'attribute' => 'status',
		            'value' => $model->StatusView($model->status),
		            'format' => 'html',
		        ],
            'count_porch',
            'count_apartment',
            'count_floor',
            'cadastral',
            'size',
			'note',
        ],
    ]) ?>
		</div>
		<div class="tab-pane" id="tabup2">
			
		</div>
	</div>    
</div>

</div>

		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>

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
	        [
	            'attribute'=>'title',
	            'format'=>'raw',
	            'value'=>Html::a($area->title, ['area/view', 'id' => $area->id]),
	        ],
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



<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
			
<div class="box box-primary">
  <div class="box-header with-border">
	<ul class="nav nav-tabs">
	<li class="nav-item active">
		<a class="nav-link active" href="#tab1" data-toggle="tab"><h3 class="title_chart box-title">Жильцы</h3></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#tab2" data-toggle="tab"><h3 class="title_chart box-title">Правление</h3></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#tab3" data-toggle="tab"><h3 class="title_chart box-title">Персонал</h3></a>
	</li>
</ul>    
    <div class="box-tools pull-right">

    </div>

  </div>

  <div class="box-body">
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<? if(\Yii::$app->user->can('government')){ ?>
				<div class="box-tools pull-right">
					<p>
					<?= ModalAjax::widget([
						'id' => 'add_user',
						'header' => '<h3>'.Yii::t('app', 'Добавить жителя').'</h3>',
						'toggleButton' => [
							'label' => 'Добавить жителя',
							'tag' => 'span',
							'class' => 'btn btn-success',
						],
						'url' => Url::to(['add_user','id_mkd' => $model->id,'role' => 'user']), // Ajax view with form to load
						'ajaxSubmit' => true,
					]);?>				
					</p>
				</div>
		    <? } ?>
			<?php Pjax::begin([]) ?>    
			<?= GridView::widget([
			    'dataProvider' => $dataProvider,
			    'filterModel' => $searchModel,
			    'rowOptions'   => function ($model, $index, $widget, $grid) {
			        return [
			            'id' => $model['id'], 
			            'onclick' => 'location.href="'
			                . Yii::$app->urlManager->createUrl('user/view') 
			                . '?id="+(this.id);',
			            'class' => 'row_link ',
			        ];
			    },
			    'columns' => [
			        //'id',
			        [   
			            'attribute' => 'lname',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                $value = $model->{$column->attribute};
			                $html = Html::tag('p', Html::encode($value.' '.$model->fname.' '.$model->fio), []);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }				            
			        ],        
			//        type,
			        [
			            'filter' => User::getStatusesArray(),
			            'attribute' => 'status',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                /** @var User $model */
			                /** @var \yii\grid\DataColumn $column */
			                $value = $model->{$column->attribute};
			                switch ($value) {
			                    case User::STATUS_ACTIVE:
			                        $class = 'success';
			                        break;
			                    case User::STATUS_WAIT:
			                        $class = 'warning';
			                        break;
			                    case User::STATUS_BLOCKED:
			                    default:
			                        $class = 'default';
			                };
			                $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }
			        ],
			        'nroom',
			    ],
			]); 
			?>    
			  <?php Pjax::end() ?> 
		</div>
		<div class="tab-pane" id="tab2">
			<? if(\Yii::$app->user->can('government')){ ?>			
				<div class="box-tools pull-right">
				<p>
				<?= ModalAjax::widget([
					'id' => 'add_government',
					'header' => '<h3>'.Yii::t('app', 'Добавить').'</h3>',
					'toggleButton' => [
						'label' => 'Добавить',
						'tag' => 'span',
						'class' => 'btn btn-success',
					],
					'url' => Url::to(['add_user','id_mkd' => $model->id,'role' => 'government']), // Ajax view with form to load
					'ajaxSubmit' => true,
				]);?>				
				</p>
			</div>
			<? } ?>			
			<?php Pjax::begin([]) ?>    
			<?= GridView::widget([
			    'dataProvider' => $dataProvider_g,
			    'filterModel' => $searchModel_g,
			    'rowOptions'   => function ($model, $index, $widget, $grid) {
			        return [
			            'id' => $model['id'], 
			            'onclick' => 'location.href="'
			                . Yii::$app->urlManager->createUrl('user/view') 
			                . '?id="+(this.id);',
			            'class' => 'row_link ',
			        ];
			    },			    
			    'columns' => [
			        //'id',
			        [   
			            'attribute' => 'lname',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                $value = $model->{$column->attribute};
			                $html = Html::tag('p', Html::encode($value.' '.$model->fname.' '.$model->fio), []);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }			            
			        ],        
			//        type,
			        [
			            'filter' => User::getStatusesArray(),
			            'attribute' => 'status',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                /** @var User $model */
			                /** @var \yii\grid\DataColumn $column */
			                $value = $model->{$column->attribute};
			                switch ($value) {
			                    case User::STATUS_ACTIVE:
			                        $class = 'success';
			                        break;
			                    case User::STATUS_WAIT:
			                        $class = 'warning';
			                        break;
			                    case User::STATUS_BLOCKED:
			                    default:
			                        $class = 'default';
			                };
			                $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }
			        ],
			        'nroom',
			    ],
			]); 
			?>    
			  <?php Pjax::end() ?>			
		</div>
		<div class="tab-pane" id="tab3">
			<? if(\Yii::$app->user->can('boss')){ ?>
			    <div class="box-tools pull-right">
					<p>
					<?= ModalAjax::widget([
						'id' => 'add_personal',
						'header' => '<h3>'.Yii::t('app', 'Добавить сотрудника').'</h3>',
						'toggleButton' => [
							'label' => 'Добавить сотрудника',
							'tag' => 'span',
							'class' => 'btn btn-success',
						],
						'url' => Url::to(['add_personal','id_mkd' => $model->id,'id_org' => $model->id_a]), // Ajax view with form to load
						'ajaxSubmit' => true,
					]);?>				
					</p>	    
			    </div>	
		    <? } ?>
			<?php Pjax::begin([]) ?>
			<?= GridView::widget([
			    'dataProvider' => $dataProvider_p,
			    'filterModel' => $searchModel_p,
			    'rowOptions'   => function ($model, $index, $widget, $grid) {
			        return [
			            'id' => $model['id'], 
			            'onclick' => 'location.href="'
			                . Yii::$app->urlManager->createUrl('user/view') 
			                . '?id="+(this.id);',
			            'class' => 'row_link ',
			        ];
			    },			    
			    'columns' => [
			        //'id',
			        [
			            'filter' => User::getRolesPersonalArray(),
			            'attribute' => 'role',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                /** @var User $model */
			                /** @var \yii\grid\DataColumn $column */
			                $value = $model->{$column->attribute};
			                switch ($value) {
			                    case User::ROLE_BOSS:
			                        $class = 'warning';
			                        break;
			                    case User::ROLE_DISPATCHER:
			                        $class = 'danger';
			                        break;
			                    case User::ROLE_AGENT:
			                        $class = 'success';
			                        break; 
			                    case User::ROLE_SPEC:
			                        $class = 'info';
			                        break;			                        
			                };
			                $html = Html::tag('span', Html::encode($model->getRolesName()), ['class' => 'label label-' . $class]);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }
			        ],
			        [   
			            'attribute' => 'lname',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                /** @var User $model */
			                /** @var \yii\grid\DataColumn $column */
			                $value = $model->{$column->attribute};
			                $html = Html::tag('p', Html::encode($value.' '.$model->fname.' '.$model->fio), []);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }			            
			        ],        
			//        type,
			        [
			            'filter' => User::getStatusesArray(),
			            'attribute' => 'status',
			            'format' => 'raw',
			            'value' => function ($model, $key, $index, $column) {
			                /** @var User $model */
			                /** @var \yii\grid\DataColumn $column */
			                $value = $model->{$column->attribute};
			                switch ($value) {
			                    case User::STATUS_ACTIVE:
			                        $class = 'success';
			                        break;
			                    case User::STATUS_WAIT:
			                        $class = 'warning';
			                        break;
			                    case User::STATUS_BLOCKED:
			                    default:
			                        $class = 'default';
			                };
			                $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'label label-' . $class]);
			                return $value === null ? $column->grid->emptyCell : $html;
			            }
			        ],
			    ],
			]);
			?>
			  <?php Pjax::end() ?>	
		</div> 
	</div>  
  
  </div>

</div>


		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>