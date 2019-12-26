<?php


namespace Symka\Core\Controller;

use Symka\Core\Entity\SiteDefaultConfigEntity;

class SiteConfigController extends AbstractCrudController
{
    protected string $entityClass = SiteDefaultConfigEntity::class;
    protected string $formClass;
    protected string $redirectAfterSaveRoute;
}