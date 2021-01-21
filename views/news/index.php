<?php
use app\models\News;
use app\models\Area;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use app\widgets\grid\LinkColumn;

$this->title = Yii::t('app', 'Новости');
$this->params['breadcrumbs'][] = $this->title;
$css= <<< CSS
	.row_link:hover {
	    background-color: #bfeafb!important;
	    cursor: pointer;
	}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-8">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?= $this->title ?></h3>
		    <div class="box-tools pull-right">
				<?= (\Yii::$app->user->can('government')) ? Html::a(Yii::t('app', 'Добавить объявление'), ['create'], ['class' => 'btn btn-success']) : ''; ?>
		    </div>

		  </div>

		  <div class="box-body">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	    'rowOptions'   => function ($model, $index, $widget, $grid) {
	        return [
	            'id' => $model['id'], 
	            'onclick' => 'location.href="'
	                . Yii::$app->urlManager->createUrl('news/view') 
	                . '?id="+(this.id);',
	            'class' => 'row_link ',
	        ];
	    },        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [   
                'attribute' => 'title',
            ],  
			[
			    'attribute' => 'body',
			    'value' => function ($model) {
			    	
					$search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
					                 "'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги
					                 "'([\r\n])[\s]+'",                 // Вырезает пробельные символы
					                 "'&(quot|#34);'i",                 // Заменяет HTML-сущности
					                 "'&(amp|#38);'i",
					                 "'&(lt|#60);'i",
					                 "'&(gt|#62);'i",
					                 "'&(nbsp|#160);'i",
					                 "'&(iexcl|#161);'i",
					                 "'&(cent|#162);'i",
					                 "'&(pound|#163);'i",
					                 "'&(copy|#169);'i");      
					 
					$replace = array ("",
					                  "",
					                  "\\1",
					                  "\"",
					                  "&",
					                  "<",
					                  ">",
					                  " ",
					                  chr(161),
					                  chr(162),
					                  chr(163),
					                  chr(169),
					                  "chr(\\1)");
					 
					$body = preg_replace($search, $replace, $model->body);			    	
			    	$body = StringHelper::truncate($body, 300);
			        return $body;
			    }
			],            
			[
				'filter' => DatePicker::widget([
				'model' => $searchModel,
				'attribute' => 'datecreated',
				'value' => date('M-Y', strtotime('+2 days')),
				'options' => ['placeholder' => 'Выберете месяц...'],
				'pluginOptions' => [
				'autoclose' => true,
				'startView'=>'year',
				'minViewMode'=>'months',
				'format' => 'mm,yyyy',
				'todayHighlight' => true
			]
			]),
	            'attribute' => 'datecreated',
	        ],
            [
                'filter' => News::getTypeuseArray(),
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var Tasks $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};
                    switch ($value) {
                        case News::TYPE_NEWS:
                            $class = 'success';
                            break;
                        case News::TYPE_MESS:
                            $class = 'warning';
                            break;
                        case News::TYPE_BOARD:
                            $class = 'primary';
                            break;
                    };
                    $html = Html::tag('span', Html::encode($model->getTypeuseName()), ['class' => 'label label-' . $class]);

                    return $value === null ? $column->grid->emptyCell : $html;
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
		  </div>
		</div>

		</div>
		<div class="col-md-3">
		</div>
	</div>
</div>