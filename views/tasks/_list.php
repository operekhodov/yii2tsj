<?php
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
/**
 * @var yiiwebView $this
 * @var yiidbActiveQuery $messagesQuery
 */
?>

<?= ListView::widget([
    'itemView' => '_row',
    'layout' => '{items}',
    'dataProvider' => new ActiveDataProvider([
        'query' => $messagesQuery,
        'pagination' => false
    ])
]) ?>
