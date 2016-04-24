<?php
namespace LinkORB\Realtime\Socket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface as SocketInterface;
use LinkORB\Realtime\Console;
use LinkORB\Realtime\Socket\Connection;

class Realtime implements MessageComponentInterface
{
    private $app;

    public function publish($data)
    {
        $data = json_decode($data);
    }

    public function __construct(Console $app)
    {
        $this->app = $app;
    }

    public function onError(SocketInterface $soc, \Exception $e)
    {
        $soc->close();
    }

    public function onOpen(SocketInterface $soc)
    {
    }

    public function onClose(SocketInterface $soc)
    {
        foreach ($this->app->getSocketStore()->getConnections() as $conn) {
            if ($soc == $conn->getSocket()) {
                $this->app->getSocketStore()->detach($conn);
            }
        }
    }

    public function onMessage(SocketInterface $soc, $data)
    {
        $data = json_decode($data, true);

        switch ($data['operation']) {
            case 'subscribe':
                $conn = new Connection($this->app, $soc);
                $conn->setUser($data['data']['user']);
                $conn->setChannel($data['data']['channel']);
                $conn->subscribe();
                break;
            default:
                break;
        }
    }
}
