<?php


namespace App\Http\Controllers\API\Parent\Message;


use App\Http\Controllers\API\BaseController;


class NotificationController extends BaseController
{
    public function sendPushNotification($to = '', $data = array())
    {
        $apiKey = 'AAAAMCymdGA:APA91bExY1q0NYhnLXJ3xX3IfA2f_bHdlmD0KYcWHKDG_8qN7jANu4WcQ5KcL9FEhkQtLyquJrD4Kd2D0Fg_TBwx426mv5etnTEcYMmE7QfHxajelUOyjcvDZOCtz1-Cs8RUPJDG_YLQ';
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

    public function push($title, $body, $to)
    {
        $to = "d87M6p89QPaAlCRfikxTiI:APA91bEFhiZJFdAyXS3e0jo8HOcvhQP61GqfgUqr7pWE0p6i1NZYzcE9ZhH_iaf_2tXRzbNIqf-BcQ8z0jnDSPsYWILreawQ3JA7_aFoJs6tgnXWeufcHt_kOBuutQMy39uP9W7hEDNC";

      //  $to = $to;
        $data = array(
            'body' => $body,
            'title' => $title,
            'playSound' => true,
            'soundName' => 'default'
        );
        $this->sendPushNotification($to, $data);
    }

}
