<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symka\Core\Event\CrudAfterDeleteSafeEvent;
use Symka\Core\Event\CrudAfterSaveEvent;
use Symka\Core\Event\CrudBeforeDeleteSafeEvent;
use Symka\Core\Event\CrudBeforeSaveEvent;
use Symka\Core\Event\CrudBeforeShowGrid;
use Symka\Core\Event\CrudErrorDeleteSafeEvent;
use Symka\Core\Event\CrudErrorSaveEvent;
use Symka\Core\Exception\CrudControllerException;
use Symka\Core\Helper\HelperCrud;
use Symka\Core\Interfaces\CrudAfterDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudAfterSaveEventInterface;
use Symka\Core\Interfaces\CrudBeforeDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudBeforeShowGridInterface;
use Symka\Core\Interfaces\CrudControllerInterface;
use Symka\Core\Interfaces\CrudErrorDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudErrorSaveInterface;
use Symka\Core\Interfaces\CrudGridInterface;
use Symka\Core\Interfaces\CrudEntityInterface;
use Symka\Core\Interfaces\CrudBeforeSaveEventInterface;

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
    protected string $entityClass;
    protected string $formClass;
    protected string $redirectAfterSaveRoute;

    public function index(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, PaginatorInterface $paginator): Response
    {
        $result = [];
        $activePage = (int)$request->query->getInt('page', 1);
        $queryBuilder = $entityManager->getRepository($this->entityClass)
            ->createQueryBuilder('t')
            ->where('t.status!=:deletedStatus')
            ->setParameter(':deletedStatus', (new $this->entityClass())->getStatusDeleted());

        $eventDispatcher->dispatch(new CrudBeforeShowGrid($queryBuilder, $paginator), CrudBeforeShowGridInterface::NAME, $activePage);

        if ($queryBuilder instanceof QueryBuilder) {
            $pagination = $paginator->paginate($queryBuilder->getQuery(), $activePage);
            $result['pagination'] = $pagination;
        }

        return $this->render(HelperCrud::getViewPathByFunctionName($this, __FUNCTION__), $result);
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

                    } catch (\Exception $e) {
                        $entityManager->rollback();
                        throw new CrudControllerException($e->getMessage(), $e->getCode());
                    }

                    if ($request->request->get('symka_core_admin_site_config_create') == '1') {
                        return $this->redirectToRoute($this->redirectAfterSaveRoute, ['id' => $id]);
                    }
                    return $this->redirectToRoute($this->redirectAfterSaveRoute, ['id' => $id]);
                } else {
                    $exception = new CrudControllerException(CrudControllerException::ERROR_VALIDATE_MESSAGE, CrudControllerException::ERROR_VALIDATE);
                    $eventDispatcher->dispatch(new CrudErrorSaveEvent($formData, $exception, $id), CrudErrorSaveInterface::NAME);
                    throw $exception;
                }
            }
        } catch (CrudControllerException $crudControllerException) {
            if ($crudControllerException->getCode() != CrudControllerException::ERROR_VALIDATE) {
                $logger->error("Error Save Crud ", [
                    "class" => get_class($this),
                    "code" => $crudControllerException->getCode(),
                    "message" => $crudControllerException->getMessage()
                ]);
                $eventDispatcher->dispatch(new CrudErrorSaveEvent($formData, $crudControllerException, $id), CrudErrorSaveInterface::NAME);
            }
        } catch (\Exception $exception) {
            $eventDispatcher->dispatch(new CrudErrorSaveEvent($formData, $exception, $id), CrudErrorSaveInterface::NAME);
            throw $exception;
        }

        $result['form'] = $form->createView();

        return $this->render(HelperCrud::getViewPathByFunctionName($this, __FUNCTION__), $result);
    }

    public function deleteSafe(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, int $id): Response
    {
        $entity = $entityManager->find($this->entityClass, $id);

        if (!$entity) {
            $exception = $this->createNotFoundException();
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEvent::NAME);
            throw $exception;
        }

        if (!($entity instanceof CrudEntityInterface)) {
            $exception = new CrudControllerException(CrudControllerException::ERROR_NO_CRUD_ENTITY_INTERFACE_MESSAGE, CrudControllerException::ERROR_NO_CRUD_ENTITY_INTERFACE);
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEvent::NAME);
            throw $exception;
        } elseif ($entity->getStatus() == $entity->getStatusDeleted()) {
            $exception = new CrudControllerException(CrudControllerException::ERROR_DATA_ALREADY_DELETED_MESSAGE, CrudControllerException::ERROR_DATA_ALREADY_DELETED);
            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEvent::NAME);
            return $this->redirectToRoute($this->redirectAfterSaveRoute, ['id' => $id]);
        }

        $eventDispatcher->dispatch(new CrudBeforeDeleteSafeEvent($entity, $id), CrudBeforeDeleteSafeEventInterface::NAME);

        try {
            $entityManager->beginTransaction();
            $entity->setStatus($entity->getStatusDeleted());
            $entity->setDeletedAt(new \DateTime('now'));
            $entityManager->flush();
            $entityManager->commit();
            $eventDispatcher->dispatch(new CrudAfterDeleteSafeEvent($entity), CrudAfterDeleteSafeEventInterface::NAME);

        } catch (\Exception $exception) {
            $entityManager->rollback();

            $logger->error('Crud Delete Safe. Error save', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);

            $eventDispatcher->dispatch(new CrudErrorDeleteSafeEvent($entity, $exception, $id), CrudErrorDeleteSafeEventInterface::NAME);
            throw $exception;
        }

        return $this->redirectToRoute($this->redirectAfterSaveRoute, ['id' => $id]);
    }
}