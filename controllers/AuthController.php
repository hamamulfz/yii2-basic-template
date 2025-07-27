<?php

/**
 * User: Taufiq Rahman (Rahman.taufiq@gmail.com)
 * Date: 11/08/19
 * Time: 09.35
 */
// use this controller if your API dont need to auth
namespace app\controllers;

use app\components\ApiLogging;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use app\components\mdm\AccessControl;
use yii\rest\Controller;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Methods' => ['POST', 'PUT', 'GET', 'OPTIONS'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Expose-Headers' => ['X-Pagination-Total-Count', 'X-Pagination-Page-Count', 'X-Pagination-Current-Page', 'X-Pagination-Per-Page'],
            ],
        ];

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        // TODO saat ini RBAC belum di jalankan
        // $behaviors['access'] = [
        //     'class' => AccessControl::class,
        // ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        $behaviors['ApiLogging'] =[
            'class' => ApiLogging::class,
            'LOG_ON_ERROR' => true 
        ];

        return $behaviors;
    }

    // public function actions()
    // {
    //     return [
    //         'index' => [
    //             'class' => 'app\components\IndexAction',
    //             'modelClass' => $this->modelClass,
    //             'checkAccess' => [$this, 'checkAccess'],
    //         ],
    //         'view' => [
    //             'class' => 'yii\rest\ViewAction',
    //             'modelClass' => $this->modelClass,
    //             'checkAccess' => [$this, 'checkAccess'],
    //         ],
    //         'create' => [
    //             'class' => 'yii\rest\CreateAction',
    //             'modelClass' => $this->modelClass,
    //             'checkAccess' => [$this, 'checkAccess'],
    //             'scenario' => $this->createScenario,
    //         ],
    //         'update' => [
    //             'class' => 'yii\rest\UpdateAction',
    //             'modelClass' => $this->modelClass,
    //             'checkAccess' => [$this, 'checkAccess'],
    //             'scenario' => $this->updateScenario,
    //         ],
    //         'delete' => [
    //             'class' => 'app\components\DeleteAction',
    //             'modelClass' => $this->modelClass,
    //             'checkAccess' => [$this, 'checkAccess'],
    //         ],
    //         'options' => [
    //             'class' => 'yii\rest\OptionsAction',
    //         ],
    //     ];
    // }

    public function beforeAction($action)
    {
        date_default_timezone_set('Asia/Jakarta');
        parent::beforeAction($action);
        return true;
    }

    /**
     * response Bad Request
     */
    public function responseErrors($code, $errors, $message = false)
    {
        Yii::$app->response->statusCode = $code;
        return [
            'name' => 'Some Errors',
            'message' => $message ? $message : ' Some Errors!',
            'code' => $code,
            'type' => $errors
        ];
    }

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

    public function responseEdited($message = false)
    {
        Yii::$app->response->statusCode = 204;
        return [
            'message' => $message ? $message : 'Edited',
        ];
    }

    public function responseDeleted($message = false)
    {
        Yii::$app->response->statusCode = 204;
        return [
            'message' => $message ? $message : 'Deleted',
        ];
    }

    /**
     * response Success response
     */
    public function responseSuccessData($data, $message = false)
    {
        Yii::$app->response->statusCode = 200;
        return [
            'status' => true,
            'data' => $data,
            'message' => $message ? $message : 'Success',
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
            'name' => 'Data/resource Not Found',
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
