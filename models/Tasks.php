<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UploadedFile;

class Tasks extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_INWORK = 2; 
    const STATUS_MODER = 3;
    const T_APARTMENT = 0;
    const T_HOUSE = 1;
    const T_PLACE = 2;
    const T_QOFFICE = 3;
    const T_ROFFICE = 4;
	public $imageFiles;
	public $id_mkd;

    public static function tableName()
    {
        return 'tasks';
    }

    public function rules()
    {
        return [
            [['createddate', 'info', 'createdby','tema'], 'required'],
            [['status', 'assignedto', 'createdby','createddate', 'finishdate','id_a'], 'integer'],
            [['imagebd','info', 'notes','address','admap','floor','porch','enddt','type','num','tema'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['type', 'default', 'value' => self::T_APARTMENT],
            [['createdby'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdby' => 'id']],
            [['assignedto'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['assignedto' => 'id']],    
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
            ['id_mkd','safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createddate' => Yii::t('app', 'T_ADD_DATE'),
            'finishdate' => Yii::t('app', 'T_CLOSE_DATE'),
            'status' => Yii::t('app', 'STATUS'),
            'info' => Yii::t('app', 'T_DATA'),
            'assignedto' => Yii::t('app', 'T_WHO_DO'),
            'notes' => Yii::t('app', 'T_NOTES'),
            'createdby' => Yii::t('app', 'T_WHO_ADD'),
            'imageFiles' => Yii::t('app', 'T_PHOTO'),
            'floor' => Yii::t('app', 'Этаж'),
            'porch' => Yii::t('app', 'Подъезд'),
            'enddt' => Yii::t('app', 'Дата закрытия задачи'),
            'type' => Yii::t('app', 'T_Type'),
            'num' => Yii::t('app', 'T_Num'),
            'tema' => Yii::t('app', 'Тема'),
            'id_mkd' => Yii::t('app', 'T_MKD'),
            'id_a' => Yii::t('app', 'T_MKD'),
        ];
    }
    public function getStName($name){
    	return ArrayHelper::getValue(self::getStatusesArray(), $name);
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    public static function getStatusesArray()
    {
        return [
            self::STATUS_NEW => Yii::t('app', 'STATUS_NEW'),
            self::STATUS_DONE => Yii::t('app', 'STATUS_DONE'),
            self::STATUS_INWORK => Yii::t('app', 'STATUS_INWORK'),
            self::STATUS_MODER => Yii::t('app', 'STATUS_MODER'),
        ];
    }
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }
    public static function getTypesArray()
    {
        return [
            self::T_APARTMENT => Yii::t('app', 'T_APARTMENT'),
            self::T_HOUSE => Yii::t('app', 'T_HOUSE'),
            self::T_PLACE => Yii::t('app', 'T_PLACE'),
            self::T_QOFFICE => Yii::t('app', 'T_QOFFICE'),
            self::T_ROFFICE => Yii::t('app', 'T_ROFFICE'),
        ];
    } 

    public function getMkd()
    {
        return $this->hasOne(Mkd::className(), ['id' => 'id_a']);
    }
    public function getMkdAddress() {
              return $this->mkd->city.', '.$this->mkd->street.', '.$this->mkd->number_house;
    }     
    public function getCreatedby0()
    {
        return $this->hasOne(User::className(), ['id' => 'createdby']);
    }
    public function getCreatedby0Username() {
              return $this->createdby0->lname.' '.$this->createdby0->fname.' '.$this->createdby0->fio;
    }

    public function getOptions0()
    {
        return $this->hasMany(Options::className(), ['id_t' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'assignedto']);
    }
    public function getUserUsername() {
        return $this->user->lname.' '.$this->user->fname.' '.$this->user->fio;
    }    
    public function StatusView($status) {
            switch ($status) {
                case Tasks::STATUS_NEW:
                    $statname = Yii::t('app', 'STATUS_NEW');
                    $class = 'success';
                    break;
                case Tasks::STATUS_DONE:
                    $statname = Yii::t('app', 'STATUS_DONE');
                    $class = 'warning';
                    break;
                case Tasks::STATUS_INWORK:
                    $statname = Yii::t('app', 'STATUS_INWORK');
                    $class = 'primary';
                    break;
                case Tasks::STATUS_MODER:
                    $statname = Yii::t('app', 'STATUS_MODER');
                    $class = 'default';
                    break;
                default:
                    $class = 'default';
            };
            $html = Html::tag('span', Html::encode($statname), ['class' => 'label label-' . $class]);

            return $status === null ? $column->grid->emptyCell : $html;
        }     
    public function NumberT($type){
        switch ($type) {
            case Tasks::T_APARTMENT:
                $num = 'К-';
                break;
            case Tasks::T_HOUSE:
                $num = 'Д-';
                break;
            case Tasks::T_PLACE:
                $num = 'Т-';
                break;
            case Tasks::T_QOFFICE:
                $num = 'В-';
                break;
            case Tasks::T_ROFFICE:
                $num = 'З-';
                break;                    
        };
        $q = self::find()->where(['type' => $type, 'id_a' => \Yii::$app->user->identity->id_a])->orderBy('id DESC')->one(); 
		if($q){
			$last = intval( preg_replace('/[^0-9]/', '', $q->num));
		}else{ $last = 0; }
		$last++;
		$num .= sprintf("%04d", $last);
		return $num;
    }

    public function getThisAreaTasksCount($id_a,$status){
		return count( self::find()->where(['id_a' => $id_a, 'status' => $status])->all() );
    }

}




