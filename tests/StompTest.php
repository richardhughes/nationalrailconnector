<?php

namespace Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NationalRail\Connection\Stomp;
use NationalRail\Connector;
use NationalRail\Exception\NotConnectedException;
use Stomp\Client;
use Stomp\Exception\StompException;
use Stomp\StatefulStomp;

class StompTest extends MockeryTestCase
{
    /**
     * @var Mockery|Client
     */
    private $stompClient;

    /**
     * @var Mockery|StatefulStomp
     */
    private $statefulStomp;

    /**
     * @var Stomp
     */
    private $stompConnection;

    public function setUp()
    {
        parent::setUp();

        $this->stompClient = Mockery::mock(Client::class);
        $this->statefulStomp = Mockery::mock(StatefulStomp::class);

        $this->stompConnection = new Stomp($this->stompClient, $this->statefulStomp);
    }

    public function testConnectionDoesNotThrowExceptionIfConnected()
    {
        $this->stompClient
            ->shouldReceive('connect')
            ->once()
            ->andReturn(true);

        $this->stompConnection->connect();
    }

    public function testConnectionThrowsNotConnectedExceptionIfFailed()
    {
        $this->stompClient
            ->shouldReceive('connect')
            ->once()
            ->andThrow(StompException::class);

        $this->expectException(NotConnectedException::class);

        $this->stompConnection->connect();
    }

    public function testLoginPassesTheCorrectValuesToTheStompClient()
    {
        $username = "test";
        $password = "password";

        $this->stompClient
            ->shouldReceive('setLogin')
            ->once()
            ->with($username, $password)
            ->andReturn(true);

        $this->stompConnection->login($username, $password);
    }

    public function testCanSubscribeToQueue()
    {
        $queue = 'test-queue';

        $this->statefulStomp
            ->shouldReceive('subscribe')
            ->once()
            ->with('/queue/' . $queue)
            ->andReturn(true);

        $this->stompConnection->subscribeTo($queue);
    }
}