# National Rail Stomp Connector
Simple connector to the National rail stomp queue. Will return the messages a decoded array for further PHP processing.

## Example

```
$nationalRailConsumer = new \NationalRail\Connector(
    'your-username',
    'your-password',
    'your-queue-string'
);

while (true) {
    var_dump($nationalRailConsumer->getMessage());
}
```