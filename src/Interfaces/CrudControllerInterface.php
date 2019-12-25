<?php


namespace Symka\Core\Interfaces;


interface CrudControllerInterface
{
    public function getDefaultTemplateNamespace(): string;
    public function getTemplateDir(string $actionName): string;
    public function getDefaultTemplateExtension(): string;
    public function getRouteRedirectAfterSave(?int $id = null): string;
}