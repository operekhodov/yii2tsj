<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'app',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'modules' => [
	    'gridview' =>  [
	        'class' => '\kartik\grid\Module',
	    ],
        'chat' => [
            'class' => 'app\modules\chat\ChatModule',
        ],	    
	],    
    'components' => [
		'view' => [
		         'theme' => [
		             'pathMap' => [
		                '@app/views' => '@app/views'
		             ],
		         ],
		    ],
	    'authManager' => [
	        'class' => 'app\components\AuthManager',
	        //'defaultRoles' => ['root','admin','government', 'dispatcher','user'], 
	    ],      
        'request' => [
            'cookieValidationKey' => 'Zaq1#edcBgt5',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'application/octet-stream' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ]            
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
         'class' => 'yii\swiftmailer\Mailer',
         'useFileTransport' => false,
         'transport' => [
         'class' => 'Swift_SmtpTransport',
         'host' => 'smtp.timeweb.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
         'username' => 'tsj@wtot.ru',
         'password' => 'Zaq1@wsxCde3',
         'port' => '465', // Port 25 is a very common port too
         'encryption' => 'ssl', // It is often used, check your provider or mail server specs
         ],
        ],        
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apidevtoken'],
					'pluralize' => false,
                    'tokens' => [
                        '{deviceid}' => '<deviceid:\d+>',
                    ],
					'extraPatterns' => [
			        	'POST {deviceid}' => 'updevtok', // 'xxxxx' refers to 'actionXxxxx'
			        	'GET {deviceid}' => 'view',
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apiupimg'],
					'pluralize' => false,
                    'tokens' => [
                        '{taskid}' => '<taskid:\d+>',
                        '{userid}' => '<userid:\d+>',
                    ],					
					'extraPatterns' => [
			        	'POST {userid}/{taskid}' => 'upload', // 'xxxxx' refers to 'actionXxxxx'
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apitasks'],
					'pluralize' => false,
					'tokens' => [
						'{id_u}' => '<id_u:\d+>',
					],
					'extraPatterns' => [
			        	'GET {id_u}' => 'taskview',
			        	'POST {id_u}' => 'taskcreate',
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apitopic'],
					'pluralize' => false,
					'tokens' => [
						'{id_u}' => '<id_u:\d+>',
					],
					'extraPatterns' => [
			        	'GET {id_u}' => 'topicview',
			        	'POST {id_q}' => 'topicans',
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apiusers'],
					'pluralize' => false,
					'tokens' => [
						'{id_u}' => '<id_u:\d+>',
					],
					'extraPatterns' => [
			        	'GET {id_u}' => 'userview',
			        	'POST {id_u}' => 'usercreate',
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apiindicat'],
					'pluralize' => false,
					'tokens' => [
						'{id_u}' => '<id_u:\d+>',
					],
					'extraPatterns' => [
			        	'GET {id_u}' => 'indicatview',
			        	'POST {id_u}' => 'indicatcreate',
			        ],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apimkd'],
					'extraPatterns' => [ 'GET' => 'mkdview' ],
					'pluralize' => false,
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apimobreg'],
					'pluralize' => false,
                    'tokens' => [
                        '{phonenumber}' => '<phonenumber:\d{11}>',
                    ],
					'extraPatterns' => [
						'POST' => 'mobreg',
						'GET {phonenumber}' => 'checkphone'
					],					
                ],
                [
                  	'class' => 'yii\rest\UrlRule',
                  	'controller' => ['apimoblogin'],
					'pluralize' => false,
                    'tokens' => [
                        '{phonenumber}' => '<phonenumber:\d{11,13}>',
                    ],					
					'extraPatterns' => [
			        	'GET {phonenumber}' => 'moblogin',
			        	'PUT' => 'mobpin',
			        	'POST' => 'pass2login',
			        ],					
                ],
                [
			        'class' => 'yii\rest\UrlRule',
			        'controller' => ['apitest'],
                    'tokens' => [
                        '{phonenumber}' => '<phonenumber:\d{11,13}>', 
                    ],			        
			        'extraPatterns' => [ 
			        	'GET {phonenumber}' => 'tview',
			        	'PUT' => 'tput',
			        	],
			        'pluralize' => false,
                ],
            ],
        ],
        'fcm' => [
             'class' => 'aksafan\fcm\source\components\Fcm',
             'apiVersion' => \aksafan\fcm\source\requests\StaticRequestFactory::LEGACY_API,
             'apiParams' => [
                 'serverKey' => 'AAAAtzQwgmo:APA91bEyTXRPX5pEio1Lqd3k8P6MwprhfY16oHkLWM57t7fet1xisRNf5-2j6FhcLCJOYyKccFEvZUgRqAoonq_KcNAfEs7E_ALn2TYEIp85sl80fL68ZmzvmdJbAM5L6-Kcv_vI8YCk',
                 'senderId' => '786854609514',
             ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1','82.200.45.10'],
    ];
}

return $config;
