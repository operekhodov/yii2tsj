<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $from
 * @property int $to
 * @property string $text
 * @property string $imageFiles
 */
class Message extends \yii\db\ActiveRecord
{
	public $imageFiles;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to', 'text'], 'required'],
            [['from', 'to','dtime'], 'integer'],
            [['text','img'], 'string'],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'text' => Yii::t('app', 'Text'),
            'imageFiles'	=> Yii::t('app','FOTO'),
        ];
    }
    /**
     * @param int $from
     * @param int $to
     * @return ActiveQuery
     */
    public static function findMessages($from, $to)
    {
        return self::find()
            ->where(['from' => $from,'to' => $to])
            ->orWhere(['from' => $to, 'to' => $from])
            ->orderBy('id ASC');
    }
    public static function findMessagess($to)
    {
        return self::find()
            ->where(['to' => $to])
            ->orWhere(['from' => $to])
            ->orderBy('id ASC');
    }    
    
		
	public static function findLastmess($from, $to)
    {
        return self::find()
            ->where(['from' => $from,'to' => $to])
            ->orWhere(['from' => $to, 'to' => $from])
            ->orderBy('id DESC');
    }
}
