<?php


namespace App\Repositories\Activities\Exceptions;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerNotFoundException extends NotFoundHttpException
{
        public function __construct()
        {
            parent::__construct("Cliente no encontrado");
        }
}
