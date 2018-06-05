<?php

namespace NationalRail\Exception;

use Exception;

class NotConnectedException extends Exception
{
    protected $message = "Unable to connect with supplied credentials.";
}