<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symka\Core\Entity\CrudRoutesEntity;
use Symka\Core\Entity\CrudRoutesEntityInterface;
use Symka\Core\Event\CrudAfterDeleteSafeEvent;
use Symka\Core\Event\CrudAfterSaveEvent;
use Symka\Core\Event\CrudBeforeDeleteSafeEvent;
use Symka\Core\Event\CrudBeforeSaveEvent;
use Symka\Core\Event\CrudBeforeShowGrid;
use Symka\Core\Event\CrudErrorDeleteSafeEvent;
use Symka\Core\Event\CrudErrorSaveEvent;
use Symka\Core\Event\CrudMultyItemFormEvent;
use Symka\Core\Event\CrudMultyItemFormSuccessEvent;
use Symka\Core\Exception\CrudControllerException;
use Symka\Core\Helper\HelperCrud;
use Symka\Core\Interfaces\CrudAfterDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudAfterSaveEventInterface;
use Symka\Core\Interfaces\CrudBeforeDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudBeforeShowGridInterface;
use Symka\Core\Interfaces\CrudControllerInterface;
use Symka\Core\Interfaces\CrudEntitySafeDeleteInterface;
use Symka\Core\Interfaces\CrudErrorDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudErrorSaveInterface;
use Symka\Core\Interfaces\CrudEntityInterface;
use Symka\Core\Interfaces\CrudBeforeSaveEventInterface;
use Symka\Core\Interfaces\CrudMultyItemFormSuccessEventInterface;

/*
 * Абстарктный класс, реализуте базовый функционал CRUD.
 * Клас реализует акшин методы:
 *  1. index - таблица списка данных с кнопками редактирования/удаления/добавления.
 *  2. save - функционал добавления/редактирования данных, выводится форма.
 *  3. deleteSafe - безопасное удаление. Данные не удаляются, помечаются как удаленные
 *
 *  Как это работает:
 *  Нужно создать класс контроллера и наследуется от этого класса.
 *
 *  !!!!! Важно !!!!!
 *  entityClass - имя класса Entity. Класс должен быть имплементирован от интерфейса CrudEntityInterface
 *  formClass - имя класса формы
 *  redirectAfterSaveRoute - роут, куда будет редиректится после успешного сохранения даннных.
 *
 *  Для более гибкого управления данными - реализованы события.
 *  События можно вызвать, напрмер в конструкторе класса.
 *  Например, можно изменить результат вывода данных в таблице:
 *
 *   public function __construct(EventDispatcherInterface $eventDispatcher)
 *   ...
 *      $eventDispatcher->addListener(CrudBeforeShowGridInterface::NAME, function(CrudBeforeShowGrid $event) {
 *            $queryBuilder = $event->getQueryBuilder();
 *           $queryBuilder->andWhere('t.id>2');
 *       });
 *   ...
 *
 *  Список собитий:
 *  Symka\Core\Event\CrudBeforeShowGrid - вызывается перед инициализацией и выводом данных в таблице
 *
 *  Symka\Core\Event\CrudBeforeSaveEvent - перед созраненияем данных
 *  Symka\Core\Event\CrudAfterSaveEvent - после сохранения данныз
 *  Symka\Core\Event\CrudErrorSaveEvent - при возникновении ошибки сохранения данных
 *
 *  Symka\Core\Event\CrudBeforeDeleteSafeEvent - перед безопасным удалением
 *  Symka\Core\Event\CrudAfterDeleteSafeEvent - после безопасного удалениея
 *  Symka\Core\Event\CrudErrorDeleteSafeEvent - при возникновении ошибки безопасного удалениея
 */

/**
 * Class AbstractCrudController
 * @package Symka\Core\Controller
 */
abstract class AbstractCrudController extends AbstractController implements CrudControllerInterface
{
    const DELETE_STATUS = 'delete';
    protected ?string $entityClass = '';
    protected ?string $formClass = '';
    protected string $redirectAfterSaveRoute;
    protected CrudRoutesEntity $crudRoutes;

    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        $this->crudRoutes = $this->getCrudRoutes($this);
        $this->formClass = $this->getFormClass($this);
        $this->entityClass = $this->getEntityClass($this);
        $this->addListeners($eventDispatcher);

    }

    public function index(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, PaginatorInterface $paginator, LoggerInterface $logger): Response
    {
        $result = [];
        $activePage = (int)$request->query->getInt('page', 1);

        $queryBuilder = $this->getQueryBuilder($entityManager);

        $eventDispatcher->dispatch(new CrudBeforeShowGrid($queryBuilder, $paginator), CrudBeforeShowGridInterface::NAME, $activePage);

        if ($queryBuilder instanceof QueryBuilder) {
            $pagination = $paginator->paginate($queryBuilder->getQuery(), $activePage, 10, ["distinct" => false]);
            $result['pagination'] = $pagination;

            $itemsTotalCount = $pagination->getTotalItemCount();

            if ($itemsTotalCount > 0) {
                $multySelectForm = $this->createFormBuilder();
                $multySelectForm->add("actionType", HiddenType::class);

                $items = $pagination->getItems();
                for ($i = 0; $i < ($itemsTotalCount <= $pagination->getItemNumberPerPage() ? $itemsTotalCount : $pagination->getItemNumberPerPage()); $i++) {
                    $multySelectForm->add("items_" . $items[$i]->getId(), CheckboxType::class, [
                        'attr' => ['class' => 'selectItemCheckbox'],
                        'label' => false,
                        'required' => false
                    ]);
                }
                $form = $multySelectForm->getForm();
                $form->handleRequest($request);

                $eventDispatcher->dispatch(new CrudMultyItemFormEvent($form), CrudMultyItemFormEvent::NAME);

                if ($form->isSubmitted()) {
                    if (!$form->isValid()) {
                        $this->addFlash('error', 'From not validate');
                    } else {
                        $deletedIdRecordsArray = HelperCrud::getMultyItemsIds($form->getData());
                        if (!empty($deletedIdRecordsArray)) {
                            $eventDispatcher->dispatch(new CrudMultyItemFormSuccessEvent($form, $this->entityClass, $deletedIdRecordsArray), CrudMultyItemFormSuccessEventInterface::NAME);

                            try {
                                $this->saveSelectedItems($entityManager, $deletedIdRecordsArray, $form->getData()['actionType']);
                            } catch (\Exception $e) {

                                throw $e;
                            }

                            return $this->redirectToRoute($this->crudRoutes->getRedirectAfterSaveRoute());

                        }
                    }
                }
                $result['checkboxItemForm'] = $form->createView();
            }
        }
        $tpl = HelperCrud::getViewPathByFunctionName(get_class($this), __FUNCTION__, $this->getViewPath());

        $result['deletedCount'] = $this->getDeletedCount($entityManager);

        return $this->render($tpl, $result);
    }

    public function save(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher, ?int $id = null): Response
    {
        $result = [
            'id' => $id
        ];
        try {

            if ($id !== null && $id > 0) {
                $entity = $entityManager->find($this->entityClass, $id);
                if (!$entity) {
                    throw $this->createNotFoundException();
                }
            } else {
                $entity = new $this->entityClass();
            }

            $form = $this->createForm($this->formClass, $entity);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                $formData = $form->getData();
                $eventDispatcher->dispatch(new CrudBeforeSaveEvent($formData, $id), CrudBeforeSaveEventInterface::NAME);

                if ($form->isValid()) {
                    try {
                        $entityManager->beginTransaction();
                        if ($id === null) {
                            $entityManager->persist($formData);
                        }
                        $entityManager->flush();
                        $entityManager->commit();
                        $eventDispatcher->dispatch(new CrudAfterSaveEvent($formData), CrudAfterSaveEventInterface::NAME);

                    } catch (\Exception $exception) {

                        $entityManager->rollback();
                        $eventDispatcher->dispatch(new CrudErrorSaveEvent($formData, $exception, $id), CrudErrorSaveInterface::NAME);
                    }

                    if ($request->request->get('is_save_and_create') == '1') {
                        return $this->redirectToRoute($this->crudRoutes->getCreateRoute());
                    }
                    return $this->redirectToRoute($this->crudRoutes->getRedirectAfterSaveRoute(), $this->crudRoutes->getParams());

                } else {
                    $exception = new CrudControllerException(CrudControllerException::ERROR_VALIDATE_MESSAGE, CrudControllerException::ERROR_VALIDATE);
                    $eventDispatcher->dispatch(new CrudErrorSaveEvent($formData, $exception, $id), CrudErrorSaveInterface::NAME);
                }
            }
        } catch (CrudControllerException $crudControllerException) {
            if ($crudControllerException->getCode() != CrudControllerException::ERROR_VALIDATE) {
                $logger->error("Error Save Crud ", [
                    "class" => get_class($this),
                    "code" => $crudControllerException->getCode(),
                    "message" => $crudControllerException->getMessage()
                ]);
                $eventDispatcher->dispatch(new CrudErrorSaveEvent($entity, $crudControllerException, $id), CrudErrorSaveInterface::NAME);
            }
        } catch (\Exception $exception) {

            $eventDispatcher->dispatch(new CrudErrorSaveEvent($entity, $exception, $id), CrudErrorSaveInterface::NAME);
            throw $exception;
        }

        $result['form'] = $form->createView();

        return $this->render(HelperCrud::getViewPathByFunctionName(get_class($this), __FUNCTION__, $this->getViewPath()), $result);
    }

    public function deleteSafe(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, int $id): Response
    {
        $entity = $entityManager->find($this->entityClass, $id);

        if (!$entity) {
            throw $this->createNotFoundException();
        }

        if (!($entity instanceof CrudEntityInterface)) {
            $exception = new CrudControllerException(CrudControllerException::ERROR_NO_CRUD_ENTITY_INTERFACE_MESSAGE, CrudControllerException::ERROR_NO_CRUD_ENTITY_INTERFACE);
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEvent::NAME);
            throw $exception;
        } elseif ($entity instanceof  CrudEntitySafeDeleteInterface && $entity->getStatus() == CrudEntitySafeDeleteInterface::STATUS_DELETED) {
            $exception = new CrudControllerException(CrudControllerException::ERROR_DATA_ALREADY_DELETED_MESSAGE, CrudControllerException::ERROR_DATA_ALREADY_DELETED);
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEvent::NAME);
            return $this->redirectToRoute($this->crudRoutes->getRedirectAfterSaveRoute(), ['id' => $id]);
        }

        $eventDispatcher->dispatch(new CrudBeforeDeleteSafeEvent($entity, $id), CrudBeforeDeleteSafeEventInterface::NAME);

        try {
            $this->saveSelectedItems($entityManager, [$id], self::DELETE_STATUS);
            $eventDispatcher->dispatch(new CrudAfterDeleteSafeEvent($entity, $id), CrudAfterDeleteSafeEventInterface::NAME);
        } catch (\Exception $e) {
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $e, $id), CrudErrorDeleteSafeEventInterface::NAME);
            throw $e;
        }


        return $this->redirectToRoute($this->crudRoutes->getRedirectAfterSaveRoute(), ['id' => $id]);
    }

    protected function getCrudRoutes(CrudControllerInterface $controller): CrudRoutesEntity
    {
        return HelperCrud::getRoutes(get_class($controller));
    }

    protected function getEntityClass(CrudControllerInterface $controller, string $entityPrefix = 'Entity', string $entityCatalogName = 'Entity'): ?string
    {
        return HelperCrud::getEntityClassName(get_class($controller), $entityPrefix, $entityCatalogName);
    }

    protected function getFormClass(CrudControllerInterface $controller, string $formPrefix = 'FormType', string $formCatalogName = 'Form'): ?string
    {
        return HelperCrud::getFormTypeClassName(get_class($controller), $formPrefix, $formCatalogName);
    }

    protected function addListeners(EventDispatcherInterface $eventDispatcher): void
    {
        $eventDispatcher->addListener(CrudAfterSaveEventInterface::NAME, function (CrudAfterSaveEvent $event) {
            $this->addFlash('success', 'Data saved');
        });

        $eventDispatcher->addListener(CrudErrorSaveInterface::NAME, function (CrudErrorSaveEvent $event) {

            if ($event->getException() instanceof CrudControllerException && $event->getException()->getCode() == CrudControllerException::ERROR_VALIDATE) {
                $this->addFlash('error', 'From not validate');
            } elseif ($event->getException() instanceof UniqueConstraintViolationException) {
                $this->addFlash('error', 'Data already exists');
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

        $eventDispatcher->addListener(CrudMultyItemFormSuccessEventInterface::NAME, function (CrudMultyItemFormSuccessEvent $event) {
            $this->addFlash('success', 'Data deleted');
        });
        return;
    }

    protected function getQueryBuilder(EntityManagerInterface $entityManager): QueryBuilder
    {
        return  $entityManager->getRepository($this->entityClass)
            ->createQueryBuilder('t')
            ->where('t.status!=:deletedStatus')
            ->setParameter('deletedStatus', CrudEntitySafeDeleteInterface::STATUS_DELETED);
    }

    protected function getDeletedCount(EntityManagerInterface $entityManager): int
    {
        if (!is_a($this->entityClass, CrudEntitySafeDeleteInterface::class, true)) {
            return 0;
        }
        try {
            return (int)$entityManager
                ->createQueryBuilder()
                ->select('COUNT(CASE WHEN t.status=:deletedStatus THEN t.id ELSE :null END)')
                ->from($this->entityClass, 't')
                ->setParameter('deletedStatus', CrudEntitySafeDeleteInterface::STATUS_DELETED)
                ->setParameter('null',null)

                ->getQuery()
               ->getSingleScalarResult()
            ;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    protected function saveSelectedItems(EntityManagerInterface $entityManager, array $idArray, ?string $action = null): void
    {
        try {
            $entityManager->beginTransaction();

            $entityManager
                ->createQueryBuilder()
                ->update($this->entityClass, 't')
                ->set('t.status', CrudEntitySafeDeleteInterface::STATUS_DELETED)
                ->set('t.deletedAt', ':deletedAt')
                ->where('t.id IN (:idArray)')
                ->setParameter('idArray', $idArray)
                ->setParameter('deletedAt', new \DateTime('now'))
                ->getQuery()
                ->execute()
            ;
            $entityManager->commit();
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    abstract protected function getViewPath(): string;


}