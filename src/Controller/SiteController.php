<?php

namespace Symka\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends AbstractController
{
    public function indexAction() : Response
    {

        return $this->render('@SymkaCoreBundle/Resource/views/site/index.html.twig');
    }
}