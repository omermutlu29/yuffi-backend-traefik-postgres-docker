<?php


namespace App\Interfaces\NotificationInterfaces;


interface INotification
{
    public function notify($data,string $title="", string $body,string $to);
}
