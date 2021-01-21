<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property int $id
 * @property string $client_id
 * @property string $secret_id
 * @property string $code
 * @property string $response_type
 * @property string $authorization_code
 * @property string $refresh_token
 * @property string $access_token
 * @property int $sandbox
 */
class Bank extends \yii\db\ActiveRecord
{
    const STATUS_BLOCKED = 'api';
    const STATUS_ACTIVE = 'sandbox';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'secret_id', 'code', 'response_type', 'authorization_code', 'refresh_token', 'access_token'], 'string', 'max' => 255],
            ['sandbox', 'string'],
            ['sandbox', 'default', 'value' => self::STATUS_ACTIVE],
            ['sandbox', 'in', 'range' => array_keys(self::getStatusesArray())],            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'secret_id' => 'Secret ID',
            'code' => 'Code',
            'response_type' => 'Response Type',
            'authorization_code' => 'Authorization Code',
            'refresh_token' => 'Refresh Token',
            'access_token' => 'Access Token',
            'sandbox' => 'Sandbox'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
 
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED =>  Yii::t('app', 'STATUS_REAL_API'),
            self::STATUS_ACTIVE =>  Yii::t('app', 'STATUS_SANDBOX'),
        ];
    }    
}
