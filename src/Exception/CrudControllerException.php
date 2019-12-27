<?php


namespace Symka\Core\Exception;


class CrudControllerException extends \Exception
{
    const ERROR_VALIDATE = 1;
    const ERROR_VALIDATE_MESSAGE = 'Form not validate';

    const ERROR_NO_CRUD_ENTITY_INTERFACE = 2;
    const ERROR_NO_CRUD_ENTITY_INTERFACE_MESSAGE = 'Your Entity must be implement by Symka\Core\Interfaces\CrudEntityInterface';

    const ERROR_DATA_ALREADY_DELETED = 3;
    const ERROR_DATA_ALREADY_DELETED_MESSAGE = 'Data already deleted';
}