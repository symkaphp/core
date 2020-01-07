<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symka\Core\Entity\SiteConfigEntity;
use Symka\Core\Event\CrudBeforeSaveEvent;

use Symka\Core\Interfaces\CrudBeforeSaveEventInterface;


class SiteConfigController extends AbstractCrudController
{

    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        $eventDispatcher->addListener(CrudBeforeSaveEventInterface::NAME, function(CrudBeforeSaveEvent $event) {
            $formData = $event->getFormData();

            if (empty($formData->getTemplatePath())) {
                $formData->setTemplatePath('default');
            }

            if (empty($formData->getAdminTemplatePath())) {
                $formData->setAdminTemplatePath('default/admin');
            }

            if ($event->getId() === null) {
                $formData->setCreatedAt(new \DateTime('now'));
            } else {
                $formData->setUpdatedAt(new \DateTime('now'));
            }
        });

       parent::__construct($eventDispatcher, $router);
       $this->crudRoutes->setParams([]);
    }

    protected function getViewPath(): string
    {
        return '@SymkaCoreBundle/Resource/views';
    }

}