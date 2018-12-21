<?php
namespace XRA\XRA\Utilities\AdminNotify;

use Illuminate\Notifications\Notification;
use GuzzleHttp\Client as HttpClient;

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
