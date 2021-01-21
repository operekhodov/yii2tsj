<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Options;
use app\models\Module;
use app\models\Mkd;
use yii\helpers\ArrayHelper;

class Logs extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'logs';
    }
    public function rules()
    {
        return [
            [['id_a', 'id_u'], 'integer'],
            [['created_at', 'ip', 'uagent', 'action'], 'required'],
            [['created_at', 'ip', 'uagent', 'action', 'info', 'dop', 'note'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_a' => Yii::t('app', 'Id A'),
            'id_u' => Yii::t('app', 'Id U'),
            'created_at' => Yii::t('app', 'Created At'),
            'ip' => Yii::t('app', 'Ip'),
            'uagent' => Yii::t('app', 'Uagent'),
            'action' => Yii::t('app', 'Action'),
            'info' => Yii::t('app', 'Info'),
            'dop' => Yii::t('app', 'Dop'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
	public function getStHistory($id)
	{
		return ArrayHelper::map(self::find()->where(['note' => '?id='.$id,'dop' => ['update','create']])->orderBy('id DESC')->all(), 'created_at', 'info');
	}
    public function addLog($controller,$action,$id,$info,$dop,$note)
    {
    	
    	if(Yii::$app->getUser()->identity->role == 'user' || Yii::$app->getUser()->identity->role == 'government'){
    		$id_org = Mkd::findByID( Yii::$app->getUser()->identity->id_a )->id_a;
    	}elseif(Yii::$app->getUser()->identity->role == 'root'){
    		return true;
    	}else{
    		$id_org =Yii::$app->getUser()->identity->id_org;
    	}
    	
    	$options = Options::getLogsetting($id_org,'m');
   		$stop = (array_key_exists($controller,$options)) ? 1 : 0; 
    	if ($stop == 1) {
    		$stop = (in_array($action,$options[$controller])) ? 1 : 0;
    	}
    	if ( ($stop == 1) && (!$note) ) {
    		if ( $action == 'create' || $action == 'update' ) {
    			$stop = 0;
    		}
    	}

		if ($stop == 1) {
			
	    	$url = $id ? $controller.'/'.$action.''.'?id='.$id : $controller.'/'.$action;
	    	
	    	$headers = Yii::$app->request->headers;
			$model = new Logs();
			$model->id_a		= \Yii::$app->user->identity->id_a;	
			$model->id_u		= \Yii::$app->user->identity->id;
			$model->created_at	= Yii::$app->formatter->asDate('now', 'yyyy/MM/dd  HH:mm:ss');
			$model->ip			= Yii::$app->request->userIP;
			$model->uagent		= $headers->get('User-Agent');
			$model->action		= $url;
			$model->info		= $info;
			$model->dop			= $dop;
			$model->note		= $note;
			$model->save();
			return true;
		}else{
			return true;
		}
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_u']);
    }
    public function getUserUsername() {
        return $this->user->lname;
    }
    
}
