<?php


namespace Symka\Core\Exception;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symka\Core\Interfaces\CrudControllerInterface;

class CrudControllerException extends \Exception
{
    private CrudControllerInterface $crudController;
    private RedirectResponse $redirectResponse;

    const ERROR_VALIDATE = 1;
    const ERROR_VALIDATE_MESSAGE = 'Form not validate';

    const ERROR_NO_CRUD_ENTITY_INTERFACE = 2;
    const ERROR_NO_CRUD_ENTITY_INTERFACE_MESSAGE = 'Your Entity must be implement by Symka\Core\Interfaces\CrudEntityInterface';

    const ERROR_DATA_ALREADY_DELETED = 3;
    const ERROR_DATA_ALREADY_DELETED_MESSAGE = 'Data already deleted';

    const ERROR_DATA_ROUTE_NOT_FOUND = 4;
    const ERROR_DATA_ROUTE_NOT_FOUND_MESSAGE = 'Route not found';

    /**
     * @return CrudControllerInterface
     */
    public function getCrudController(): CrudControllerInterface
    {
        return $this->crudController;
    }

    /**
     * @param CrudControllerInterface $crudController
     * @return CrudControllerException
     */
    public function setCrudController(CrudControllerInterface $crudController): CrudControllerException
    {
        $this->crudController = $crudController;
        return $this;
    }

    /**
     * @return RedirectResponse
     */
    public function getRedirectResponse(): RedirectResponse
    {
        return $this->redirectResponse;
    }

    /**
     * @param RedirectResponse $redirectResponse
     * @return CrudControllerException
     */
    public function setRedirectResponse(RedirectResponse $redirectResponse): CrudControllerException
    {
        $this->redirectResponse = $redirectResponse;
        return $this;
    }





}