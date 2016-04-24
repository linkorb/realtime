<?php

namespace LinkORB\Realtime\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Radvance\Controller\BaseController;

class AppController extends BaseController
{
    public function indexAction(Application $app, Request $request)
    {
        return $this->renderIndex(
            $app
        );
    }

    protected function getEditForm(Application $app, Request $request, $id = null)
    {
        $add = false;
        if (isset($id)) {
            $add = true;
        }

        $appRepo = $app->getRepository($this->getModelName());
        $entity = $appRepo->findOrCreate($id);

        $form = $app->getForm()->appEdit($entity, $add);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            if ($form->isValid()) {
                $entity->loadFromArray($data);

                $entity->setSecret(bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)));

                if ($appRepo->persist($entity)) {
                    return $app->redirect(
                        $app['url_generator']->
                        generate(
                            sprintf('%s_view', $this->getModelName()),
                            array(
                                'id' =>  $entity->getId()
                            )
                        )
                    );
                }
            }
        }

        return $this->renderEdit(
            $app,
            array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }
}
