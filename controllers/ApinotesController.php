<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

class ApinotesController extends ActiveController
{


    public $modelClass = 'app\models\Tasks';
}