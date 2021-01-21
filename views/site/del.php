<?
use miloschuman\highcharts\GanttChart;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::t('app', 'Видеоконференция');
$this->params['breadcrumbs'][] = Yii::t('app', 'Видеоконференция');
?>
<script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/gantt/modules/gantt.js"></script>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
						<div class="col-md-4">
							<img alt="" src="/uploads/5db511fa499ea.jpg" class="img-thumbnail">
						</div>
					</div>
				</div>
				<div class="col-md-4">
					
					

			<div class="tabbable" id="tabs-57221">
				<ul class="nav nav-tabs">
					<li class="nav-item active">
						<a class="nav-link active show" href="#tab1" data-toggle="tab">Чат</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tab2" data-toggle="tab">Список пользователей</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
						<p>

<div class="hello" style="overflow-y:scroll;height:400px;">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
				
<?php Pjax::begin([
    'id' => 'list-messages',
    'enablePushState' => false,
    'formSelector' => false,
    'linkSelector' => false
]) ?>
<?= $this->render('_list', compact('messagesQuery')) ?>
<?php Pjax::end() ?>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>
</div><br>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4" style="padding: unset;">

			<?php  $form = ActiveForm::begin(['options' => ['class' => 'pjax-form form-group']]) ?>
				<?= Html::activeTextarea($message, 'text', ['class' => 'form-control']) ?><br>
				<? 
				echo $form->field($message, 'imageFiles')->widget(FileInput::classname(), [
				    'options' => ['accept' => 'image/*'],
				    'pluginOptions' => [
				        'showPreview' => false,
				        'showCaption' => true,
				        'showRemove' => false,
				        'showUpload' => false
				    ]				    	
				]);
				?>
				<?= Html::submitButton('Отправить', ['class' => 'btn btn-info btn-block btn-md']) ?><br>
			<?php ActiveForm::end() ?>
		
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>








						</p>
					</div>
					<div class="tab-pane" id="tab2">
						<p>
							Howdy, I'm in Section 2.
						</p>
					</div>
				</div>
			</div>
		
					
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
<br>

<?
echo GanttChart::widget([
    'options' => [
        'title' => ['text' => 'План работ ТСЖ "Уралец"'],
        'format' => 'yyyy-mm-dd',
        'series' => [
            [
                'name' => 'Проекты',
                'data' => [
                    [
                        'id' => 's',
                        'name' => 'Планирование ремонта',
                        'start' => new JsExpression('Date.UTC(2019, 01, 01)'),
                        'end' => new JsExpression('Date.UTC(2019, 01, 20)'),
                    ],
                    [
                        'id' => 'b',
                        'name' => 'Ремонт',
                        'start' => new JsExpression('Date.UTC(2019, 04, 01)'),
                        'end' => new JsExpression('Date.UTC(2019, 05, 25)'),
                        'dependency' => 's',
                    ],
                    [
                        'id' => 'a',
                        'name' => 'Проверка и сдача',
                        'start' => new JsExpression('Date.UTC(2019, 07, 01)'),
                        'end' => new JsExpression('Date.UTC(2019, 10, 26)'),
                        'dependency' => 'b',                        
                    ],
                    [
                        'name' => 'Получение отзывов',
                        'start' => new JsExpression('Date.UTC(2019, 10, 27)'),
                        'end' => new JsExpression('Date.UTC(2020, 01, 30)'),
                        'dependency' => [
                            'a',
                        ],
                    ],
                ],
            ],
        ],
    ],
]);
?>

					
				</div>
			</div>
		</div>
	</div>
</div>



