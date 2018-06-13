<?php

namespace NationalRail;

use NationalRail\Connection\Stomp as StompConnection;
use NationalRail\Constants\Stomp;
use Stomp\Client;
use Stomp\StatefulStomp;

class Connector
{
    private $connection;

    public function __construct(string $username, string $password, string $queue)
    {
        $stompClient = new Client(
            Stomp::NATIONAL_RAIL_URI
            . ':'
            . Stomp::LISTENING_PORT
        );

        $stomp = new StatefulStomp($stompClient);

        $this->connection = new StompConnection($stompClient, $stomp);
        $this->connection->login($username, $password);
        $this->connection->connect();
        $this->connection->subscribeTo($queue);
    }

    public function getConnection(): StompConnection
    {
        return $this->connection;
    }
}