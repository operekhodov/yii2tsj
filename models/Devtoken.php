<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devtoken".
 *
 * @property int $id
 * @property int $deviceid
 * @property int $token
 */
class Devtoken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devtoken';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceid', 'token'], 'required'],
            [['deviceid'],'unique'],
            [['deviceid', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deviceid' => 'Deviceid',
            'token' => 'Token',
        ];
    }
}
