<?php

/**
 * Created by Hamamul Fauzi
 * Date: 11/05/2025
 * Time: 10.17
 */

namespace  app\components;

use app\helpers\Utils as HelpersUtils;
use Yii;
use yii\base\Behavior;
use yii\web\Response;
use yii\rest\Controller;
use app\models\ApiLog;

class ApiLogging extends Behavior
{

    public $app_name;
    public $UrlName;
    public $LOG_ON_ERROR = true;
    private $apilogid;
    public function init()
    {
        parent::init();
        $request = Yii::$app->request;
        $this->UrlName = $request->getUrl();
        $this->app_name = Yii::$app->id;

        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response  = $event->sender;
            $status    = $response->statusCode;
            $json      = $response->data;
            $response1 = json_encode($json);
            $this->logger_response($response1, $status);
        });
    }

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function logger()
    {
        $app_id        = Yii::$app->id;
        $controller    = Yii::$app->controller->id;
        $action        = Yii::$app->controller->action->id;
        $header        = Yii::$app->request->getHeaders();
        $params        = Yii::$app->request->get();
        $body          = Yii::$app->request->post();
        $requestedPath = Yii::$app->request->getPathInfo();
        $method        = $_SERVER['REQUEST_METHOD'];
        $ws_type       = $controller . '-' . $action;
        $willSave      = $header['platform'] == 'mobile';
        
        if ($willSave) {
            $model             = new  ApiLog();
            $model->ip_client  = HelpersUtils::getClientIp();
            $model->endpoint   = $requestedPath;
            $model->header     = json_encode(['token' => $header['Authorization'], 'platform' => $header['platform']]);
            $model->params     = json_encode($params);
            $model->body       = json_encode($body);
            $model->method     = $method;
            $model->app_name   = $app_id;
            $model->ws_type    = $ws_type;
            $model->created_at = date("Y-m-d H:i:s");
            if (isset(Yii::$app->getUser()->id)) {
                $model->created_by = Yii::$app->getUser()->id;
            } else {
                $model->created_by = 0;
            }
            $model->save(false);
            $this->apilogid = $model->id;
        }
    }

    public function logger_response($response, $statusCode)
    {
        $model = ApiLog::findOne(['id' => $this->apilogid]);
        if ($model) {
            $model->response = @$response;
            $model->status_code = @$statusCode;
            $model->update(false);
        }
    }

    public function beforeAction($event)
    {
        $this->logger();
    }
}
