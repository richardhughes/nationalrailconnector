<?php

include '../../vendor/autoload.php';

$connector = new \NationalRail\Connector(
    'user',
    'password',
    'queue'
);
$connection = $connector->getConnection();

while (true) {
    var_dump($connection->getMessage());
}