<?
use app\models\User;
use app\models\Tasks;
use app\models\Topic;
use app\models\Area;
use app\models\News;
?>

<aside class="main-sidebar">

    <section class="sidebar">

<? if (!Yii::$app->user->isGuest) { ?> 
        <!-- Sidebar user panel -->
		<?= Area::getHTMLLeftLayoutArea() ?>
		<!-- /Sidebar user panel -->
<?

$role	= \Yii::$app->user->identity->role;
$id 	= \Yii::$app->user->identity->id;
$id_a 	= \Yii::$app->user->identity->id_a;
$t0 	= News::getThisAreaNewsCount(Yii::$app->user->identity->id_a,0);
$t1 	= News::getThisAreaNewsCount(Yii::$app->user->identity->id_a,1);
$t2 	= News::getThisAreaNewsCount(Yii::$app->user->identity->id_a,2);
$st0	= Tasks::getThisAreaTasksCount(Yii::$app->user->identity->id_a,0);
$st2	= Tasks::getThisAreaTasksCount(Yii::$app->user->identity->id_a,2);
$st3	= Tasks::getThisAreaTasksCount(Yii::$app->user->identity->id_a,3);
$c_opros= Topic::getCountDoneTopic($id,$id_a);
$html_opros = ($role == 'user' || $role == 'government') ?
			        		"<a href='{url}'>{icon} {label}
			        			<span class='pull-right-container'>
			        				<small class='label pull-right bg-orange'>$c_opros</small>
			        			</span>
			        		</a>" : 
			        		"<a href='{url}'>{icon} {label}</a>";

}
?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Меню', 'options' => ['class' => 'header']],
                    ['label' => Yii::t('app', 'NAV_LOGIN'), 'icon' => 'sign-in', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => Yii::t('app', 'NAV_SIGNUP'), 'icon' => 'registered', 'url' => ['site/checkphone'], 'visible' => Yii::$app->user->isGuest],
					
					['url' => '/','label' => Yii::t('app', 'SIDE_MAIN'),'icon' => 'home', 'visible' => (!Yii::$app->user->isGuest)],
					
			        [
			        	'label' => Yii::t('app', 'Новости'), 
			        	'url' => ['/news/index'], 
			        	'icon' => 'newspaper-o', 
			        	'visible' => (!Yii::$app->user->isGuest),
			        	'template'=>
			        		"<a href='{url}'>{icon} {label}
			        			<span class='pull-right-container'>
			        				<small class='label pull-right bg-green'>$t0</small>
			        				<small class='label pull-right bg-blue'>$t1</small>
			        				<small class='label pull-right bg-red'>$t2</small>
			        			</span>
			        		</a>"			        	
			        	
			        ],					
					['label' => Yii::t('app', 'Жилой фонд'),'url' => ['/mkd/index'],'icon' => 'building ', 'visible' => (\Yii::$app->user->can('spec'))],
					['label' => Yii::t('app', 'Жилой фонд'),'url' => ['/mkd/view?id='.$id_a],'icon' => 'building ', 'visible' => ($role == 'government' )],
			        ['label' => Yii::t('app', 'SIDE_ALL_AREA'),'url' => ['/area/index'],'icon' => 'flag', 'visible' => (!Yii::$app->user->isGuest)],
			        ['label' => Yii::t('app', 'SIDE_ALL_MODULE'),'url' => ['/module/index'],'icon' => 'server', 'visible' => (\Yii::$app->user->can('moder'))],					
					
			        ['label' => Yii::t('app', 'CHAT'), 'url' => ['/chat/'], 'icon' => 'envelope', 'visible' => (!Yii::$app->user->isGuest)],
			        [
			        	'label' => Yii::t('app', 'SIDE_ALL_TASKS'),
			        	'url' => ['/tasks/index'],
			        	'icon' => 'tags', 
			        	'visible' => (!Yii::$app->user->isGuest && $role=='user'),
			        ],			        
			        [
			        	'label' => Yii::t('app', 'SIDE_ALL_TASKS'),
			        	'url' => ['/tasks/index'],
			        	'icon' => 'tags', 
			        	'visible' => (!Yii::$app->user->isGuest && $role!='user'),
			        	'template'=>
			        		"<a href='{url}'>{icon} {label}
			        			<span class='pull-right-container'>
			        				<small class='label pull-right bg-green'>$st0</small>
			        				<small class='label pull-right bg-blue'>$st2</small>
			        				<small class='label pull-right bg-gray'>$st3</small>
			        			</span>
			        		</a>"
			        ],
			        ['label' => Yii::t('app', 'Показания счетчиков'),'url' => ['/indicat/index'],'icon' => 'calendar', 'visible' => (!\Yii::$app->user->can('agent'))],
			        ['label' => Yii::t('app', 'Все показания по дому'),'url' => ['/indicat/index'],'icon' => 'calendar', 'visible' => (\Yii::$app->user->can('agent'))],
			        ['label' => Yii::t('app', 'SIDE_ALL_VIDEO'),'url' => ['/site/video'],'icon' => 'video-camera', 'visible' => (!Yii::$app->user->isGuest && Area::getAccessCheck('video'))],
			        ['label' => 'Транспорт', 'url' => ['/site/cars'],'icon' => 'copy', 'visible' => (!Yii::$app->user->isGuest && Area::getAccessCheck('cars'))],
			        [
			        	'label' => Yii::t('app', 'Голосование'), 
			        	'url' => ['/topic/index'], 
			        	'icon' => 'thumbs-up', 
			        	'visible' => (!Yii::$app->user->isGuest && Area::getAccessCheck('topic')),
			        	'template'=> $html_opros				        	
			        ],			        
			        ['label' => "Общее собрание", 'url' => ['/site/golos'],'icon' => 'users', 'visible' => (!Yii::$app->user->isGuest && Area::getAccessCheck('golos'))],
			        ['label' => "Документы", 'url' => ['/moddocs/index'],'icon' => 'list-alt', 'visible' => (!Yii::$app->user->isGuest && Area::getAccessCheck('moddocs'))],
			        [
			        	'label' => Yii::t('app', 'План работ'), 
			        	'url' => ['/gantt/kanban'],
			        	'icon' => 'sliders',
			        	'visible' => (!Yii::$app->user->isGuest),
			        ],
			        ['label' => Yii::t('app', 'SIDE_ALL_MAP'),'url' => ['/site/maps'],'icon' => 'map-marker'],
			        ['label' => Yii::t('app', 'SIDE_ALL_BANK'), 'url' => ['/bank/list'],'icon' => 'folder-open', 'visible' => ($role=='root'||$role=='admin'||$role=='government'||$role=='dispatcher')],
			        //['label' => Yii::t('app', 'SIDE_ALL_USERS'), 'url' => ['user/index'],'icon' => 'user', 'visible' => ($role=='root'||$role=='admin'||$role=='government'||$role=='dispatcher')],
			        [
			        	'label' => Yii::t('app', 'SIDE_ALL_OPTIONS'), 
			        	'icon' => 'wrench',
			        	'visible' => ($role=='root'||$role=='admin'),
						'items' => [
	                		['label' => Yii::t('app', 'NAV_CONF_VIDEO'), 'url' => ['/video/index']],
	                		['label' => Yii::t('app', 'NAV_CONF_BANK'), 'url' => ['/bank/authbank']],
			                ['label' => Yii::t('app', 'TAGS_TASKS'), 'url' => ['/options/tagsindex']],
			                ['label' => Yii::t('app', 'Шаблоны настроек'), 'url' => ['/options/pattern_ar']],
			                ['label' => Yii::t('app', 'Logs'), 'url' => ['/logs/index']],
			                ['label' => Yii::t('app', 'Logs Setting'), 'url' => ['/logs/settings']],
			                ['label' => Yii::t('app', 'SIDE_S_USERS'), 'url' => ['user/index'],'icon' => 'user', 'visible' => ($role=='root')],
						],					        	
			        ],			        
			        
                ],
            ]
        ) ?>

    </section>

</aside>
