yii2-amqp
=========
Yii2 extension enables you to use RabbitMQ queuing with native Yii2 syntax.

## Installation

Via composer

```
$ php composer.phar require iviu96afa/yii2-amqp "dev-master"
```

Or add

```
"iviu96afa/yii2-amqp": "dev-master"
```

to the ```require``` section of your `composer.json` file.

Also, add the following

```
'amqp' => [
	'class' => 'iviu96afa\amqp\components\Amqp',
	'host' => '127.0.0.1',
	'port' => 5672,
	'user' => 'username',
	'password' => 'password',
	'vhost' => '/',
],
```

to the ```components``` section of your `config.php` file.

## How to use

1- Sending:

```
$exchange = 'example';
$queue_name = 'queue_name';
$message = serialize($data);

Yii::$app->amqp->publish($exchange, $queue_name, $message);
```

2- Receiving:

```
use iviu96afa\amqp\PhpAmqpLib\Connection\AMQPConnection;

$exchange = 'example';
$queue_name = 'queue_name';

$connection = new AMQPConnection('localhost', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare($queue_name, false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
```
