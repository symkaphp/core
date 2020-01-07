<?php

namespace Symka\Core\Interfaces;

interface CrudRoutesEntityInterface
{
    public function getIndexRoute(): string;
    public function setIndexRoute(string $indexRoute): CrudRoutesEntityInterface;
    public function getIndexController(): string;
    public function setIndexController(string $indexController): CrudRoutesEntityInterface;
    public function getCreateRoute(): string;
    public function setCreateRoute(string $createRoute): CrudRoutesEntityInterface;
    public function getCreateController(): string;
    public function setCreateController(string $createController): CrudRoutesEntityInterface;
    public function getUpdateRoute(): string;
    public function setUpdateRoute(string $updateRoute): CrudRoutesEntityInterface;
    public function getUpdateController(): string;
    public function setUpdateController(string $updateController): CrudRoutesEntityInterface;
    public function getDeleteSafeRoute(): string;
    public function setDeleteSafeRoute(string $deleteSafeRoute): CrudRoutesEntityInterface;
    public function getDeleteSafeController(): string;
    public function setDeleteSafeController(string $deleteSafeController): CrudRoutesEntityInterface;
    public function getDeleteItemSafeRoute(): string;
    public function setDeleteItemSafeRoute(string $deleteItemSafeRoute): CrudRoutesEntityInterface;
    public function getDeleteItemController(): string;
    public function setDeleteItemController(string $deleteItemController): CrudRoutesEntityInterface;
    public function getRedirectAfterSaveRoute(): string;
    public function setRedirectAfterSaveRoute(string $redirectAfterSaveRoute): CrudRoutesEntityInterface;
    public function getRedirectAfterSaveController(): string;
    public function setRedirectAfterSaveController(string $redirectAfterSaveController): CrudRoutesEntityInterface;
    public function getParams(): ?array;
    public function setParams(?array $params): CrudRoutesEntityInterface;
}