<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends AbstractFOSRestController
{
    /**
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormInterface
     */
    protected function buildForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    /**
     * @param $data
     * @param int $statusCode
     * @return Response
     */
    protected function respond($data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->handleView($this->view($data, $statusCode));
    }
}