<?php

namespace app\models;

use Yii;

class Logsetting extends \yii\db\ActiveRecord
{
	public $index;
	public $create;
	public $update;
	public $delete;
	public $view;

    public static function tableName()
    {
        return 'options_';
    }

    public function rules()
    {
        return [
            [['index','create','update','delete','view','value'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'value' => Yii::t('app', 'Value'),
            'index' => Yii::t('app', 'index (Главная страница модуля)'),
            'view' => Yii::t('app', 'view (Просмотр)'),
            'create' => Yii::t('app', 'create (Добавление)'),
            'update' => Yii::t('app', 'update (Обновление)'),
            'delete' => Yii::t('app', 'delete (Удаление)'),
        ];
    }
}
