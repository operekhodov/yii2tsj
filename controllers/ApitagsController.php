<?php
namespace app\controllers;

use Yii;

use app\models\Options;
use yii\rest\ActiveController;
use yii\web\Controller;


class ApitagsController extends ActiveController
{

	public $modelClass = 'app\models\Options';

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "tagsview" ] = ['GET' ];
        return $verbs;
    }

    public function actionTagsview() {
       	$tags  = Options::find()->where('name = :tags', [':tags'  => 'tags'])->andWhere('id_t = :idt', [':idt'  => '0'])->all();
    	if ($tags) {
    		return $tags;
    	}
    }
}