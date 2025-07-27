<?php
namespace app\controllers;

use app\models\AuthItemRegister;
use app\models\UserModel;
use Yii;

class CheckAuthController extends AuthCustomController
{
    public $modelClass = UserModel::class;

    public function actionAllow($path) {
        return Yii::$app->user->can($path);
    }

    public function actionMenu() {
        $model = AuthItemRegister::find()
        ->select('name')
        ->where(['is_menu' =>1])->all();
        $menu = [];
        foreach($model as $item) {
            if (Yii::$app->user->can($item->name)) {
                $menu[] = $item->name;
            }
        }
        return $menu;
    }
}
