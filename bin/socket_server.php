<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\App as WS;

use LinkORB\Realtime\Console;
use LinkORB\Realtime\Socket\Realtime;

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = new Console();

echo "Initializing Event Loop\n";
$loop   = React\EventLoop\Factory::create();
$pusher = new Realtime($app);

echo "Attachig ZMQ\n";
$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://'.$app['zmq']['server'].':'.$app['zmq']['port']);
$pull->on('message', array($pusher, 'publish'));

echo "Attachig Socket\n";
$socket = new WS($app['socket']['server'], $app['socket']['port'], '0.0.0.0', $loop);
$socket->route('realtime', $pusher, array('*'));

echo "Attaching Online Users Event Loop\n";
$loop->addPeriodicTimer(5, function () use (&$i, $app) {
    $app->getPublisher()->online();
});

echo "Socket Server Listening at : ws://". $app['socket']['server'] . ":" . $app['socket']['port'] . "/realtime \n";
$loop->run();
