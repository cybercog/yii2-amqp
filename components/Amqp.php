<?php

/**
 * @link https://github.com/iviu96afa/yii2-amqp
 * @copyright Copyright (c) 2014 webtoucher
 * @license https://github.com/iviu96afa/yii2-amqp/blob/master/LICENSE.md
 */

namespace iviu96afa\amqp\components;

use yii\base\Component;
use yii\base\Exception;
use iviu96afa\amqp\PhpAmqpLib\Connection\AMQPConnection;
use iviu96afa\amqp\PhpAmqpLib\Message\AMQPMessage;

/**
 * AMQP wrapper.
 */
class Amqp extends Component {

    public $port = 5672;
    public $host = '127.0.0.1';
    public $user;
    public $password;
    public $vhost = '/';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if (empty($this->user)) {
            throw new Exception(Yii::t('app', 'AMQP - "user" attiribute is not set!'));
        }
    }

    /**
     * Sends message to the exchange.
     *
     * @param string $exchange
     * @param string $routing_key
     * @param string|array $message
     * @return void
     */
    public function publish($exchange, $routing_key, $message) {
        $connection = new AMQPConnection($this->host, $this->port, $this->user, $this->password);
        $channel = $connection->channel();
        $message = new AMQPMessage($message);

        $channel->queue_declare($routing_key, false, false, false, false);
        $channel->basic_publish($message, $exchange, $routing_key);

        $channel->close();
        $connection->close();
    }

}
