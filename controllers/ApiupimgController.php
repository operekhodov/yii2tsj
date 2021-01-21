<?php
namespace app\controllers;

use Yii;

use app\models\Options;
use yii\rest\ActiveController;
use yii\web\Controller;


class ApiupimgController extends ActiveController
{

	public $modelClass = 'app\models\Options';
    public $documentPath = 'uploads/';

    public function verbs()
    {
        $verbs = parent::verbs();
        $verbs[ "upload" ] = ['POST' ];
        return $verbs;
    }

    public function actionUpload($userid,$taskid)
    {
        $postdata = fopen( $_FILES[ 'fileName' ][ 'tmp_name' ], "r" );
        $extension = substr( $_FILES[ 'fileName' ][ 'name' ], strrpos( $_FILES[ 'fileName' ][ 'name' ], '.' ) );
        $filename = $this->documentPath . uniqid() . $extension;
        $fp = fopen( $filename, "w" );
        while( $data = fread( $postdata, 1024 ) )
            fwrite( $fp, $data );
        fclose( $fp );
        fclose( $postdata );
		
		$options = new Options();
		$options->name = 'img_task';
		$options->value = $filename;
		$options->id_u = $userid;
		$options->id_t = $taskid;
		if ( $options->validate() && $options->save() ) {
			return $options;
		}
    }
    public function actionTagsview($taskid) {
       	$tags = Options::find()->where(['name' => 'tags','id_t' => '0']);
    	if ($tags) {
    		return $tags;
    	}
    }
}