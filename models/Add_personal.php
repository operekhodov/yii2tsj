<?php

namespace app\models;

use Yii;

class Add_personal extends \yii\db\ActiveRecord
{

	public $fio;
    public $password;
    public $repeatPass;
    public $phonenumber;
	public $id_mkd;
	public $id_org;
	public $role;

    public function rules()
    {
        return [
			[['role','phonenumber','fio','password','repeatPass','id_mkd','id_org'], 'required'],
			[['role','fio'],'string'],
            [['phonenumber'], 'filter', 'filter' => function($value){
                $value = str_replace(['(', ')', '-','+',' '], '', $value);
                return substr($value,1);
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
            'role' => Yii::t('app', 'Роль'),
        ];
    }

    public function add_pf()
    {
        if ($this->validate()) {
            $user = new User();
            $user->phonenumber = $this->phonenumber;
            $fio		 = explode(' ',$this->fio);
            $user->lname = $fio[0];
            $user->fname = $fio[1];
            $user->fio	 = $fio[2];
            
            $user->setPassword($this->password);
            $user->status = User::STATUS_ACTIVE;
            $user->role = $this->role;
            $user->id_a = $this->id_mkd;
            $user->id_org = $this->id_org;
            
            if ($user->save()) {
                return $user;
            }else{
            	Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'SUF_error_data'));
            }
            
        }
		return null;
     }    
}
