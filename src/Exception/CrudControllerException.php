<?php


namespace Symka\Core\Exception;


class CrudControllerException extends \Exception
{
    const ERROR_VALIDATE = 1;
    const ERROR_VALIDATE_MESSAGE = 'Form not validate';
}