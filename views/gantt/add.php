<?
use yii\helpers\Url;
use lo\widgets\modal\ModalAjax;

echo ModalAjax::widget([
    'id' => 'createCompany',
    'header' => 'Create Company',
    'toggleButton' => [
        'label' => 'New Company',
        'class' => 'btn btn-primary pull-right'
    ],
    'url' => Url::to(['/tasks/create']), // Ajax view with form to load
    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
    // ... any other yii2 bootstrap modal option you need
]);

?>