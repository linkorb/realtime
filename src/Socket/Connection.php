<?php
namespace LinkORB\Realtime\Socket;

use Ratchet\ConnectionInterface as SocketInterface;

class Connection
{
    private $user;
    private $soc;
    private $channel;
    private $app;

    public function __construct($app, SocketInterface $soc)
    {
        $this->app = $app;
        $this->soc = $soc;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getSocket()
    {
        return $this->soc;
    }

    public function subscribe()
    {
        $channelRepo = $this->app->getRepository('channel');
        $channel = $channelRepo->findOneOrNullBy(['id' =>$this->getChannel()]);
        if ($channel) {
            $this->app->getSocketStore()->attach($this);
            $this->app->getPublisher()->channel($channel->getId());
            return true;
        } else {
            // publish that channel not found
            $this->app->getPublisher()->send($this, 'unsubscribe', ['message'=>'Channel not found.']);
        }
    }
}
