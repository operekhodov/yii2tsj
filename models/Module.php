<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "module".
 *
 * @property int $id
 * @property string $name
 * @property string $action
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','action','sub_action','sub_action_ru'], 'required'],
            [['name','action','sub_action','sub_action_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'action' => Yii::t('app', 'Action'),
            'sub_action' => Yii::t('app', 'Sub Action'),
            'sub_action_ru' => Yii::t('app', 'Sub Action RU'),
        ];
    }
    public static function getAllModule()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->all(), 'id', 'name');
    }
    public static function getAllAction()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->all(), 'id', 'action');
    }    
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function findByAction($action)
    {
        return static::findOne(['action' => $action]);
    }    
    public function getArrNameMod($arr_id)
    {
    	$arr_action = array();
    	if ($arr_id) {
	    	foreach($arr_id as $key){
	    		$name = static::findOne(['id' => $key])->action;
	    		array_push($arr_action, $name);
	    	}
    		return $arr_action;
    	}else{return false;}
    }
}
