<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yiiwebView $this
 * @var appmodelsMessage $message
 * @var yiidbActiveQuery $messagesQuery
 */
?>
<?php Pjax::begin([
    'timeout' => 3000,
    'enablePushState' => false,
    'linkSelector' => false,
    'formSelector' => '.pjax-form'
]) ?>
<?= $this->render('_vchat', compact('messagesQuery', 'message')) ?>
<?php Pjax::end() ?>
<?php $this->registerJs(<<<JS
function updateList() {
  $.pjax.reload({container: '#list-messages'});
}
setInterval(updateList, 5000);
JS
) ?>