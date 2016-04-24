<?php

namespace LinkORB\Realtime\Model;

use Radvance\Model\ModelInterface;
use Radvance\Model\BaseModel;

class Channel extends BaseModel implements ModelInterface
{
    protected $id;
    protected $name;
    protected $description;

    protected $app_id;
}
