<?php
namespace LinkORB\Realtime;

use Radvance\Framework\BaseConsoleApplication;
use Radvance\Framework\FrameworkApplicationInterface;
use RuntimeException;

use LinkORB\Realtime\Repository;
use LinkORB\Realtime\Socket\Storage;
use LinkORB\Realtime\Socket\Publish;

class Console extends BaseConsoleApplication implements FrameworkApplicationInterface
{
    private $socketStore;
    private $publisher;

    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->configureSocket();
    }

    public function getRootPath()
    {
        return realpath(__DIR__ . '/../');
    }

    protected function configureRepositories()
    {
        $repo = new Repository($this->pdo);
        foreach ($repo->getRepositories() as $repository) {
            $this->addRepository($repository);
        }
    }

    protected function configureSocket()
    {
        $this->socketStore = new Storage();
        $this->publisher = new Publish($this);
    }

    public function getSocketStore()
    {
        return $this->socketStore;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }
}
