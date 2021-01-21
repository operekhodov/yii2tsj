<?php
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use app\models\Message;
/**
 * @var yiiwebView $this
 * @var yiidbActiveQuery $messagesQuery
 */
?>
<?// $lastQuery = Message::findLastmess(2, 27);?>
<?= ListView::widget([
    'itemView' => '_row',
    'layout' => '{items}',
    'dataProvider' => new ActiveDataProvider([
        'query' => $lastQuery,
        'pagination' => [
			'pageSize' => 1,
		],
    ])
]) ?>
