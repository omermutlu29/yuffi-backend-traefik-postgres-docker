<?php


namespace App\Services\NotificationServices;


use App\Interfaces\NotificationInterfaces\INotification;

class PushNotificationService implements INotification
{
    private $apiKey;

    public function __construct($apiKey = null)
    {

        $this->apiKey = $apiKey == null ? env('FIREBASE_APIKEY') : 'AAAAMCymdGA:APA91bExY1q0NYhnLXJ3xX3IfA2f_bHdlmD0KYcWHKDG_8qN7jANu4WcQ5KcL9FEhkQtLyquJrD4Kd2D0Fg_TBwx426mv5etnTEcYMmE7QfHxajelUOyjcvDZOCtz1-Cs8RUPJDG_YLQ';
    }


    public function notify(string $title="", string $body,string $to): bool|string
    {
        try {
            $data = array(
                'body' => $body,
                'title' => $title,
                'playSound' => true,
                'soundName' => 'default'
            );
            return $this->sendDataToGoogleAPI($data,$to);
        }catch (\Exception $exception){
            throw $exception;
        }

    }

    private function sendDataToGoogleAPI(array $data, string $to): bool|string
    {
        $fields = array('to' => $to, 'notification' => $data);
        $headers = array('Authorization: key=' . $this->apiKey, 'Content-Type: application/json');
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
        return $result;
    }
}
