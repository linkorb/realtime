<?php

namespace LinkORB\Realtime\Repository;

use Radvance\Repository\BaseRepository;
use Radvance\Repository\RepositoryInterface;
use LinkORB\Realtime\Model\Channel;
use PDO;

class PdoChannelRepository extends BaseRepository implements RepositoryInterface
{
    public function createEntity()
    {
        return Channel::createNew();
    }
}
