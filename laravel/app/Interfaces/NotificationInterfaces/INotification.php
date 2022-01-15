<?php


namespace App\Interfaces\NotificationInterfaces;


interface INotification
{
    public function notify(string $title="", string $body,string $to);
}
