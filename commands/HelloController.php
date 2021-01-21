<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\daemons\EchoServer;
use app\daemons\DelServer;
use consik\yii2websocket\WebSocketServer;

class HelloController extends Controller
{
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }
    public function actionStart($port = null)
    {
        $server = new EchoServer();
        if ($port) {
            $server->port = $port;
        }
        $server->start();
    }

}
