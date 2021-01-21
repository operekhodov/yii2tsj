<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use app\models\Area;
use yii\grid\ActionColumn;
//use app\widgets\grid\ActionColumn;
use app\widgets\grid\LinkColumn;
use app\widgets\grid\SetColumn;
use app\widgets\grid\RoleColumn;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$css= <<< CSS
	.row_link:hover {
	    background-color: #bfeafb!important;
	    cursor: pointer;
	}
CSS;
$this->registerCss($css, ["type" => "text/css"], "myStyles" );

$this->title = Yii::t('app', 'ADMIN_USERS');
$this->params['breadcrumbs'][] = $this->title;

$arr = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><?=$this->title;?></h3>
		    <div class="box-tools pull-right">
				<?= Html::a(Yii::t('app', 'CREATE_USER'), ['create'], ['class' => 'btn btn-success']) ?>
		    </div>
		  </div>
		  <div class="box-body">
<div class="user-index">

<?php Pjax::begin(); ?>
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
            'filter' => User::getSystemUArray(),
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $column) {
                /** @var User $model */
                /** @var \yii\grid\DataColumn $column */
                $value = $model->{$column->attribute};
                switch ($value) {
                    case User::ROLE_ROOT:
                        $class = 'danger';
                        break;
                    case User::ROLE_ADMIN:
                        $class = 'warning';
                        break;
                    case User::ROLE_MODER:
                        $class = 'primary';
                        break;
                    case User::ROLE_DISPATCHER:
                        $class = 'info';
                        break;
                    case User::ROLE_BOSS:
                        $class = 'success';
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
        [
        	'filter' => Area::getWorkArea(),
        	'attribute'	=> 'id_a',
        	'format' => 'raw',
        	'value' => 'AreaUName'
        ],
        //['class' => ActionColumn::className()],
    ],
]); 

?>
    <?php Pjax::end(); ?>
</div>
		  </div>

		</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>
