<?php


namespace Symka\Core\Helper;


use Symka\Core\Interfaces\CrudControllerInterface;

class HelperCrud
{
    public static function getViewPathByFunctionName(CrudControllerInterface $controller, string $functionName, string $nameSpace = '@SymkaCoreBundle/Resource/views', string $defaultTemplateExtension = '.html.twig') : string
    {
        $classShortName = (new \ReflectionClass($controller))->getShortName();
        $classShortName = str_replace('Controller', '', $classShortName);
        $classShortName = lcfirst($classShortName);
        return $nameSpace. '/' . $classShortName . '/' . $functionName . $defaultTemplateExtension;
    }
}