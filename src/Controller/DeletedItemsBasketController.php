<?php
declare(strict_types=1);

namespace Symka\Core\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symka\Core\Helper\HelperCrud;
use Symka\Core\Interfaces\CrudEntitySafeDeleteInterface;

class DeletedItemsBasketController extends AbstractCrudController
{

    const BACKUP_STATUS = 'backup';

    public function backup(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, int $id) : Response
    {
        try {
            $this->saveSelectedItems($entityManager, [$id], self::BACKUP_STATUS);
            $this->addFlash('success', 'Backup is success');
        } catch (\Exception $exception) {
            $logger->error('Backup data error', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);

            $this->addFlash('error', 'Error backup data');
        }

        return $this->redirectToRoute($this->crudRoutes->getIndexRoute());
    }



    protected function getQueryBuilder(EntityManagerInterface $entityManager): QueryBuilder
    {
        return  $entityManager->getRepository($this->entityClass)
            ->createQueryBuilder('t');
    }

    protected function getViewPath(): string
    {
        return '@SymkaCoreBundle/Resource/views';
    }

    protected function saveSelectedItems(EntityManagerInterface $entityManager, array $idArray, ?string $action = null): void
    {
        try {
            $entityManager->beginTransaction();

            $queryBuilder = $entityManager
                ->createQueryBuilder()
                ->where('t.id IN (:idArray)')
                ->setParameter('idArray', $idArray)
              ;

            $items = $queryBuilder->select('t')->from($this->entityClass, 't')->getQuery()->getResult();

            if (!empty($items)){
                foreach ($items as $item) {
                    if (($deletedItem = $entityManager->find($item->getEntityClassName(), $item->getItemId())) !== null && $deletedItem instanceof CrudEntitySafeDeleteInterface) {
                        if ($action == self::DELETE_STATUS) {
                            $entityManager->remove($deletedItem);
                        }

                        if ($action == self::BACKUP_STATUS) {
                            $deletedItem->setStatus(CrudEntitySafeDeleteInterface::STATUS_ACTIVE);
                        }
                    }
                }
                $entityManager->flush();
            }

            $queryBuilder->delete($this->entityClass, 't')
                ->where('t.id IN (:idArray)')
                ->setParameter('idArray', $idArray)
                ->getQuery()
                ->execute();

            $entityManager->commit();

        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }



}