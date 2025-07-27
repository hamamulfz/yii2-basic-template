<?php

namespace app\helpers;

use Yii;
use Pusher;

class PusherNotification
{

    //agatra.app@gmail.com | bandungmrt2020
    // staging for dev
    // prod for live
    public static function send($channel, $purpose, $data)
    {
        $pusherCredential = Yii::$app->params['pusher'];
        $pusherID = $pusherCredential['app_id'];
        $pusherKey = $pusherCredential['key'];
        $pusherSecret = $pusherCredential['secret'];
        $pusherCluster = $pusherCredential['cluster'];
        $pusher = new Pusher\Pusher($pusherKey, $pusherSecret, $pusherID, array(
            'cluster' => $pusherCluster,
            'useTLS' => false
        ));
        $pusher->trigger(
            $channel,
            $purpose, // event name
            $data
        );
        return true;
    }
}
