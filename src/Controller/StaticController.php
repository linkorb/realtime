<?php

namespace LinkORB\Realtime\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Radvance\Controller\BaseController;

class StaticController extends BaseController
{
    public function indexAction(Application $app, Request $request)
    {
        return $app->redirect('/app');
    }
}
