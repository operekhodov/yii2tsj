<?php

namespace app\models;

use Yii;

class Add_user extends \yii\db\ActiveRecord
{

	public $fio;
    public $password;
    public $repeatPass;
    public $phonenumber;
	public $nroom;
	public $id_mkd;

	
    public function rules()
    {
        return [
			[['phonenumber','fio','password','repeatPass','id_mkd'], 'required'],
			[['nroom','fio'],'string'],
            [['phonenumber'], 'filter', 'filter' => function($value){
                $value = str_replace(['(', ')', '-','+',' '], '', $value);
                return $value;
            }],
            ['phonenumber', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'SUF_error_phone')],			
            ['password', 'string', 'min' => 6],
            ['repeatPass', 'compare', 'compareAttribute' => 'password'],
         ];
    }
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'SUF_password'),
            'repeatPass' => Yii::t('app', 'SUF_repeatPass'),
            'phonenumber' => Yii::t('app', 'SUF_phonenumber'),
            'fio' => Yii::t('app', 'ФИО'),
            'nroom' => Yii::t('app', 'PF_nroom'),
        ];
    }
    public function add_u($role)
    {
        if ($this->validate()) {
            $user = new User();
            $user->phonenumber = $this->phonenumber;
            $fio		 = explode(' ',$this->fio);
            $user->lname = $fio[0];
            $user->fname = $fio[1];
            $user->fio	 = $fio[2];
            $user->nroom = $this->nroom;
            
            $adr = Mkd::findById($this->id_mkd);
            
            $user->locality 	= $adr->city;
            $user->street		= $adr->street;
            $user->num_house	= $adr->number_house;
            $user->setPassword($this->password);
            $user->status		= User::STATUS_ACTIVE;
            $user->role 		= $role;
            $user->id_a 		= $this->id_mkd;
            $user->id_mkd 		= $this->id_mkd;
            
            if ($user->save()) {
                return $user;
            }else{
            	Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'SUF_error_data'));
            }
            
        }
		return null;
     }

}
