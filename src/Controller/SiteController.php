<?php

namespace Symka\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('@SymkaCoreBundle/Resource/views/site/index.html.twig');
    }
}