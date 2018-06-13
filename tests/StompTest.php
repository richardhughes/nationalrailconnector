<?php

namespace Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NationalRail\Connection\Stomp;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->stompClient = Mockery::mock(Client::class);
        $this->statefulStomp = Mockery::mock(StatefulStomp::class);

        $this->stompConnection = new Stomp($this->stompClient, $this->statefulStomp);
    }

    /**
     * @throws NotConnectedException
     */
    public function testConnectionDoesNotThrowExceptionIfConnected(): void
    {
        $this->stompClient
            ->shouldReceive('connect')
            ->once()
            ->andReturn(true);

        $this->stompConnection->connect();
    }

    /**
     * @throws NotConnectedException
     */
    public function testConnectionThrowsNotConnectedExceptionIfFailed(): void
    {
        $this->stompClient
            ->shouldReceive('connect')
            ->once()
            ->andThrow(StompException::class);

        $this->expectException(NotConnectedException::class);

        $this->stompConnection->connect();
    }

    public function testLoginPassesTheCorrectValuesToTheStompClient(): void
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

    public function testCanSubscribeToQueue(): void
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