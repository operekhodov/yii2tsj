<?php
namespace app\rbac;
 
use Yii;
use yii\rbac\Rule;
 
class UserGroupRule extends Rule
{
    public $name = 'userGroup';
 
    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            $group = \Yii::$app->user->identity->role;
            if ($item->name === 'root') {
            	return $group == 'root';
            } elseif ($item->name === 'admin') {
                return $group == 'root' || $group == 'admin';
            } elseif ($item->name === 'moder') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder';
            } elseif ($item->name === 'boss') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'boss';
            } elseif ($item->name === 'dispatcher') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'boss' || $group == 'dispatcher';
            } elseif ($item->name === 'spec') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'boss' || $group == 'dispatcher' || $group == 'spec';
            } elseif ($item->name === 'agent') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'boss' || $group == 'dispatcher' || $group == 'spec' || $group == 'agent';
            } elseif ($item->name === 'government') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'government';
            } elseif ($item->name === 'user') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'government' || $group == 'user';
            } elseif ($item->name === 'halfuser') {
            	return $group == 'root' || $group == 'admin' || $group == 'moder' || $group == 'government' || $group == 'user' || $group == 'halfuser';
            }
        }
        return true;
    }
}