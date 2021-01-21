<?php
namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\User;
use app\models\Module;

class Area extends \yii\db\ActiveRecord
{
	public $imageFiles;
	const TYPE_TSG	= '0';
	const TYPE_UK	= '1';
	const TYPE_KP	= '2';
	const TYPE_SNT	= '3';
	const TYPE_SMD	= '4';
	
    const STATUS_OFF	= 0;
    const STATUS_ON 	= 1;	

    public static function tableName()
    {
        return 'area';
    }
    public function rules()
    {
        return [
            [['createddate'], 'required'],
            [['createddate', 'status', 'type'], 'integer'],
            [['info', 'notes', 'logo', 'inn', 'address', 'about','title','email','useemail'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'createddate' => Yii::t('app', 'CREATEDDATE'),
            'status' => Yii::t('app', 'STATUS'),
            'info' => Yii::t('app', 'INFO'),
            'notes' => Yii::t('app', 'NOTES'),
            'logo' => Yii::t('app', 'LOGO'),
            'inn' => Yii::t('app', 'INN'),
            'address' => Yii::t('app', 'ADDRESS'),
            'about' => Yii::t('app', 'ABOUT'),
            'type' => Yii::t('app', 'TYPE'),
            'imageFiles' => Yii::t('app', 'LOGO'),            
            'title' => Yii::t('app', 'Название'), 
            'email' => Yii::t('app', 'Почта на которую будут отправляться показания жильцов'),
            'useemail' => Yii::t('app', 'Использовать почту для получение показаний'),
        ];
    }
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    public static function getStatusesArray()
    {
        return [
            self::STATUS_OFF => Yii::t('app', 'STATUS_OFF'),
            self::STATUS_ON => Yii::t('app', 'STATUS_ON'),
        ];
    }
    public function getAreaCount($status){
		return count( self::find()->where(['status' => $status])->all() );
    }    
    public static function getWorkArea()
    {
        return ArrayHelper::map(self::find()->where(['status' => '1'])->orderBy('title')->all(), 'id', 'title');
    }
    public function getTypesName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }
    public static function getTypesArray()
    {
        return [
            self::TYPE_TSG => Yii::t('app', 'TYPE_TSG'),
            self::TYPE_UK => Yii::t('app', 'TYPE_UK'),
            self::TYPE_KP => Yii::t('app', 'TYPE_KP'),
            self::TYPE_SNT => Yii::t('app', 'TYPE_SNT'),
            self::TYPE_SMD => Yii::t('app', 'TYPE_SMD'),
        ];
    }
    public function StatusView($status) 
    {
            switch ($status) {
                case Area::STATUS_ON:
                    $statname = Yii::t('app', 'STATUS_ON');
                    $class = 'success';
                    break;
                case Area::STATUS_OFF:
                    $statname = Yii::t('app', 'STATUS_OFF');
                    $class = 'warning';
                    break;
                default:
                    $class = 'default';
            };
            $html = Html::tag('span', Html::encode($statname), ['class' => 'label label-' . $class]);

            return $status === null ? $column->grid->emptyCell : $html;
    }
    public function getColorType($type) 
    {
        switch ($type) {
            case Area::TYPE_TSG:
                $class = 'blue';//'primary';
                break;
            case Area::TYPE_UK:
                $class = 'lightskyblue ';//'info';
                break;
            case Area::TYPE_KP:
                $class = 'green';//'success';
                break;
            case Area::TYPE_SNT:
                $class = 'orange';//'warning';
                break;
            case Area::TYPE_SMD:
                $class = 'red';//'danger';
                break;
        };
        return $class;
    }
    public function getArrIdsArea($type)
    {
    	return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ON,'type'=>$type])->all(), 'id', 'title');
    }
    public function TypeView($type) 
    {
        switch ($type) {
            case Area::TYPE_TSG:
                $statname = Yii::t('app', 'TYPE_TSG');
                $class = 'primary';
                break;
            case Area::TYPE_UK:
                $statname = Yii::t('app', 'TYPE_UK');
                $class = 'info';
                break;
            case Area::TYPE_KP:
                $statname = Yii::t('app', 'TYPE_KP');
                $class = 'success';
                break;
            case Area::TYPE_SNT:
                $statname = Yii::t('app', 'TYPE_SNT');
                $class = 'warning';
                break;
            case Area::TYPE_SMD:
                $statname = Yii::t('app', 'TYPE_SMD');
                $class = 'danger';
                break;
        };
        $html = Html::tag('span', Html::encode($statname), ['class' => 'label label-' . $class]);

        return $type === null ? $column->grid->emptyCell : $html;
    }
	public static function getActiveArea()
    {
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ON])->all(), 'id', 'title');
    }
    public function getListIdsOneType($type)
    {
    	return ArrayHelper::map(self::find()->where(['type' => $type])->all(), 'id', 'title');
    }
	public static function getModuleArea($id)
    {
        return ArrayHelper::map(self::find()->where(['id' => $id])->one(), 'id', 'module');
    }    
    public function getTitleLogo($id)
    {
    	$area = self::find()->where(['id' => $id])->one();
		$arr = [
				'id' => $area->id,
				'title' => $area->title,
				'logo' => $area->logo,
			];
		return $arr;
    }
     public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function getHTMLLeftLayoutArea(){
    	$area		= static::findOne(['id' => \Yii::$app->user->identity->id_org]);
		$area_logo	= json_decode($area->logo);
		$org_logo	= ($area_logo) ? Html::img('/'.$area_logo[0], ['class' => 'img-circle']) : '<span class="glyphicon glyphicon-home" style="color:white;font-size: 30px;"></span>';
		$org_title	= $area->title;
    	$html = "
        <div class=\"user-panel\">
            <div class=\"pull-left image\">
                $org_logo
            </div>
            <div class=\"pull-left info\">
                <p>$org_title</p>
            </div>
        </div>    	
    	";
    	return $html;
    }
    public function getMailArea($id)
    {
    	$area = Area::findById($id);
    	if ($area->useemail == 1){
    		return $area->email;
    	}else{return false;}
    }
    public function getAccessCheck($name)
    {
    	if(Yii::$app->user->identity->role == 'user' || Yii::$app->user->identity->role == 'government'){
    		$id_a = Mkd::findById(Yii::$app->user->identity->id_a)->id_a;
    		$area = self::find()->where(['id' => $id_a])->one();
    	}else{
    		$area = self::find()->where(['id' => Yii::$app->user->identity->id_org])->one();
    	}
    	$id_m = Module::findByAction($name)->id;
    	
    	$arr_mod = json_decode($area->module);
    	return ($arr_mod && in_array($id_m,$arr_mod) || \Yii::$app->user->identity->role == 'root' ) ? true : false;
    }
}
