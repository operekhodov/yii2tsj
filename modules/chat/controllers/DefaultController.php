<?php

namespace app\modules\chat\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\rbac\Rbac as AdminRbac;

/**
 * Default controller for the `chat` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => [
					'identityClass' => 'app\models\User',
					'enableAutoLogin' => false,
					'loginUrl' => ['site/first'],
				],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [AdminRbac::PERMISSION_USER_PANEL],
                    ],
                ],
            ],
        ];
    }	
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
