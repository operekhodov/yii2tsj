<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $phonenumber;
    public $password;

    private $_user = false;

    public function rules()
    {
        return [
            // username and password are both required
            [['phonenumber', 'password'], 'required'],
            [['phonenumber'], 'filter', 'filter' => function($value){
                $value = str_replace(['(', ')', '-','+',' '], '', $value);
                return $value;
            }],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phonenumber' => Yii::t('app', 'LF_phonenumber'),
            'password' => Yii::t('app', 'LF_PASSWORD'),
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                Yii::$app->session->addFlash('warning', Yii::t('app', 'LF_error_pass'));
                $this->addError($attribute, '');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
			$this->_user = User::findByPhonenumber($this->phonenumber);
        }

        return $this->_user;
    }
}
