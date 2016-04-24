<?php

namespace LinkORB\Realtime\Model;

use Radvance\Model\ModelInterface;
use Radvance\Model\BaseModel;

class App extends BaseModel implements ModelInterface
{
    protected $id;
    protected $name;
    protected $secret;
    protected $description;
}
