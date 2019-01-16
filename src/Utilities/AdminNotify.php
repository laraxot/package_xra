<?php



namespace XRA\XRA\Utilities\AdminNotify;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\Notification;

abstract class AdminNotify
{
    protected $http;

    public function __construct(HttpClient $http, $config = [])
    {
        $this->http = $http;
        $this->setConfig($config);
    }

    public function setConfig($config)
    {
    }

    abstract public function send(Notification $notification);
}
