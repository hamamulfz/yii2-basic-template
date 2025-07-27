<?php

/**
 * User: Taufiq Rahman (Rahman.taufiq@gmail.com)
 * Date: 11/08/19
 * Time: 09.35
 */
// use this controller if your API dont need to auth
namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

class CustomController extends \yii\rest\Controller
{
    private $_verbs = ['GET', 'POST', 'PUT', 'HEAD', 'OPTIONS'];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'OPTIONS'],
                'Access-Control-Expose-Headers' => ['X-Pagination-Total-Count', 'X-Pagination-Page-Count', 'X-Pagination-Current-Page', 'X-Pagination-Per-Page'],
                'Access-Control-Allow-Credentials' => false,
                'Access-Control-Allow-Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['*'],
            ],
        ];
        
        return $behaviors;
    }
    public function beforeAction($action)
    {
        
        date_default_timezone_set('Asia/Jakarta');
        $options = $this->_verbs;
        parent::beforeAction($action);
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
            Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Methods', implode(', ', $options));
            Yii::$app->end();
        }
        return true;
    }

    /**
     * response Bad Request
     */
    public function responseBadRequest($errors, $message = false)
    {
        Yii::$app->response->statusCode = 400;
        return [
            'name' => 'Bad Request',
            'message' => $message ? $message : ' Bad Request, please contact Admin!',
            'code' => 400,
            'type' => $errors
        ];
    }

    /**
     * response Success response
     */
    public function responseSuccess($message = false)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'message' => $message ? $message : 'Success',
        ];
    }

    public function responseFound($model)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'data' => $model
        ];
    }

        /**
     * response Success response
     */
    public function responseSubmitted($message = false)
    {
        Yii::$app->response->statusCode = 201;
        return [
            'message' => $message ? $message : 'Submitted',
        ];
    }



    /**
     * response Not Found
     */
    public function responseNotFound($errors, $message = false)
    {
        Yii::$app->response->statusCode = 404;
        return [
            'name' => 'Resource Not Found',
            'message' => $message ? $message : 'Your request not exist, please contact Admin!',
            'code' => 404,
            'status' => 404,
            'type' => $errors
        ];
    }


    public function parseError($objs)
    {
        $data = [];
        foreach ($objs as $obj) {
            // var_dump($intReportForm->firstErrors);die();
            // var_dump($obj);die();
            $data[] = $obj;
        }
        return $data[0];
    }
}
