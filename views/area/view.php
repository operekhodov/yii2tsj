<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Area;
use app\models\Module;
use kartik\select2\Select2;
use newerton\fancybox\FancyBox;
use app\rbac\Rbac as AdminRbac;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIDE_ALL_AREA'), 'url' => ['index']];
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
$css= <<< CSS
.hcs-thumbnail{
    width: 100px;    
    height: auto;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );
?>
<div class="area-view">


<?$arr_img = json_decode($model->logo); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
<?= ($arr_img) ? Html::a(Html::img('/'.$arr_img[0], ['class' => 'img-thumbnail hcs-thumbnail']), '/'.$arr_img[0], ['rel' => 'fancybox']) : '';?>
		</div>
		<div class="col-md-7">

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="box-tools pull-left">
		<ul class="nav nav-tabs">
			<li class="nav-item active">
				<a class="nav-link active" href="#tab1" data-toggle="tab"><h3 class="title_chart box-title">Карточка организации</h3></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#tab2" data-toggle="tab"><h3 class="title_chart box-title">Тарифы</h3></a>
			</li>
		</ul>
	</div>    
    <div class="box-tools pull-right">
	    <p>
	    	<?if ( \Yii::$app->user->can('agent') && \Yii::$app->user->identity->id_org == $model->id ) {?>
				<?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<? }elseif(\Yii::$app->user->can('moder')){ ?>
				<?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<? } ?>
			<?if (\Yii::$app->user->can('moder') ) {?>
		        <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->id], [
		            'class' => 'btn btn-danger',
		            'data' => [
		                'confirm' => 'Are you sure you want to delete this item?',
		                'method' => 'post',
		            ],
		        ]) ?>
			<? } ?>
	    </p> 
	</div>
  </div>
  <div class="box-body">
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			
		    <?= DetailView::widget([
		        'model' => $model,
		        'attributes' => [
		            //'id',
		            //'title',
		        [
		            'attribute' => 'status',
		            'value' => $model->StatusView($model->status),
		            'format' => 'html',
		        ],
		            'info',
		            //'logo',
		            'inn',
		            'address',
		            'about',
		        [
		            'attribute' => 'type',
		            'value' => $model->TypeView($model->type),
		            'format' => 'html',
		        ],
		            'notes',
		            'createddate:datetime',
		            'email',
		        ],
		    ]) ?>
		    <table class="table table-striped table-bordered detail-view">
		   <tbody>
		       <tr>
		           <th>
		               Модули
		           </th>
		           <td>
		<?
			$model->module = json_decode($model->module);
			
			$data = Module::getAllModule();
			if ($model->module) {
				foreach ($model->module as $key) {
				   echo '<span class="label label-primary">'.$data[$key]."</span> ";
				}
			}
		?>               
		           </td>
		       </tr>
		   </tbody> 
		</table>

		</div>
		<div class="tab-pane" id="tab2">
			
<p>КОММУНАЛЬНЫЕ УСЛУГИ </p>
<p>Водоотведение </p>
<p>Электроэнергия: </p>
<p>	- день					руб. </p>
<p>	- ночь					руб. </p>
<p>Домофон	 					руб. </p>
<hr>
<p>СОДЕРЖАНИЕ И РЕМОНТ ЖИЛОГО ПОМЕЩЕНИЯ </p>
<p>Содержание и ремонт ж/ф		руб. </p>
<hr>
<p>СОДЕРЖАНИЕ ОБЩЕГО ИМУЩЕСТВА </p>
<hr>
<p>ПРОЧИЕ УСЛУГИ </p>
<p> Вывоз и утилизация ТКО 	руб. </p>
<p> Кап. ремонт				руб. </p>

		</div>
	</div>
  </div>
</div>
 		
 		
		<div class="col-md-4">
		</div>
	</div>
</div>   


</div>
