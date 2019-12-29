<?php


namespace Symka\Core\Controller;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symka\Core\Entity\SiteDefaultConfigEntity;
use Symka\Core\Event\CrudAfterDeleteSafeEvent;
use Symka\Core\Event\CrudAfterSaveEvent;
use Symka\Core\Event\CrudBeforeSaveEvent;
use Symka\Core\Event\CrudBeforeShowGrid;
use Symka\Core\Event\CrudErrorDeleteSafeEvent;
use Symka\Core\Event\CrudErrorSaveEvent;
use Symka\Core\Exception\CrudControllerException;
use Symka\Core\Form\SiteDefaultConfigFormType;
use Symka\Core\Interfaces\CrudAfterDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudAfterSaveEventInterface;
use Symka\Core\Interfaces\CrudBeforeSaveEventInterface;
use Symka\Core\Interfaces\CrudBeforeShowGridInterface;
use Symka\Core\Interfaces\CrudErrorDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudErrorSaveInterface;

class SiteConfigController extends AbstractCrudController
{
    protected string $entityClass = SiteDefaultConfigEntity::class;
    protected string $formClass = SiteDefaultConfigFormType::class;
    protected string $redirectAfterSaveRoute = 'symka_core_admin_site_config_index';

    public function __construct(EventDispatcherInterface $eventDispatcher)
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

        $eventDispatcher->addListener(CrudAfterSaveEventInterface::NAME, function (CrudAfterSaveEvent $event) {
            $this->addFlash('success', 'Data saved');
        });

        $eventDispatcher->addListener(CrudErrorSaveInterface::NAME, function (CrudErrorSaveEvent $event) {
             if ($event->getException() instanceof CrudControllerException && $event->getException()->getCode() == CrudControllerException::ERROR_VALIDATE) {
                $this->addFlash('error', 'From not validate');
             } else {
                 $this->addFlash('error', 'Some errors');
             }
        });

        $eventDispatcher->addListener(CrudErrorDeleteSafeEventInterface::NAME, function (CrudErrorDeleteSafeEvent $event) {
            if ($event->getException() instanceof CrudControllerException && $event->getException()->getCode() == CrudControllerException::ERROR_DATA_ALREADY_DELETED) {
                $this->addFlash('error', CrudControllerException::ERROR_DATA_ALREADY_DELETED_MESSAGE);
            } else {
                $this->addFlash('error', 'Some errors');
            }
        });

        $eventDispatcher->addListener(CrudAfterDeleteSafeEventInterface::NAME, function (CrudAfterDeleteSafeEvent $event) {
            $this->addFlash('success', 'Data deleted');
        });
    }
}