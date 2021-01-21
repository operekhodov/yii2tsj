<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use app\models\Mkd;
use yii\helpers\StringHelper;
use app\models\Area;

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED	= 0;
    const STATUS_ACTIVE 	= 1;
    const STATUS_WAIT		= 2;
    
    const ROLE_ROOT			= 'root';
    const ROLE_ADMIN		= 'admin';
    const ROLE_MODER		= 'moder';
    
    const ROLE_BOSS			= 'boss';
    const ROLE_DISPATCHER	= 'dispatcher';
    const ROLE_SPEC			= 'spec';
    const ROLE_AGENT		= 'agent';
    
    const ROLE_GOVERMENT	= 'government';
    const ROLE_USER			= 'user';
    const ROLE_HALFUSER		= 'halfuser';
    

    const T_OWNER			= 0;
    const T_TENANT			= 1;
    const T_FAMILYM 		= 2;
    const T_REPRESENTATIVE	= 3;

	const TU_RESIDENTIAL	= 0;
	const TU_OFFICE			= 1;
	const TU_RETAIL 		= 2;
	const TU_OTHER			= 3;
	const TU_PARKING		= 4;

    public $imageFiles;
    public $newPassword;
    public $newPasswordRepeat;
    public $proof_img;
    
    public static function tableName()
    {
        return 'user';
    }
    public function rules()
    {
        return [
        	['phonenumber','string']

        ];
    }
    public function attributeLabels()
    {
        return [
            'id'			=> Yii::t('app','ID'),
            'created_at'	=> Yii::t('app','CREATEDDATE'),
            'updated_at'	=> Yii::t('app','UPDATE_AT'),
            'username'		=> Yii::t('app','USERNAME'),
            'password_hash' => Yii::t('app','PASSWORD'),
            'status'		=> Yii::t('app','STATUS'),
            'role'			=> Yii::t('app','ROLE'),
            'id_a'			=> Yii::t('app','ID_A'),
			'type'			=> Yii::t('app','M_TYPE'),
			'nroom'			=> Yii::t('app','M_NROOM'),
			'nexit'			=> Yii::t('app','M_NEXIT'),
			'nfloor'		=> Yii::t('app','M_NFLOOR'),
			'nid'			=> Yii::t('app','M_NID'),
			'space'			=> Yii::t('app','M_SPACE'),
			'share'			=> Yii::t('app','M_SHARE'),
			'ncad'			=> Yii::t('app','M_NCAD'),
			'typeuse'		=> Yii::t('app','M_TYPEUSE'),
			'email'			=> Yii::t('app','M_EMAIL'),
			'fio'			=> Yii::t('app','Отчество'),
			'lname'			=> Yii::t('app','Фамилия'),
			'fname'			=> Yii::t('app','Имя'),
            'imageFiles'	=> Yii::t('app','Фото'),
            'proof_img'		=> Yii::t('app','Фото документа подтверждающего права собственности'),
            'locality'		=> Yii::t('app','Населенный пункт'),
            'street'		=> Yii::t('app','Улица'),
            'num_house'		=> Yii::t('app','Номер дома'),
            'phonenumber'	=> Yii::t('app','Номер телефона'),
        ];
    }
    public function getDataThisUser()
    {
		$data = self::find()->where(['id'=>Yii::$app->user->identity->id])->one();
		$arr['id'] =( $data->id ) ;
		$arr['role'] =( $data->role ) ;
		$arr['lname'] =( $data->lname ) ;
		$arr['fname'] =( $data->fname ) ;
		$arr['fio'] =( $data->fio ) ;
		$arr['phonenumber'] =( $data->phonenumber ) ;
		$arr['foto'] =( $data->foto ) ;
		$arr['locality'] =( $data->locality ) ;
		$arr['street'] =( $data->street ) ;
		$arr['num_house'] =( $data->num_house ) ;

		return $arr;    	
    }
    public function getDataThisOrg()
    {
		$data = self::find()->where(['id_org'=>Yii::$app->user->identity->id_org])->all();
		if (!empty($data)) {
			foreach($data as $user) {
				if($user->role != 'user' && $user->role != 'government'){
					$arr[$user->id] = [ 
						array('role' => $user->role ),
						array('lname' =>$user->lname ),
						array('fname' =>$user->fname ),
						array('fio' =>$user->fio ),
						array('phonenumber' =>$user->phonenumber ),
						array('foto' =>$user->foto )];
				}
			}
		}
		return (!empty($arr)) ? $arr : false;
    }
    public function getDataThisMkd()
    {
    	if(Yii::$app->user->identity->id_a != null){
			$data = self::find()->where(['id_a'=>Yii::$app->user->identity->id_a])->all();
    	}else{
    		return false;
    	}
		if (!empty($data)) {
			foreach($data as $user) {
				if($user->role == 'user' || $user->role == 'government'){
					$arr[$user->id] = [ 
						array('id' => $user->id),
						array('role' => $user->role ),
						array('lname' =>$user->lname ),
						array('fname' =>$user->fname ),
						array('fio' =>$user->fio ),
						array('phonenumber' =>$user->phonenumber ),
						array('foto' =>$user->foto ),
						array('locality' =>$user->locality ),
						array('street' =>$user->street ),
						array('num_house' =>$user->num_house )];
				}
			}
		}
		return (!empty($arr)) ? $arr : false;    	
    }
    public function getAreaUName()
    {
        return ArrayHelper::getValue(Area::getActiveArea(), $this->id_a);
    }    
    public function getTypeuseName()
    {
        return ArrayHelper::getValue(self::getTypeuseArray(), $this->typeuse);
    }
    public static function getTypeuseArray()
    {
        return [
            self::TU_RESIDENTIAL	=>  Yii::t('app', 'TU_RESIDENTIAL'),
            self::TU_OFFICE			=>  Yii::t('app', 'TU_OFFICE'),
            self::TU_RETAIL 		=>  Yii::t('app', 'TU_RETAIL'),
            self::TU_OTHER			=>  Yii::t('app', 'TU_OTHER'),
            self::TU_PARKING		=>  Yii::t('app', 'TU_PARKING'),
        ];
    }    
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypeArray(), $this->type);
    }
    public static function getTypeArray()
    {
        return [
            self::T_OWNER			=>  Yii::t('app', 'T_OWNER'),
            self::T_TENANT			=>  Yii::t('app', 'T_TENANT'),
            self::T_FAMILYM 		=>  Yii::t('app', 'T_FAMILYM'),
            self::T_REPRESENTATIVE	=>  Yii::t('app', 'T_REPRESENTATIVE'),
        ];
    }    
    public function getRolesName()
    {
        return ArrayHelper::getValue(self::getRolesArray(), $this->role);
    }
    public static function getRolesArray()
    {
        return [
			self::ROLE_ROOT			=> Yii::t('app','ROLE_ROOT'),
			self::ROLE_ADMIN		=> Yii::t('app','ROLE_ADMIN'),
			self::ROLE_MODER		=> Yii::t('app','ROLE_MODER'),
			self::ROLE_BOSS			=> Yii::t('app','ROLE_BOSS'),
			self::ROLE_DISPATCHER	=> Yii::t('app','ROLE_DISPATCHER'),
			self::ROLE_SPEC			=> Yii::t('app','ROLE_SPEC'),
			self::ROLE_AGENT		=> Yii::t('app','ROLE_AGENT'),
			self::ROLE_GOVERMENT	=> Yii::t('app','ROLE_GOVERMENT'),
			self::ROLE_USER			=> Yii::t('app','ROLE_USER'),
			self::ROLE_HALFUSER     => Yii::t('app','ROLE_HALFUSER'),
        ];
    }
	public static function getSystemUArray()
    {
        return [
			self::ROLE_ROOT			=> Yii::t('app','ROLE_ROOT'),
			self::ROLE_ADMIN		=> Yii::t('app','ROLE_ADMIN'),
			self::ROLE_MODER		=> Yii::t('app','ROLE_MODER'),
			self::ROLE_BOSS			=> Yii::t('app','ROLE_BOSS'),
			self::ROLE_DISPATCHER	=> Yii::t('app','ROLE_DISPATCHER'),
			self::ROLE_SPEC			=> Yii::t('app','ROLE_SPEC'),
			self::ROLE_AGENT		=> Yii::t('app','ROLE_AGENT'),
        ];
    }    
    public static function getRolesPersonalArray()
    {
        return [
			self::ROLE_BOSS			=> Yii::t('app','ROLE_BOSS'),
			self::ROLE_DISPATCHER	=> Yii::t('app','ROLE_DISPATCHER'),
			self::ROLE_SPEC			=> Yii::t('app','ROLE_SPEC'),
			self::ROLE_AGENT		=> Yii::t('app','ROLE_AGENT'),
        ];
    }    
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }
    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED =>  Yii::t('app', 'STATUS_BLOCKED'),
            self::STATUS_ACTIVE =>  Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_WAIT =>  Yii::t('app', 'STATUS_WAIT'),
        ];
    }
    public function getId_a0()
    {
        return $this->hasOne(Area::className(), ['id' => 'id_a']);
    }
    public function getId_a0Inn() 
    {
		return $this->id_a0->title;
    }
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'id_a']);
    }
    public function getAreaInn() 
    {
		return $this->area->title;
    }
    public function getFullNameAndFullAddress()
    { 
    	$fname = substr( StringHelper::truncate($this->fname, 1) ,0,3);
    	$fio = substr( StringHelper::truncate($this->fio, 1) ,0,3);

        return $this->lname.' '.$fname.' '.$fio.' ['.$this->locality.', '.$this->street.', '.$this->num_house.', '.$this->nroom.']['.$this->nid.']';
    }
    public static function getActiveUser()
    {
        return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE,'role'=>['government','user']])->orderBy('lname')->all(), 'id', 'FullNameAndFullAddress');
    }
    public static function getActiveUserFullName($id)
    {
        $arr = static::findOne(['id' => $id]);
        return $arr->lname.' '.$arr->fname.' '.$arr->fio;
        
    }    
    public static function getWorkUser($id_a)
    {
        return ArrayHelper::map(self::find()->where(['role' => 'spec', 'id_a' => $id_a])->orderBy('lname')->all(), 'id', 'lname');
    }
    public function getAllUsersThisMkd($id_mkd)
    {
    	return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE, 'id_a' => $id_mkd,'role'=>['government','user']])->all(), 'id','id');
    }
    public function getArrUsrIdThisOrg($id_org)
    {
    	$arr_mkd_id = Mkd::getAllIdMkdThisArea($id_org);
    	return ArrayHelper::map(self::find()->where(['id_org' => $id_org])->orWhere(['id_mkd' => $arr_mkd_id])->all(), 'id','id');
    }    
    public function getAllUsersThisArea($id_org)
    {
    	$arr_mkd_id = Mkd::getAllIdMkdThisArea($id_org);
    	return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE, 'id_a' => $arr_mkd_id,'role'=>['government','user']])->all(), 'id','id');
    }    
    public function getCountAllUsersThisArea($id_a)
    {
    	return count(ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE, 'id_a' => $id_a, 'role' => ['government','user']])->all(), 'id','id'));
    }
    public function getAllUsersNameThisMkd($id_mkd)
    {
    	return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE, 'id_a' => $id_mkd,'role'=>['government','user']])->all(), 'id','FullNameAndFullAddress');
    }    
    public function getAllUsersNameThisArea($id_org)
    {
    	$arr_mkd_id = Mkd::getAllIdMkdThisArea($id_org);
    	return ArrayHelper::map(self::find()->where(['status' => self::STATUS_ACTIVE, 'id_a' => $arr_mkd_id,'role'=>['government','user']])->all(), 'id','FullNameAndFullAddress');
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    public static function findIdentity($id)
    {
        return static::find()->where('id = :id and status != :status', ['id'=>$id, 'status'=>self::STATUS_BLOCKED])->one();
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function validBearer($id_u,$authKey) 
    {
    	$user =	static::findOne(['id' => $id_u, 'auth_key' => $authKey]);
    	if ($user && $authKey) {
    		return true;
    	}else{ return false;}
    }
    public static function getIDA($id_u)
    {
    	$user =	static::findOne(['id' => $id_u]);
    	if($user){
    		return $user->id_a;
    	}else{ return false;}
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    public static function findByPhonenumber($phonenumber)
    {
        return static::findOne(['phonenumber' => $phonenumber]);
    }
    public static function findByPhone_and_mail($phonenumber,$email)
    {
        return static::findOne(['phonenumber' => $phonenumber, 'email' => $email]);
    }      
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    public function getThisAreaUsersCount($id_a,$status)
    {
		return count( self::find()->where(['id_a' => $id_a, 'status' => $status, 'role' => 'user'])->all() );
    }
    public function getAllAreaUsersCount()
    {
    	return $arr = array(
    		'hu'  => count( self::find()->where([ 'role' => 'halfuser'])->all() ),
    		'u'   => count( self::find()->where([ 'role' => 'user'])->all() ),
    		'g'   => count( self::find()->where([ 'role' => 'government'])->all() ),
    		'w'	  => count( self::find()->where([ 'status' => '2'])->all() ),
    		'b'	  => count( self::find()->where([ 'status' => '0'])->all() ));
    	
    }
    public function StatusView($status) 
    {
        switch ($status) {
            case User::STATUS_BLOCKED:
                $statname = Yii::t('app', 'STATUS_BLOCKED');
                $class = 'danger';
                break;
            case User::STATUS_ACTIVE:
                $statname = Yii::t('app', 'STATUS_ACTIVE');
                $class = 'success';
                break;
            case User::STATUS_WAIT:
                $statname = Yii::t('app', 'STATUS_WAIT');
                $class = 'warning';
                break;
        };
        $html = Html::tag('span', Html::encode($statname), ['class' => 'label label-' . $class]);

        return $status === null ? $column->grid->emptyCell : $html;
    }
    public function sendMail($id,$model,$setSubject,$setHtmlBody)
    {
		$user = User::findById($id);
		
		if ($user->email && json_decode($user->email,true)['use'] == 1) {
			Yii::$app->mailer->compose()
			    ->setFrom('tsj@wtot.ru')
			    ->setTo(json_decode($user->email,true)['email'])
			    ->setSubject($setSubject)
			    ->setHtmlBody($setHtmlBody)
			    ->send();				
		}		
		return true;
    }    
    
}
