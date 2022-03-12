<?php


namespace App\Services\NotificationServices;


use App\Interfaces\NotificationInterfaces\INotification;

class PushNotificationService implements INotification
{
    public $apiKey;

    public function __construct($apiKey = null)
    {

        $this->apiKey = env('FIREBASE_APIKEY');
    }


    public function notify($data, string $title = "", string $body, string $to): bool|string
    {
        try {
            $data = [
                "to" => $to,
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                    "icon" => "ic_launcher"
                ],
                "data" => $data,

            ];
            return $this->sendDataToGoogleAPI($data);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    private function sendDataToGoogleAPI($data): bool|string
    {
        $data = json_encode($data);
//FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
//api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = $this->apiKey;
//header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );
//CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}
