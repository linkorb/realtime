<?php

namespace LinkORB\Realtime\Util;

class JsonHandler
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function getResponse($code, $message)
    {
        $data = array();
        $data['message'] = $message;
        return $this->app->json($data, $code);
    }

    public function jsonToArray($request)
    {
        $params = [];
        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true); // 2nd param to get as array
        }
        return $params;
    }
}
