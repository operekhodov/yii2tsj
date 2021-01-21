<?php

namespace app\models;

use Yii;

class Tariff extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'options_';
    }

    public function rules()
    {
        return [
            [['id_u', 'id_t'], 'integer'],
            [['name'], 'required'],
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_u' => Yii::t('app', 'Id U'),
            'id_t' => Yii::t('app', 'Id T'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
