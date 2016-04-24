<?php
namespace LinkORB\Realtime\Socket;

use Ratchet\ConnectionInterface as SocketInterface;

class Publish
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function channel($id, $data = [])
    {
        $connections = $this->app->getSocketStore()->getConnections();
        foreach ($connections as $value) {
            if ($value->getChannel() == $id) {
                $this->send($value, 'broadcast', $data);
            }
        }
    }

    private function channelUsers($id)
    {
        $connections = $this->app->getSocketStore()->getConnections();
        $users = [];
        foreach ($connections as $value) {
            if ($value->getChannel() == $id) {
                array_push($users, $value->getUser());
            }
        }

        return $users;
    }

    private function channelStats()
    {
        $channels = $this->app->getRepository('channel')->findAll();
        $stats = [];
        foreach ($channels as $value) {
            $stats[ $value->getId() ] = $this->channelUsers($value->getId());
        }

        return $stats;
    }

    public function online()
    {
        $stats = $this->channelStats();
        $connections = $this->app->getSocketStore()->getConnections();
        foreach ($connections as $value) {
            $this->send($value, 'online', $stats[$value->getChannel()]);
        }
    }

    public function send($conn, $operation, $input = [])
    {
        $data = [];
        $data['operation'] = $operation;
        $data['subscription'] = [];
        $data['subscription']['channel'] = $conn->getChannel();
        $data['subscription']['user'] = $conn->getUser();
        $data['data'] = $input;

        $conn->getSocket()->send(json_encode($data));
    }
}
