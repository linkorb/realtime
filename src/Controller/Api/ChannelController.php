<?php

namespace LinkORB\Realtime\Controller\Api;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Radvance\Controller\BaseController;

class ChannelController extends BaseController
{
    public function addAction(Application $app, Request $request)
    {
        $params = $app->getJsonHandler()->jsonToArray($request);
        $rt_app = $app->verfiySecret($params);
        if (!$rt_app) {
            return $app->getJsonHandler()->getResponse(401, "Invalid Application ID or Application Secret.");
        } elseif (!isset($params['data']['name'])) {
            return $app->getJsonHandler()->getResponse(400, "Channel requires name.");
        } else {
            $channel = $app->getRepository('channel')->createEntity();
            $channel->setAppId($rt_app->getId());
            $channel->setName($params['data']['name']);
            if (isset($params['data']['description'])) {
                $channel->setDescription($params['data']['description']);
            }
            $app->getRepository('channel')->persist($channel);
            $app->getZmqSocket()->send(json_encode(['channel'=>'test successful']));
            return $app->getJsonHandler()->getResponse(201, "Channel created.");
        }
    }
}
