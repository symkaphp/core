<?php
declare(strict_types=1);

namespace Symka\Core\Repository;


use Doctrine\ORM\EntityRepository;

class DeletedItemsBasketRepository extends EntityRepository
{
    public function deleteItemIfExists(string $entityClassName, int $itemId): void
    {
        try {
            $this->createQueryBuilder('t')
                ->delete()
                ->where("t.entityClassName=:entityClassName")
                ->andWhere("t.itemId=:itemId")
                ->setParameter("entityClassName", $entityClassName)
                ->setParameter("itemId", $itemId)
                ->getQuery()
                ->execute();
        } catch (\Exception $exception) {
            throw $exception;
        }
        return;
    }

    public function deleteItemIfExistsByIdArray(string $entityClassName, array $itemIdArray): void
    {
        try {
            $this->createQueryBuilder('t')
                ->delete()
                ->where("t.entityClassName=:entityClassName")
                ->andWhere("t.itemId IN (:itemId)")
                ->setParameter("entityClassName", $entityClassName)
                ->setParameter("itemId", $itemIdArray)
                ->getQuery()
                ->execute();
        } catch (\Exception $exception) {
            throw $exception;
        }
        return;
    }
}