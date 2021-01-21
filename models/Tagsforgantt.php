<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Tagsforgantt extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'tagsforgantt';
    }

    public function rules()
    {
        return [
            [['id_a', 'value'], 'required'],
            [['id_a'], 'integer'],
            [['name', 'value', 'note'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_a' => Yii::t('app', 'Id A'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Значение'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
    public static function getAllTags()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->all(), 'id', 'value');
    }        
}
