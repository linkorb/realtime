<?php

namespace LinkORB\Realtime\Util;

class Form
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function appEdit($entity, $add)
    {

        $button = $add ? 'Update Application' : 'Add Application';

        return $this->app['form.factory']->createBuilder('form', $entity)
            ->add(
                'name',
                'text',
                array(
                    'required' => true,
                    'trim' => true,
                    'attr' => array(
                        'placeholder' => 'Name of Application',
                        'class' => 'form-control',
                    ),
                )
            )
            ->add(
                'description',
                'textarea',
                array(
                    'required' => true,
                    'trim' => true,
                    'attr' => array(
                        'placeholder' => 'Application Description',
                        'class' => 'form-control',
                    ),
                )
            )
            ->add(
                $button,
                'submit',
                array(
                    'attr' => array(
                        'class' => 'btn btn-default',
                    ),
                )
            )
            ->getForm();

    }
}
