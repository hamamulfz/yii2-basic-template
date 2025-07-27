<?php

namespace app\controllers;

use app\controllers\CustomController;
use app\helpers\PusherNotification;
use app\helpers\ServerCheck;
use app\models\Language;
use Pusher\Pusher;

class SiteController extends CustomController
{
    public function actionIndex()
    {
        return $this->responseSuccess('Micro Web API');
    }

    public function actionError()
    {
        return $this->responseNotFound('Unknown Request');
    }

    public function actionFileExist($path)
    {
        return file_exists($path);
    }


    public function actionHealth()
    {
        $serverHelper = new ServerCheck();
        $data = $serverHelper->health();
        
        return $this->responseSuccessItems($data, 'status Active');
    }

    public function actionLoadLanguage($lang)
    {
        if ($lang == 'id') {
            $search = Language::find()
            ->select(['code', 'id as lang'])
            ->all();
        } else if ($lang == 'cn') {
            $search = Language::find()
            ->select(['code', 'cn as lang'])
            ->all();
        } else if ($lang == 'en') {
            $search = Language::find()
            ->select(['code', 'en as lang'])
            ->all();
        }
        $result = [];
        foreach($search as $item) {
            $result[$item->code] = $item->lang;
        }
        return $result;
    }

    function actionPusher() {
        $trigger  = PusherNotification::send('test_channel', 'test_event', [
            'message' => 'hello world'
        ]);
        return $this->responseSuccess('Pusher Notification');
    }
}
