<?php


namespace Symka\Core\Entity;


use Symka\Core\Interfaces\CrudRoutesEntityInterface;

class CrudRoutesEntity implements CrudRoutesEntityInterface
{
    private string $indexRoute = '';
    private string $indexController = '';

    private string $createRoute = '';
    private string $createController = '';

    private string $updateRoute = '';
    private string $updateController = '';

    private string $deleteSafeRoute = '';
    private string $deleteSafeController = '';

    private string $deleteItemSafeRoute = '';
    private string $deleteItemController = '';

    private string $redirectAfterSaveRoute = '';
    private string $redirectAfterSaveController = '';

    private ?array $params = null;

    public function getIndexRoute(): string
    {
        return $this->indexRoute;
    }
    
    public function setIndexRoute(string $indexRoute): CrudRoutesEntityInterface
    {
        $this->indexRoute = $indexRoute;
        return $this;
    }

    public function getIndexController(): string
    {
        return $this->indexController;
    }

    public function setIndexController(string $indexController): CrudRoutesEntityInterface
    {
        $this->indexController = $indexController;
        return $this;
    }

    public function getCreateRoute(): string
    {
        return $this->createRoute;
    }

    public function setCreateRoute(string $createRoute): CrudRoutesEntityInterface
    {
        $this->createRoute = $createRoute;
        return $this;
    }

    public function getCreateController(): string
    {
        return $this->createController;
    }

    public function setCreateController(string $createController): CrudRoutesEntityInterface
    {
        $this->createController = $createController;
        return $this;
    }

    public function getUpdateRoute(): string
    {
        return $this->updateRoute;
    }

    public function setUpdateRoute(string $updateRoute): CrudRoutesEntityInterface
    {
        $this->updateRoute = $updateRoute;
        return $this;
    }

    public function getUpdateController(): string
    {
        return $this->updateController;
    }

    public function setUpdateController(string $updateController): CrudRoutesEntityInterface
    {
        $this->updateController = $updateController;
        return $this;
    }

    public function getDeleteSafeRoute(): string
    {
        return $this->deleteSafeRoute;
    }

    public function setDeleteSafeRoute(string $deleteSafeRoute): CrudRoutesEntityInterface
    {
        $this->deleteSafeRoute = $deleteSafeRoute;
        return $this;
    }

    public function getDeleteSafeController(): string
    {
        return $this->deleteSafeController;
    }

    public function setDeleteSafeController(string $deleteSafeController): CrudRoutesEntityInterface
    {
        $this->deleteSafeController = $deleteSafeController;
        return $this;
    }

    public function getDeleteItemSafeRoute(): string
    {
        return $this->deleteItemSafeRoute;
    }

    public function setDeleteItemSafeRoute(string $deleteItemSafeRoute): CrudRoutesEntityInterface
    {
        $this->deleteItemSafeRoute = $deleteItemSafeRoute;
        return $this;
    }

    public function getDeleteItemController(): string
    {
        return $this->deleteItemController;
    }

    public function setDeleteItemController(string $deleteItemController): CrudRoutesEntityInterface
    {
        $this->deleteItemController = $deleteItemController;
        return $this;
    }

    public function getRedirectAfterSaveRoute(): string
    {
        return $this->redirectAfterSaveRoute;
    }

    public function setRedirectAfterSaveRoute(string $redirectAfterSaveRoute): CrudRoutesEntityInterface
    {
        $this->redirectAfterSaveRoute = $redirectAfterSaveRoute;
        return $this;
    }

    public function getRedirectAfterSaveController(): string
    {
        return $this->redirectAfterSaveController;
    }

    public function setRedirectAfterSaveController(string $redirectAfterSaveController): CrudRoutesEntityInterface
    {
        $this->redirectAfterSaveController = $redirectAfterSaveController;
        return $this;
    }
   
    public function getParams(): ?array
    {
        return $this->params;
    }

    public function setParams(?array $params): CrudRoutesEntityInterface
    {
        $this->params = $params;
        return $this;
    }
}