<?
use yii\helpers\Html;
use app\models\Mkd;
use app\models\Area;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = 'Управляющие компании и ТСЖ на карте '.$search_bar['city'].'а';

$list_types_area = Area::getTypesArray();
$allCity = Mkd::getAllCity();
$selectedValues = array_search($search_bar['city'], $allCity);

?>

<?php Pjax::begin([])?> 
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $this->title ?></h3>
			</div>
				<div class="box-body">
					<?= Html::beginForm('maps', 'post',['data-pjax' => true, 'id'=>'filter']); ?>
						город: <?= Html::dropDownList('city', $selectedValues, $allCity,[
							'onchange'=>"(function ( \$event ) { $('#type').val('all'); $('#btn_all').submit(); })();",
							'id'=>'city']) ?><br><br>
						<? $color_button = ($search_bar['type'] == 'all') ? 'primary' : 'default'; ?>
						<?= Html::submitButton('Все', ['class' => "btn btn-$color_button btn-flat", 'id' => 'btn_all', 'onclick' => "(function ( \$event ) { $('#type').val('all'); })();" ]); ?>
						<? 
						foreach($list_types_area as $id => $name_type){
							$color	= Area::getColorType($id);
							$button = "<span class='fa fa-map-marker' style='color:$color;font-size:20px;'></span> ".$name_type;
							$color_button = ($id == $search_bar['type'] && $search_bar['type'] != 'all') ? 'primary' : 'default';
							echo Html::submitButton($button, ['class' => "btn btn-$color_button btn-flat", 'id' => 'btn_send', 'onclick' => "(function ( \$event ) { $('#type').val($id); })();" ]).' ';
						}
						?>
						<input type="hidden" id="type" name="type"  value="">
					<?php Html::endForm(); ?>
				</div>
			</div>
				<div class="tabbable" id="tabs-471952">
					
				<ul class="nav nav-tabs">
					<li class="nav-item active">
						<a class="nav-link active" href="#tab1" data-toggle="tab"><span class="fa fa-map-o"></span> На карте</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab2" data-toggle="tab"><span class="fa fa-navicon"></span> Списком</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
<?
$this->registerJs("$data",
    \yii\web\View::POS_END,
    'maps_js'
);
?>						
    					<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=53f5ce8f-19af-4cfc-aab6-e5fba0fedd66" type="text/javascript"></script>
						<div id="map" style="width: auto; height: 700px"></div>
					</div>
					<div class="tab-pane" id="tab2">
						
						
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>

            </div>
            <div class="box-body">

<?php Pjax::begin([]) ?> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [ 
                'attribute'=>'id_a',
                'value'=>'AreaHTMLLogo',
                'format'=>'html',
                'header'=>'Обслуживает'
            ],
            [ 
                'attribute'=>'id_a',
                'value'=>'AreaHTMLType',
                'format'=>'html',
                'header'=>'Тип'
            ],            
            [
            	'attribute'=>'city',
            ],
            [
            	'attribute'=>'street',
            ],
            [
            	'attribute'=>'number_house',
            ],
            [
            	'attribute'=>'note',
            	'headerOptions' => ['style' => 'width:25%'],
            ],            
        ],
    ]); ?>
<?php Pjax::end() ?> 

            </div>

          </div>
          
          
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php Pjax::end() ?> 
