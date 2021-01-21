<?php

namespace app\models;

use Yii;
use app\models\Mkd;

class SignupForm extends \yii\db\ActiveRecord
{
	public $fname;
    public $lname;
    public $adr;
    public $password;
    public $repeatPass;
    public $phonenumber;
	public $nroom;
	public $fio;
	public $geo;
	
    public function rules()
    {
        return [
			[['phonenumber','lname','adr','password','repeatPass'], 'required'],
			[['lname','adr'],'string'],
            [['phonenumber'], 'filter', 'filter' => function($value){
                $value = str_replace(['(', ')', '-','+',' '], '', $value);
                return $value;
            }],
            ['phonenumber', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'SUF_error_phone')],			
            ['password', 'string', 'min' => 6],
            ['repeatPass', 'compare', 'compareAttribute' => 'password'],
            [['fio','fname','nroom','geo'],'safe']
         ];
    }
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'SUF_password'),
            'repeatPass' => Yii::t('app', 'SUF_repeatPass'),
            'phonenumber' => Yii::t('app', 'SUF_phonenumber'),
            'lname' => Yii::t('app', 'SUF_LNAME'),
            'fname' => Yii::t('app', 'SUF_FNAME'),
            'adr' => Yii::t('app', 'SUF_ADR'),
        ];
    }
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->phonenumber = $this->phonenumber;
            $user->lname = $this->lname;
            $user->fname = $this->fname;
            $user->fio	 = $this->fio;
            $user->nroom = $this->nroom;
            $adr = explode(',',$this->adr);
            $user->locality = $adr[0];
            $user->street = substr($adr[1],1);
            $user->num_house = substr($adr[2],1);
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->role = 'halfuser';
            $user->id_a = Mkd::getIDA($user->locality,$user->street,$user->num_house);
            $t	= Mkd::actAddMkd($user->locality, $user->street, $user->num_house, $this->geo);
            $user->id_mkd = $t;
            if ($user->save()) {
                return $user;
            }else{
            	Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'SUF_error_data'));
            }
            
        }
		return null;
    }
    public function encode($unencoded,$key)
    {//Шифруем
		$string=base64_encode($unencoded);//Переводим в base64
		
		$arr=array();//Это массив
		$x=0;
		while ($x++< strlen($string)) {//Цикл
			$arr[$x-1] = md5(md5($key.$string[$x-1]).$key);//Почти чистый md5
			$newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];//Склеиваем символы
		}
		return $newstr;//Вертаем строку
	}
	public function decode($encoded, $key)
	{//расшифровываем
		$strofsym="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";//Символы, с которых состоит base64-ключ
		$x=0;
		while ($x++<= strlen($strofsym)) {//Цикл
			$tmp = md5(md5($key.$strofsym[$x-1]).$key);//Хеш, который соответствует символу, на который его заменят.
			$encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);//Заменяем №3,6,1,2 из хеша на символ
		}
		return base64_decode($encoded);//Вертаем расшифрованную строку
	}
}
