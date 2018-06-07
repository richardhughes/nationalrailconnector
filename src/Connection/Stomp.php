<?php

namespace NationalRail\Connection;

use Converter\XML;
use NationalRail\Exception\NotConnectedException;
use Stomp\Client;
use Stomp\Exception\StompException;
use Stomp\StatefulStomp;

class Stomp
{
    /**
     * @var Client
     */
    private $stompClient;

    /**
     * @var StatefulStomp
     */
    private $statefulStomp;

    public function __construct(
        Client $stompClient,
        StatefulStomp $statefulStomp
    )
    {
        $this->stompClient = $stompClient;
        $this->statefulStomp = $statefulStomp;
    }

    public function getMessage(): ?array
    {
        $message = $this->statefulStomp->read();

        if ($message) {
            $unZip = gzdecode($message->body);
            $loadedXml = simplexml_load_string($unZip);
            if (!$loadedXml) {
                return null;
            }

            return (new XML($loadedXml, []))->toArray();
        }

        return null;
    }

    /**
     * @throws NotConnectedException
     */
    public function connect(): void
    {
        try {
            $this->stompClient->connect();
        } catch (StompException $exception) {
            throw new NotConnectedException();
        }
    }

    public function login(string $username, string $password): void
    {
        $this->stompClient->setLogin($username, $password);
    }

    public function subscribeTo(string $queue): void
    {
        $this->statefulStomp->subscribe('/queue/' . $queue);
    }

}