<?php


namespace App\Http\Controllers\API\BabySitter\Message;


use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;

class NotificationController extends BaseController
{
    public function sendPushNotification($to = '', $data = array())
    {
        $apiKey = 'AIzaSyCFpgoq8dhCxm-49BVqX1tnP3MGxkbUjeQ';
        $fields = array('to' => $to, 'notification' => $data);
        $headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        curl_close($ch);

        return true;
    }

    public function push($title,$body,$to)
    {

        $to = $to;
        $data = array(
            'body' => $body,
            'title' => $title,
            'playSound'=> true,
            'soundName'=> 'default'
        );
        $this->sendPushNotification($to, $data);
    }

}
