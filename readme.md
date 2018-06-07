# National Rail Stomp Connector
Simple connector to the National rail stomp queue. Will return the messages a decoded array for further PHP processing.

## Example

```
$connector = new \NationalRail\Connector(
    'user',
    'password',
    'your-queue-name'
);
$connection = $connector->getConnection();

var_dump($connection->getMessage());
```