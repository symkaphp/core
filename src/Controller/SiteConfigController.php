<?php


namespace Symka\Core\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symka\Core\Entity\SiteDefaultConfigEntity;
use Symka\Core\Form\SiteDefaultConfigFormType;

class SiteConfigController extends AbstractCrudController
{
    protected string $entityClass = SiteDefaultConfigEntity::class;
    protected string $formClass = SiteDefaultConfigFormType::class;
    protected string $redirectAfterSaveRoute = 'symka_core_admin_site_config_index';

}