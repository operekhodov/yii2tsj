<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Numerators extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'numerators';
    }
    public function rules()
    {
        return [
            [['id_u', 'type', 'num'], 'integer'],
            [['note'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_u' => Yii::t('app', 'Id U'),
            'type' => Yii::t('app', 'Type_num'),
            'num' => Yii::t('app', 'Num_num'),
            'note' => Yii::t('app', 'Note_num'),
        ];
    }
	public function getUsrNums($id_u)
	{
		return ArrayHelper::map(self::find()->where(['id_u' => $id_u])->all(), 'num','note');
	}
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
}
