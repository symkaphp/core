<?php
declare(strict_types=1);

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

    public static function getMultyItemsIds(array $formData): array
    {
        $returnArray = [];
        foreach ($formData as $key => $value) {
            if (preg_match('/items_(\d+)/', $key, $res) && isset($res[1]) && $value === true) {
                $returnArray[] = (int)$res[1];
            }
        }
        return $returnArray;
    }

}