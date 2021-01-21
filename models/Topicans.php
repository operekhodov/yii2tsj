<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

class Topicans extends \yii\db\ActiveRecord
{
	public $cnt;

    public static function tableName()
    {
        return 'topicans';
    }
    public function rules()
    {
        return [
            [['id_u', 'id_q', 'answer', 'note'], 'required'],
            [['id_u', 'id_q','created_at'], 'integer'],
            [['answer', 'note'], 'string', 'max' => 255],
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_u']);
    }
    public function getUserUsername() 
    {
        return $this->user->lname.' '.$this->user->fname.' '.$this->user->fio;
    }
    public function getSDA($id_q,$id_u)
    {
        return static::findOne(['id_q' => $id_q, 'id_u' => $id_u]);
    }
    public function getAllSDA($id)
    {
    	return ArrayHelper::map(self::find()->where(['id_q' => $id])->orderBy('id')->all(), 'id', 'answer');
    }
	public function getCountUsersAnswer($id_q)
	{
		return count( ArrayHelper::map(self::find()->where(['id_q' => $id_q])->orderBy('id')->all(), 'id', 'id_u') );
	}
	public function getCountDA($id_q)
	{
		return
		Topicans::find()
		    ->select(['answer', 'COUNT(*) AS cnt'])
		    //->where('and',['=', 'id_u', $id_u],['=', 'id_q', $id_q])
		    ->where(['=', 'id_q', $id_q])
		    ->groupBy('answer')
		    ->asArray()
		    ->all(); 	
	}
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_u' => Yii::t('app', 'ФИО'),
            'id_q' => Yii::t('app', 'Id Q'),
            'answer' => Yii::t('app', 'Вариант'),
            'note' => Yii::t('app', 'Комментарий'),
            'created_at' => Yii::t('app', 'Дата'),
        ];
    }
}
