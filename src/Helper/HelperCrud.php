<?php
declare(strict_types=1);

namespace Symka\Core\Helper;

use Symka\Core\Entity\CrudRoutesEntity;
use Symka\Core\Entity\CrudRoutesEntityInterface;
use Symka\Core\Interfaces\CrudControllerInterface;

class HelperCrud
{
    public static function getViewPathByFunctionName(string $controller, string $functionName, string $nameSpace, string $defaultTemplateExtension = '.html.twig') : string
    {
        $classShortName = (new \ReflectionClass($controller))->getShortName();
        $classShortName = str_replace('Controller', '', $classShortName);
        $classShortName = lcfirst($classShortName);
        return $nameSpace. '/' . $classShortName . '/' . $functionName . $defaultTemplateExtension;
    }

    public static function getFormTypeClassName(string $controller, string $formPrefix = 'FormType', string $formCatalogName = 'Form'): ?string
    {
        return self::getClassName($controller, $formPrefix, $formCatalogName);
    }

    public static function getRoutes(string $controller): CrudRoutesEntity
    {
        $reflection = new \ReflectionClass($controller);

        $str = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'],'$1.$2', $reflection->getShortName()));
        $str = str_replace(['\\', '..'],['', '.'], $str);

        $crudRoutesEntity = new CrudRoutesEntity();

        $crudRoutesEntity->setIndexRoute( $str.'.index');
        $crudRoutesEntity->setIndexController($reflection->getName().'::index');

        $crudRoutesEntity->setCreateRoute( $str.'.create');
        $crudRoutesEntity->setCreateController($reflection->getName().'::create');

        $crudRoutesEntity->setUpdateRoute( $str.'.update');
        $crudRoutesEntity->setUpdateController($reflection->getName().'::update');

        $crudRoutesEntity->setDeleteSafeRoute( $str.'.delete-safe');
        $crudRoutesEntity->setDeleteSafeController($reflection->getName().'::deleteSafe');

        $crudRoutesEntity->getDeleteItemSafeRoute( $str.'.delete-item');
        $crudRoutesEntity->setDeleteItemController($reflection->getName().'::itemSafe');

        $crudRoutesEntity->setRedirectAfterSaveRoute( $crudRoutesEntity->getIndexRoute());
        $crudRoutesEntity->setDeleteItemController($crudRoutesEntity->getIndexController());

        return $crudRoutesEntity;
    }

    public static function getRouteXml(CrudRoutesEntity $crudRoutesEntity): string
    {
        //header('Content-type: text/xml');
        //header('Content-Disposition: attachment; filename="text.xml"');

        $ret = '';

        $ret = '<?xml version="1.0" encoding="UTF-8" ?>';
        $ret .= "\n\t".'<routes xmlns="http://symfony.com/schema/routing" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://symfony.com/schema/routing http://symfony.com/schema/routing/routing-1.0.xsd">';
        $ret .= "\n\t\t".'<route id="'.$crudRoutesEntity->getIndexRoute().'" path="/" controller="'.$crudRoutesEntity->getIndexController().'" methods="GET"/>';
        $ret .= "\n\t\t".'<route id="'.$crudRoutesEntity->getCreateRoute().'" path="/create" controller="'.$crudRoutesEntity->getCreateController().'" methods="GET,POST"/>';
        $ret .= "\n\t\t".'<route id="'.$crudRoutesEntity->getUpdateRoute().'" path="/update/{id}" controller="'.$crudRoutesEntity->getUpdateController().'" methods="GET,POST"/>';
        $ret .= "\n\t\t".'<route id="'.$crudRoutesEntity->getDeleteSafeRoute().'" path="/delete-save/{id}" controller="'.$crudRoutesEntity->getDeleteSafeController().'" methods="GET"/>';
        $ret .= "\n\t\t".'<route id="'.$crudRoutesEntity->getDeleteItemSafeRoute().'" path="/delete-item" controller="'.$crudRoutesEntity->getDeleteItemController().'" methods="GET"/>';
        $ret .= "\n\t".'</routes>';
        return $ret;
    }


    public static function getEntityClassName(string $controller, string $entityPrefix = 'Entity', string $entityCatalogName = 'Entity'): ?string
    {
        return self::getClassName($controller, $entityPrefix, $entityCatalogName);
    }

    private static function getClassName(string $controller, string $prefix, string $catalogName, bool $returnIfClassNotExists = true): ?string
    {
        $reflection = new \ReflectionClass($controller);

        $result = $reflection->getShortName();
        $namespace = $reflection->getNamespaceName();

        $result = str_replace('Controller', $prefix, $result);
        $namespace = str_replace('Controller', $catalogName, $namespace);
        $str = $namespace.'\\'.$result;
        if (!$returnIfClassNotExists || class_exists($str)) {
            return $str;
        }
        return null;
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