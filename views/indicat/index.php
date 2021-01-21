<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\models\Indicat;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use app\widgets\grid\LinkColumn;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

if($_GET['tnx']) {
   	$date_now = Yii::$app->formatter->asDate('now', 'dd.MM.yyyy');
	$y_m = substr($date_now,0,7);
	$id_u = \Yii::$app->user->identity->id;
	$last_indicat  = Indicat::find()->where('id_u = :id_u', [':id_u'  => $id_u])->andWhere(['like', 'created_at', $y_m])->one();
	$buf = ($last_indicat) ? 1 : 0;
}else{$buf=0;}

$_GET['tnx'] = null;
$this->title = Yii::t('app', 'indicat_Title');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    "
    if( $buf == 1 ) {
    	$('#w5').modal('show');
    }
    ",
    \yii\web\View::POS_END,
    'del_title'
);
?>
 <?php 
$css= <<< CSS
.date_column{
    width: 20%;
    text-align:center;
}
.datepicker{
	z-index:9999!important;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>
<?php Pjax::begin([]) ?> 
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-8">
			
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><?= Yii::t('app', 'indicat_Title')?></h3>
    <div class="box-tools pull-right">
<?= Html::a(Yii::t('app', 'indicat_create_button'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>

  </div>

  <div class="box-body">
<div class="indicat-index">
	    <div class="box-tools pull-right">
	<p>
	<?
	$gridColumns = [
		'created_at',
        'gvs',
        'gvs1',
        'hvs',
        'hvs1',
        'elday',
        'elnight',
        [
            'attribute'=>'id_u',
            'value'=>'UserUsername',
            'visible'=> (\Yii::$app->user->identity->role != 'user'),
        ]

	 ];
	echo ExportMenu::widget([
	'dataProvider' => $dataProvider,
	'columns' => $gridColumns,
	'exportConfig' => [
		ExportMenu::FORMAT_HTML => false,
	],
	'tableOptions' => [
	'class' => 'table table-striped table-responsive'
	],	
	]);
	?> 
	</p>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => [
                    	'format' => 'mm.yyyy',
                    	'startView'=>'months',
                    	'minViewMode'=>'months',
                    	'autoclose' => true,
                    	'todayHighlight' => true
                    ]
                ]),
                'attribute' => 'created_at',
			    'contentOptions' => ['class' => 'date_column'],
			    'headerOptions' => ['class' => 'date_column']                
            ],
            'gvs',
            'gvs1',
            'hvs',
            'hvs1',
            'elday',
            'elnight',
            [   'filter' => User::getAllUsersNameThisArea(\Yii::$app->user->identity->id_org),
                'attribute'=>'id_u',
                'value'=>'UserUsername',
                'visible'=> (\Yii::$app->user->identity->role != 'user' && \Yii::$app->user->identity->role != 'root' && \Yii::$app->user->identity->role != 'government'),
                'class' => LinkColumn::className(),
            ],
			[   'filter' => User::getAllUsersNameThisMkd(\Yii::$app->user->identity->id_a),
                'attribute'=>'id_u',
                'value'=>'UserUsername',
                'visible'=> (\Yii::$app->user->identity->role == 'government'),
                'class' => LinkColumn::className(),
            ],            
            [   'filter' => User::getActiveUser(),
                'attribute'=>'id_u',
                'value'=>'UserUsername',
                'visible'=> (\Yii::$app->user->identity->role == 'root'),
                'class' => LinkColumn::className(),
            ],            

            [
            	'class' => 'yii\grid\ActionColumn',
            	'visible'=> (\Yii::$app->user->identity->role != 'user'),
            ],
        ],
    ]); ?>
</div>
  </div>
</div>
		</div>
		<div class="col-md-3">
		</div>
	</div>
</div>
<?php Pjax::end() ?> 
<?
Modal::begin([
	'header' => '<h2>Спасибо!</h2>',
]);
?>
<h3>
Показания приняты в обработку
</h3>
<p>
Вы можете внести изменения во внесенные показания до 25го числа этого месяца
</p>
<h4>
Показания переданы за услуги: 
</h4>
<ul>
	<li>ГВС-1: <?= $last_indicat->gvs.' м3' ?></li>
	<li>ГВС-2: <?= $last_indicat->gvs1.' м3' ?></li>
	<li>ХВС-1: <?= $last_indicat->hvs.' м3' ?></li>
	<li>ХВС-2: <?= $last_indicat->hvs1.' м3' ?></li>
	<li>ЭлДень: <?= $last_indicat->elday.' кВт.ч' ?></li>
	<li>ЭлНочь: <?= $last_indicat->elnight.' кВт.ч' ?></li>
</ul>
<?php Modal::end(); ?> 
