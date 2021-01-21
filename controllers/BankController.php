<?php

namespace app\controllers;

use Yii;
use app\models\Bank;
use app\models\BankSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * BankController implements the CRUD actions for Bank model.
 */
class BankController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionAuthbank()
    {
        $model = $this->findModel(1);
		if ($_GET['code']){
			$model->code = $_GET['code'];
			$model->save();
			$client = new Client();
			$response = $client->createRequest()
			    ->setMethod('POST')
			    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/oauth2/token")
			    ->setFormat(Client::FORMAT_JSON)
			    ->setData([ 'client_id' => $model->client_id, 
			    			'client_secret' => $model->secret_id,
			    			'grant_type' => 'authorization_code',
			    			'code' => $model->code])
			    ->send();
			if ($response->isOk) {
			    $newUserId = $response->data['id'];
			    $responseData = $response->getData();
			    
			    $json = json_decode($response->content, true);
			    $model->refresh_token = $json['refresh_token'];
			    $model->access_token = $json['access_token'];
			    $model->save();
			}			
		}


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	    	//GET api/v1/authorize/?response_type={response_type}&client_id={client_id}
	    	$model = Bank::findOne(1);
	    	//Для работы с песочницей замените во всех запросах /api/v1 на /sandbox/v1.
			$client = new Client();
			$response = $client->createRequest()
			    ->setMethod('GET')
			    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/authorize/?response_type=code&client_id=$model->client_id")
			    ->send();
			if ($response->isOk) {
			}
            return $this->render('update', [
            'model' => $model,
        ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionList()
    {
    	$model = Bank::findOne(1);
    	
		if($_POST['request_id_result']){
				    	/* Вывод запроса */
				    	$request_id = $_POST['request_id_result'];
						$client = new Client();
						$response = $client->createRequest()
						    ->setMethod('GET')
						    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/statement/result/$request_id")
						    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
						    ->send();
						if ($response->isOk) {
						    $newUserId = $response->data['id'];
						    $responseData = $response->getData();
						    $this->view->params['result'] = $response->content;
						    $this->view->params['statement'] = $request_id;
						}
				    	/* Вывод запроса */
		}elseif($_POST){

    		$arr_amount = explode('|', $_POST['key_amount']);
    		$date_end = $_POST['date_end'];
    		$date_start = $_POST['date_start'];
    		/*Создание запроса*/
				$client = new Client();
				$response = $client->createRequest()
				    ->setMethod('POST')
				    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/statement")
				    ->setFormat(Client::FORMAT_JSON)
				    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
				    ->setData([ 
		        		"account_code" => "$arr_amount[0]",
		        		"bank_code" => "$arr_amount[1]",
		        		"date_end" => "$date_end",
		        		"date_start" => "$date_start"])
				    ->send();
				if ($response->isOk) {

				    $newUserId = $response->data['id'];
				    $responseData = $response->getData();
				    
				    $json = json_decode($response->content, true);
				    
				    $request_id = $json['request_id'];
					$this->view->params['statement'] = $request_id;
					
					/* Проверка статуса запроса */
					$client = new Client();
					$response = $client->createRequest()
					    ->setMethod('GET')
					    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/statement/status/$request_id")
					    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
					    ->send();
					if ($response->isOk) {
					    $newUserId = $response->data['id'];
					    $responseData = $response->getData();
					    $this->view->params['list'] = $response->content;
					    $json = json_decode($response->content, true);
					    if($json['status'] == 'queued'){
					    	Yii::$app->session->addFlash('success', 'Запрос находится в обработке.');
					    }elseif($json['status'] == 'ready'){
					    	Yii::$app->session->addFlash('success', 'Запрос выполнен.');
					    	/* Вывод запроса */
							$client = new Client();
							$response = $client->createRequest()
							    ->setMethod('GET')
							    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/statement/result/$request_id")
							    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
							    ->send();
							if ($response->isOk) {
							    $newUserId = $response->data['id'];
							    $responseData = $response->getData();
							    $this->view->params['result'] = $response->content;
							}
					    	/* Вывод запроса */
					    	
					    }
					    
					    
					}
					/* Проверка статуса запроса */


				}    		
    		/*Создание запроса*/
    	}
		
		/* Обмен refresh_token на новые access_token и refresh_token */
			$client = new Client();
			$response = $client->createRequest()
			    ->setMethod('POST')
			    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/oauth2/token")
			    ->setFormat(Client::FORMAT_JSON)
			    ->setData([ 
	        		"client_id" => "$model->client_id",
	        		"client_secret" => "$model->secret_id",
	        		"grant_type" => "refresh_token",
	        		"refresh_token" => "$model->refresh_token"])
			    ->send();
			if ($response->isOk) {

			    $newUserId = $response->data['id'];
			    $responseData = $response->getData();
			    
			    $json = json_decode($response->content, true);
			    $model->refresh_token = $json['refresh_token'];
			    $model->access_token = $json['access_token'];
			    $model->save();
				
			}		
		/* Обмен refresh_token на новые access_token и refresh_token */
		
		$model = Bank::findOne(1);
				
		/* Получение списка организаций и счетов */
		$client = new Client();
		$response = $client->createRequest()
		    ->setMethod('GET')
		    ->setUrl("https://enter.tochka.com/$model->sandbox/v1/account/list")
		    ->addHeaders(['Authorization' => "Bearer $model->access_token"])
		    ->send();
		if ($response->isOk) {
		    $newUserId = $response->data['id'];
		    $responseData = $response->getData();
		    $this->view->params['list'] = $response->content;
		}
		/* Получение списка организаций и счетов */
		
    	return $this->render('index');
    }


    protected function findModel($id)
    {
        if (($model = Bank::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
