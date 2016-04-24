<?php
namespace LinkORB\Realtime\Socket;

use LinkORB\Realtime\Socket\Connection;

class Storage
{
    private $connections;

    public function __construct()
    {
        $this->connections = new \SplObjectStorage;
    }

    public function attach(Connection $conn)
    {
        $this->connections->attach($conn);
    }

    public function detach(Connection $conn)
    {
        $this->connections->detach($conn);
    }

    public function getConnections()
    {
        return $this->connections;
    }
}
