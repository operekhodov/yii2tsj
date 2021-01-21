<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Area;

class Mkd extends \yii\db\ActiveRecord
{
    const STATUS_OFF	= 0;
    const STATUS_ON 	= 1;
	
    public static function tableName()
    {
        return 'mkd';
    }
    public function rules()
    {
        return [
            [['id_a','cadastral','size','status'], 'integer'],
            [['city', 'street', 'number_house', 'count_apartment'], 'required'],
            [['city', 'street', 'number_house', 'count_porch', 'count_apartment', 'note','count_floor','geo'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_a' => Yii::t('app', 'Юр. лицо'),
            'city' => Yii::t('app', 'Город'),
            'street' => Yii::t('app', 'Улица'),
            'number_house' => Yii::t('app', 'Номер дома'),
            'count_porch' => Yii::t('app', 'Количество подъездов'),
            'count_floor' => Yii::t('app', 'Количество этажей'),
            'count_apartment' => Yii::t('app', 'Количество квартир'),
            'note' => Yii::t('app', 'Заметка'),
            'cadastral' => Yii::t('app', 'Кадастровый номер'),
            'size' => Yii::t('app', 'Площадь'),
            'status' => Yii::t('app', 'STATUS'),
            
        ];
    }
    public function actAddMkd($city, $street, $num_house, $geo)
    {
    	/*
    		Проверка существует такой МКД в БД или нет
    	*/
    	$if = Mkd::getIDA($city,$street,$num_house);
    	if( $if ){
    		return $if;
    	}else{
	    	/*
	    		Создание нового
	    	*/
	    	$mkd = new Mkd();
	    	$mkd->city				= $city;
			$mkd->street 			= $street;
			$mkd->number_house		= $num_house;
			$mkd->geo				= $geo;
			$mkd->count_apartment	= '1';
			$mkd->status			= '0';
			$mkd->save();
			return $mkd->id;
    	}
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
    public function getMkdDataLocality()
    {
    	 return ArrayHelper::map(self::find()->orderBy('city')->all(), 'id', 'city');
	}
    public function getMkdDataStreet()
    {
    	return ArrayHelper::map(self::find()->orderBy('city')->all(), 'id', 'street');
	}
    public function getMkdDataNum_House()
    {
    	return ArrayHelper::map(self::find()->orderBy('city')->all(), 'id', 'number_house');
	}
	public function getOneMkdIDThisArea($id)
	{
		$data = self::find()->where(['id'=>$id])->all();
		foreach($data as $mkd) {
			$arr[$mkd->id] =( $mkd->city.', '.$mkd->street.', '.$mkd->number_house ) ;
		}
		return $arr;
	}	
	public function getAllMkdIDThisArea($id_org)
	{
		$data = self::find()->where(['id_a'=>$id_org])->all();
		foreach($data as $mkd) {
			$arr[] =( $mkd->id ) ;
		}
		return $arr;
	}
	public function getAllMkdThisAreaType($type)
	{
		$allMkdThisAreaType = array_keys(Area::getListIdsOneType($type));
		foreach($allMkdThisAreaType as $id_org){
			$data = self::find()->where(['id_a'=>$id_org])->all();
			foreach($data as $mkd) {
				$arr[] =( $mkd->id ) ;
			}
		}
		return $arr;		
		
	}
    public function getMkdCount($status)
    {
		return count( self::find()->where(['status' => $status])->all() );
    }	
	public function getAllMkd()
	{
		$data = self::find()->all();
		foreach($data as $mkd) {
			$arr[$mkd->id] =( $mkd->city.', '.$mkd->street.', '.$mkd->number_house ) ;
		}
		return $arr;
	}
    public function getAllIdMkdThisArea($id_org)
    {
    	return ArrayHelper::map(self::find()->where(['id_a'=>$id_org])->all(), 'id','id');
    }	
	public function getAllMkdThisArea($id_org)
	{
		$data = self::find()->where(['id_a'=>$id_org])->all();
		foreach($data as $mkd) {
			$arr[$mkd->id] =( $mkd->city.', '.$mkd->street.', '.$mkd->number_house ) ;
		}
		return $arr;
	}
	public function getAddressMkdByID($id)
	{
		$mkd = self::find()->where(['id'=>$id])->one();
		return  $mkd->city.', '.$mkd->street.', '.$mkd->number_house;
	}	
	public function getMkdData()
	{
		$data = ArrayHelper::map(self::find()->all(), 'city','street','number_house');
		$arr = array();
		foreach($data as $key => $value){
			foreach($value as $keys => $values){
				$adr = $keys.', '.$values.', '.$key;
				array_push($arr, $adr);
				$adr = null;
			}
		}
		return $arr;
		
	}
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'id_a']);
    }
    public function getAreaInfo()
    {	
    	if($this->area->logo){
    		$area_logo = json_decode($this->area->logo);
    		$title = Html::img('/'.$area_logo[0], ['class' => 'img-thumbnail hcs-thumbnail','style'=>'width:100px;']).' <h4>'.$this->area->title.'</h4>';
    	}else{
    		$title = $this->area->title;
    	}
    	return $title;
    }
    public function getAreaHTMLLogo()
    {	
    	if($this->area->logo){
    		$area_logo = json_decode($this->area->logo);
    		$title = 
    		'
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
'.Html::img('/'.$area_logo[0], ['class' => 'img-thumbnail hcs-thumbnail','style'=>'width:100px;']).'		
		</div>
		<div class="col-md-6">
 <h4>'.$this->area->title.'</h4>		
		</div>
	</div>
</div>';
    	}else{
    		$title = ' <h4>'.$this->area->title.'</h4>';
    	}
    	return $title;
    }    
    public function getAreaHTMLTitle()
    {
    	return ' <h4>'.$this->area->title.'</h4>';
    }
    public function getAreaHTMLType()
    {
    	return Area::TypeView($this->area->type);
    }    
    public function getAreaTitle() 
    {
        return $this->area->title;
    }
    public static function getIDA($city,$street,$num_house)
    {
    	$mkd =	static::findOne(['city' => $city,'street' => $street,'number_house' => $num_house]);
    	if($mkd){
    		return $mkd->id;
    	}else{ return false;}
    }
    public function getOrgId($id)
    {
		$mkd =	static::findOne(['id' => $id]);
    	if($mkd){
    		return $mkd->id;
    	}else{ return false;}
    }
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function getAllCity()
    {
    	$data = ArrayHelper::map(self::find()->groupBy('city')->all(), 'id','city');
    	return $data;
    }
	/*
	- Количество квартир в этом МКД							$mkd->count_apartment
	- Сколько на текущий момент зарегистрировано юзеров  	User::getCountAllUsersThisArea($id_a)
	- Тип обслуживающей организации: УК/ТСЖ					$area
	- Название УК/ТСЖ										$area->title
	- Логотип УК/ТСЖ										$area->logo
	- Контактная информация									$area->info
	- Тарифы												$tariff			???
	*/
	public function getInfoForMaps($type,$city,$center)
	{
		if( $type == 'all' ){
			$data = self::find()->andWhere(['city'=>$city])->all();
		}else{
			$arr_ids_areas = array_keys(Area::getArrIdsArea($type));
			$data = self::find()->where(['in','id_a',$arr_ids_areas])->andWhere(['city'=>$city])->all();
		}
		foreach($data as $mkd) {
			$geo	= json_decode($mkd->geo);
			$a		= $geo->geo[0];
			$b		= $geo->geo[1];
			
			$count_users = User::getCountAllUsersThisArea($mkd->id_a);
			
			$area		= Area::findById($mkd->id_a);
			$arr_img	= json_decode($area->logo);
			$logo_area	= $arr_img[0]; 
			$type		= Area::TypeView($area->type);
			$color		= Area::getColorType($area->type);
			
			$data .= 
			"
			    var placemark$mkd->id = new ymaps.Placemark([$a, $b], {
			        balloonContentHeader: '$mkd->city, $mkd->street, $mkd->number_house<br>' +
			            '<span class=\"description\">$type $area->title</span>',
			        balloonContentBody: '<img src=\"/$logo_area\" height=\"50\" width=\"50\"> ' +
			            '$area->info <br>' +
			            'Количество квартир: $mkd->count_apartment <br>' +
			            'Зарегистрированно пользователей: $count_users',
			        balloonContentFooter: 'Информация о тарифах: ',
			        hintContent: '$mkd->city, $mkd->street, $mkd->number_house'
			    }, {
            iconColor:'$color',
        });
			    myMap.geoObjects.add(placemark$mkd->id);
			    placemark$mkd->id.balloon.close();
			";
		}
		$geo	= json_decode($center);
		$a		= $geo->geo[0];
		$b		= $geo->geo[1];
		
		  $data = " ymaps.ready(init); 
					function init() {
			    		var myMap = new ymaps.Map('map', {
			            	center: [$a, $b],
			            	zoom: 14
			        	});
					$data
					}";		
		return $data;
		
	}
	
}
