<?php

namespace app\models;

use Yii;

class Indicats extends \yii\db\ActiveRecord
{
	public $massiv = [];
	
    public static function tableName()
    {
        return 'indicats';
    }
    public function rules()
    {
        return [
            [['id_u', 'created_at'], 'integer'],
            [['indinow'], 'string'],
            [['massiv'], 'each', 'rule' => ['string']],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_u' => Yii::t('app', 'Id U'),
            'created_at' => Yii::t('app', 'Created At'),
            'indinow' => Yii::t('app', 'Indinow'),
        ];
    }
}
