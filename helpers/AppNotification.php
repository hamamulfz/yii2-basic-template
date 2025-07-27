<?php


namespace app\helpers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Pusher;
use Kreait\Firebase\Exception\FirebaseException;
use Yii;

class AppNotification
{
    private $serverKey;
    private $factory;
    private $messaging;

    public function __construct($serverKey)
    {
        $this->serverKey = $serverKey;
        $this->factory = (new Factory)->withServiceAccount(__DIR__ . '/service-account.json');
        $this->messaging = $this->factory->createMessaging();
    }

    public function sendFcm($target, $title, $body, $isTopic = false, $data = null)
    {
        try {
            $arr = [
                'priority' => 'high',
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    // 'sound' =>  'alert1'
                ],
                'data' => $data ?? [
                    'title' => $title,
                    'body' => $body,
                ],
            ];

            if ($isTopic) {
                $arr['topic'] = $target;
            } else {
                $arr['token'] = $target;
            }
            $message = CloudMessage::fromArray($arr);
            $result =    $this->messaging->send($message);

            return [
                'success' => true,
                'message' => 'Notification sent successfully',
                'response' => $result
            ];
        } catch (FirebaseException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public static function sendPusher($channel, $purpose, $data)
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
