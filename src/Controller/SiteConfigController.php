<?php


namespace Symka\Core\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteConfigController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('@SymkaCoreBundle/Resource/views/siteConfig/index.html.twig');
    }
}