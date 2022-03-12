<?php


namespace App\Services\NotificationServices;

use App\Interfaces\LogInterfaces\ILogger;
use App\Interfaces\NotificationInterfaces\INotification;

class NetGSMSmsNotification implements INotification
{
    private $userName;
    private $password;
    private $logger;

    public function __construct($userName = "", $password = "")
    {
        if ($userName != "" && $password != "") {
            $this->userName = $userName;
            $this->password = $password;
        }
    }

    public function notify($data=null,string $title = "", string $body, string $to): bool
    {
        $url = "https://api.netgsm.com.tr/sms/send/get/?usercode=" . $this->userName . "&password=" . $this->password . "&gsmno=" . $to . "&message=" . $body . "&msgheader=" . $title;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (substr($http_response, 0, 2) != "00") {
            return false;
        } else {
            return true;
        }
    }
}
