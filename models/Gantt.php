<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Gantt;
use app\models\Options;
use app\models\User;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;

class Gantt extends \yii\db\ActiveRecord
{
    const STATUS_NEW	= 0;
    const STATUS_WORK 	= 1;
    const STATUS_TEST	= 2;
    const STATUS_DONE	= 3;

    public static function tableName()
    {
        return 'gantt';
    }
    public function rules()
    {
        return [
            [['status', 'name', 'start', 'end','id_u'], 'required'],
            [['status', 'parent','id_a','id_u'], 'integer'],
            [['name', 'start', 'end','about','chekbox'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Статус'),
            'name' => Yii::t('app', 'Описание'),
            'start' => Yii::t('app', 'Дата начала'),
            'end' => Yii::t('app', 'Дата завершения'),
            'parent' => Yii::t('app', 'Родительская задача'),
            'about' => Yii::t('app', 'Описание'),
            'tags' => Yii::t('app', 'Теги'),
            'chekbox' => Yii::t('app', 'Чек-лист'),
        ];
    }
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NEW  =>  Yii::t('app', 'STATUS_NEW'),
            self::STATUS_WORK =>  Yii::t('app', 'В работе'),
            self::STATUS_TEST =>  Yii::t('app', 'Сдача задачи'),
            self::STATUS_DONE =>  Yii::t('app', 'STATUS_DONE'),
        ];
    }
    public static function getAllGantt()
    {
        return ArrayHelper::map(self::find()->orderBy('id')->all(), 'id', 'name','status');
    }
    public static function getWorkGantt()
    {
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_NEW])->orderBy('id')->all(), 'id','name');
    }
     public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }   
	public function Kanban($status,$id_mkd){
		$query = Gantt::find()->where(['status' => $status, 'id_a' => $id_mkd]);
		
		$provider = new ActiveDataProvider([
		    'query' => $query,
		]);

		$posts = $provider->getModels();
		
		$full_data  = array();
		foreach($posts as $model){
		
			$model->tags = json_decode($model->tags);
			$data = Options::getAllTags();
			if ($model->tags) {
				foreach ($model->tags as $key) {
				   $html_tags .= '<span class="label label-primary">'.$data[$key]."</span> ";
				}
			}
			$modal_update_event = (Yii::$app->user->identity->role !='user') ? 
								ModalAjax::widget([
									'id' => 'update_event'.$model->id,
									'header' => '<h3>'.Yii::t('app', 'Изменить').'</h3>',
									'toggleButton' => [
										'label' => '',
										'tag' => 'span',
										'class' => 'pull-right glyphicon glyphicon glyphicon-pencil btn',
									],
									'url' => Url::to(['update_event','id' => $model->id]), 
									'ajaxSubmit' => true,
								]) : '';
			$name = User::getActiveUserFullName($model->id_u);
			$one_html = "
				$model->name
				
				$modal_update_event<br>
				
				<span class='direct-chat-timestamp pull-left'>Период: $model->start - $model->end</span><br>
				<span class='direct-chat-timestamp pull-left'>Автор: $name </span><br>
				$html_tags
			";
			$html_tags = '';
			$class = 'dragdrop'.$status;
			$full_data[$model->id] = ['content' => $one_html,'options' => ['class' => $class]];
			
		} 
		return $full_data;
	}
}
