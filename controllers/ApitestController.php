<?php
namespace app\controllers;

use Yii;



class ApitestController extends \yii\rest\Controller
{
	public function actionTview($phonenumber)
	{
		return "hello ".$phonenumber;
	}
	
	public function actionTput()
	{
		return "hi";
	}

}	