<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Options extends \yii\db\ActiveRecord
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
            [['name'], 'string', 'max' => 255],
            ['value','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_u' => Yii::t('app', 'OP_ID_U'),
            'id_t' => Yii::t('app', 'OP_ID_T'),
            'name' => Yii::t('app', 'OP_NAME'),
            'value' => Yii::t('app', 'OP_VALUE'),
        ];
    }
    
    public static function getAllTags()
    {
        return ArrayHelper::map(self::find()->where(['name' => 'tags', 'id_t' => '0'])->orderBy('id')->all(), 'id', 'value');
    }
    public static function getLogsetting($id_a,$v)
    {	if( $v == 'm'){
    		$buf =  self::find()->where(['name' => 'logsetting', 'id_t' => $id_a])->one();
        	return json_decode($buf->value,true);
    	}elseif( $v == 'o'){
        	return  self::find()->where(['name' => 'logsetting', 'id_t' => $id_a])->one();
    	}    		
    } 
    public static function getTemp_access($id_a,$name,$v)
    {	if( $v == 'm'){
    		$buf =  self::find()->where(['name' => $name, 'id_t' => $id_a])->one();
        	return json_decode($buf->value,true);
    	}elseif( $v == 'o'){
        	return  self::find()->where(['name' => $name, 'id_t' => $id_a])->one();
    	}    		
    }    
}
