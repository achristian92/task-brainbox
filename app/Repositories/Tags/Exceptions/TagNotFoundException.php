<?php


namespace App\Repositories\Tags\Exceptions;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct("Etiqueta no encontrada");
    }
}
