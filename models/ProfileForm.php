<?php

namespace app\models;

use Yii;
use app\models\Mkd;
use app\models\User;
use yii\web\UploadedFile;

class ProfileForm extends \yii\db\ActiveRecord
{
    public $lname; //'Фамилия'
    public $fname; //'Имя'
    public $fio; //'Отчество'
    public $type; //'Статус жильца'
    public $typeuse; //'Назначение помещения'
    public $nexit; //'Подъезд'
    public $nfloor; //'Этаж'
    public $nroom; //'Номер помещения'
    public $nid; //'Лицевой счет'
    public $space; //'Площадь (кв.м)'
    public $share; //'Доля в общей площади'
    public $ncad; //'Кадастровый номер'
    public $email; //'E-mail'
    public $adr; //'Адрес'
	public $imageFiles; //'Фото профиля'
	public $proof_img; //'Фото документа подтверждающего права собственности'
	
	public $phonenumber;
	public $newPassword;
	public $newPasswordRepeat;
	public $status;
	public $role_u;
	public $notice;
	
    public function rules()
    {
        return [
			[['lname','adr'], 'required'],
            [['fio','fname','lname','adr','nroom','nexit','nfloor','nid','ncad','email','status','role_u','phonenumber','notice'], 'string'],
            [['space','share'],'integer'],
            ['type', 'default', 'value' => User::T_OWNER],
            ['type', 'in', 'range' => array_keys(User::getTypeArray())],
            ['typeuse', 'default', 'value' => User::TU_RESIDENTIAL],
            ['typeuse', 'in', 'range' => array_keys(User::getTypeuseArray())],
            [['imageFiles','proof_img'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 1],
            [['phonenumber'], 'filter', 'filter' => function($value){
                $value = str_replace(['(', ')', '-','+',' '], '', $value);
                return substr($value,1);
            }],
            //['phonenumber', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'SUF_error_phone')],
            ['newPassword', 'string', 'min' => 6],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],            
         ];
    }

    public function attributeLabels()
    {
        return [
        	'phonenumber' => Yii::t('app', 'PF_phonenumber'),
        	'status' => Yii::t('app', 'PF_status'),
        	'role_u' => Yii::t('app', 'PF_role'),
        	'newPassword' => Yii::t('app', 'PF_newPassword'),
        	'newPasswordRepeat' => Yii::t('app', 'PF_newPasswordRepeat'),
			'lname' => Yii::t('app', 'PF_lname'),
			'fname' => Yii::t('app', 'PF_fname'),
			'fio' => Yii::t('app', 'PF_fio'),
			'adr' => Yii::t('app','PF_adr'),
			'type' => Yii::t('app', 'PF_type'),
			'typeuse' => Yii::t('app', 'PF_typeuse'),
			'nexit' => Yii::t('app', 'PF_nexit'),
			'nfloor' => Yii::t('app', 'PF_nfloor'),
			'nroom' => Yii::t('app', 'PF_nroom'),
			'nid' => Yii::t('app', 'PF_nid'),
			'space' => Yii::t('app', 'PF_space'),
			'share' => Yii::t('app', 'PF_share'),
			'ncad' => Yii::t('app', 'PF_ncad'),
			'email' => Yii::t('app', 'PF_email'),
			'imageFiles' => Yii::t('app', 'PF_imageFiles'),
			'proof_img' => Yii::t('app', 'PF_proof_img'),
			'notice' => Yii::t('app', 'PF_notice'),
        ];
    }

    public function update_profile($id)
    {
        if ($this->validate()) {
			$user = User::findById($id);
			if($this->phonenumber){
				if ($user->phonenumber != $this->phonenumber){
					if(User::findByPhonenumber($this->phonenumber)){
						Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'SUF_error_phone'));
						return false;
					}else{ 
						$user->phonenumber = $this->phonenumber; }
				}
				$user->status = $this->status;
				$user->role = $this->role_u;
				if ($this->newPassword) {$user->setPassword($this->newPassword);}
			}
				
            $user->lname = $this->lname;
			$user->fname = $this->fname;
			$user->fio = $this->fio;
			$user->type = $this->type;
			$user->typeuse = $this->typeuse;
			$user->nexit = $this->nexit;
			$user->nfloor = $this->nfloor;
			$user->nroom = $this->nroom;
			$user->nid = $this->nid;
			$user->space = $this->space;
			$user->share = $this->share;
			$user->ncad = $this->ncad;
			$buf = ['email'=>$this->email,'use'=>$this->notice];
			$user->email = json_encode($buf);
			$adr = explode(',',$this->adr);
            $user->locality = $adr[0];
            $user->street = substr($adr[1],1);
            $user->num_house = substr($adr[2],1);
            if($user->id_a != Mkd::getIDA($user->locality,$user->street,$user->num_house)) {
				$user->id_a = Mkd::getIDA($user->locality,$user->street,$user->num_house);
				$user->status = 2;
            }
			
			$this->imageFiles  = UploadedFile::getInstances($this, 'imageFiles');
			if($this->imageFiles) {
				$arr_img = array();
				foreach ($this->imageFiles as $file) {
					$filename = uniqid().".{$file->extension}";
					$url_filename = "uploads/".$filename;
					$file->saveAs($url_filename);
					array_push($arr_img,$url_filename);
				}
				$this->imageFiles = null;
				$user->foto = json_encode($arr_img);
			}
			
			$this->proof_img  = UploadedFile::getInstances($this, 'proof_img');
			if($this->proof_img) {
				$arr_img = array();
				foreach ($this->proof_img as $file) {
					$filename = uniqid().".{$file->extension}";
					$url_filename = "uploads/".$filename;
					$file->saveAs($url_filename);
					array_push($arr_img,$url_filename);
				}
				$this->proof_img = null;
				$user->proof = json_encode($arr_img);            
			}
			
			
				
            if ($user->save()) {
                return $user;
            }else{
            	Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'SUF_error_data'));
            }
            
        }
		return null;
     }
		
    public function encode($unencoded,$key){//Шифруем
		$string=base64_encode($unencoded);//Переводим в base64
		
		$arr=array();//Это массив
		$x=0;
		while ($x++< strlen($string)) {//Цикл
			$arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//Почти чистый md5
			$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
		}
		return $newstr;//Вертаем строку
	}

	public function decode($encoded, $key){//расшифровываем
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
		$x=0;
		while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
		}
		return base64_decode($encoded);//Вертаем расшифрованную строку
	}     
}
