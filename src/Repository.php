<?php

namespace LinkORB\Realtime;

use LinkORB\Realtime\Repository\PdoAppRepository;
use LinkORB\Realtime\Repository\PdoChannelRepository;

class Repository
{
    private $repos = [];
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->configureRepositories();
    }

    private function configureRepositories()
    {
        array_push($this->repos, new PdoAppRepository($this->pdo));
        array_push($this->repos, new PdoChannelRepository($this->pdo));
    }

    public function getRepositories()
    {
        return $this->repos;
    }
}
