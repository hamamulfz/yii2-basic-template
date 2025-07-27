<?php

namespace app\controllers;


use app\models\LoginForm;
use Yii;

class UserController extends CustomController
{

    public function actionLogin()
    {
        date_default_timezone_set("Asia/Bangkok");
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $roleList = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            $roleName = [];
            foreach ($roleList as $role) {
                $roleName[] = $role->name;
            }
            $model->user->is_login = 1;
            $model->user->save();
        
            $data = [
                'user_id' => $model->user->id,
                'username' => $model->user->username,
                'auth_key' =>$model->user->auth_key,
                'auth' => $roleName
        
                
            ];

            return $this->responseSuccessItems($data, 'Login success');
        } else {
            return $this->responseNotFound('invalid user name or password', $this->parsingError($model->errors));
        }
    }
}
