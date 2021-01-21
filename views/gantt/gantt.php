<?
use miloschuman\highcharts\GanttChart;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use app\models\Gantt;
use app\models\Mkd;
use app\models\Area;
var_dump(Area::getActiveArea());
var_dump($data = Mkd::getAllMkdThisArea(3));



































$this->title = Yii::t('app', 'План работ');
$this->params['breadcrumbs'][] = Yii::t('app', 'План работ');
?>
<script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/gantt/modules/gantt.js"></script>
<?

$work = Gantt::getWorkGantt();
$arr=array();
$k = array();
foreach($work as $key=>$value){
	$one  = Gantt::findById($key);
	$k = 
                    [
                        'id' => "$key",
                        'name' => "$one->name",
                        'start' => new JsExpression("Date.UTC($one->start)"),
                        'end' => new JsExpression("Date.UTC($one->end)"),
                    ];
	array_push($arr,$k);
}
echo GanttChart::widget([
    'options' => [
        'title' => ['text' => 'План работ ТСЖ "Уралец"'],
        'format' => 'yyyy-mm-dd',
        'series' => [
            [
                'name' => 'Проекты',
                'data' =>
                    $arr,
            ],
        ],
    ],
]);
?>



