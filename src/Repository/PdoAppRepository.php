<?php

namespace LinkORB\Realtime\Repository;

use Radvance\Repository\BaseRepository;
use Radvance\Repository\RepositoryInterface;
use LinkORB\Realtime\Model\App;
use PDO;

class PdoAppRepository extends BaseRepository implements RepositoryInterface
{
    public function createEntity()
    {
        return App::createNew();
    }
}
