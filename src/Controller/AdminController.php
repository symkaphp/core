<?php


namespace Symka\Core\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    public function index()
    {
        return $this->render('@SymkaCoreBundle\Resource\views\admin\index.html.twig');
    }
/*
    public function vertilcaMenuAction()
    {
        $params = $this->getParameter('admin_vertical_menu');
        return $this->render('@SymkaCoreBundle\Resource\views\admin\vertical_menu.html.twig', [
            'menuItems' => $params
        ]);
    }*/


}