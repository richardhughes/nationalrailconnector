<?php

namespace NationalRail;

use Converter\XML;
use NationalRail\Exception\NotConnectedException;
use Stomp\Client;
use Stomp\Exception\StompException;
use Stomp\StatefulStomp;

class Connector
{
    private const DEFAULT_NATIONAL_RAIL_URI = 'tcp://datafeeds.nationalrail.co.uk';

    private const STOMP_LISTENING_PORT = 61613;
    /**
     * @var Client
     */
    private $stompClient;

    private $stomp;

    public function __construct(
        string $username,
        string $password,
        string $queue,
        string $uri = self::DEFAULT_NATIONAL_RAIL_URI,
        int $port = self::STOMP_LISTENING_PORT
    ) {
        $this->stompClient = new Client($uri . ':' . $port);
        $this->stompClient->setLogin($username, $password);

        $this->connect();

        $this->stomp = new StatefulStomp($this->stompClient);
        $this->stomp->subscribe('/queue/' . $queue);
    }

    public function getMessage(): ?array
    {
        $message = $this->stomp->read();

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
    private function connect(): void
    {
        try {
            $this->stompClient->connect();
        } catch (StompException $exception) {
            throw new NotConnectedException();
        }
    }
}