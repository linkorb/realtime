<?php

namespace LinkORB\Realtime\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function loginAction(Application $app, Request $request)
    {
        return new Response($app['twig']->render(
            'auth/login.html.twig',
            array(
                'error' => $app['security.last_error']($request)
            )
        ));
    }

    public function logoutAction(Application $app, Request $request)
    {
        $app['session']->start();
        $app['session']->invalidate();

        return $app->redirect('/');
    }
}
