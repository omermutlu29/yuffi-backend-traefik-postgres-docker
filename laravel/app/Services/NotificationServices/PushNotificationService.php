<?php


namespace App\Services\NotificationServices;


use App\Interfaces\NotificationInterfaces\INotification;

class PushNotificationService implements INotification
{
    public $apiKey;

    public function __construct($apiKey = null)
    {

        $this->apiKey = env('FIREBASE_APIKEY') ;
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
