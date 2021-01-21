<?php
namespace app\assets;

use yii\web\AssetBundle;

class ICheckAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/icheck/line/blue.css',
    ];
    public $js = [
    	'js/icheck/icheck.min.js',
    ];
}
