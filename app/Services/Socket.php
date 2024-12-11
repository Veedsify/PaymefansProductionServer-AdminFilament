<?php

namespace App\Services;

use ElephantIO\Client;

class Socket
{

    private $client;

    public function __construct()
    {
        $options = ['client' => Client::CLIENT_4X];
        $url = 'http://localhost:3002/socket.io';
        $this->client = Client::create($url, $options);
        $this->client->connect();
    }

    public function emit($event, $data)
    {
        $this->client->emit($event, $data);
    }

    public function listen($event)
    {
        $packet = $this->client->wait($event);
        return $packet->data;
    }

}
