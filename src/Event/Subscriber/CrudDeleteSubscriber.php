<?php


namespace Symka\Core\Event\Subscriber;


use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symka\Core\Entity\DeletedItemsBasketEntity;
use Symka\Core\Interfaces\CrudAfterDeleteSafeEventInterface;
use Symka\Core\Interfaces\CrudEntityInterface;
use Symka\Core\Interfaces\CrudMultyItemFormSuccessEventInterface;

class CrudDeleteSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            CrudAfterDeleteSafeEventInterface::NAME => 'saveInBasket',
            CrudMultyItemFormSuccessEventInterface::NAME => 'saveInBasketByIdArrays'
        ];

    }

    public function saveInBasket(CrudAfterDeleteSafeEventInterface $event)
    {
        $entity = $event->getFormData();
        try {
            $this->entityManager->beginTransaction();
            $this
                ->entityManager
                ->getRepository(DeletedItemsBasketEntity::class)
                ->deleteItemIfExists(get_class($entity), $entity->getId());

            $deletedItemsBasketEntity = new DeletedItemsBasketEntity();
            $deletedItemsBasketEntity->setCreatedAt(new \DateTime('now'));
            $deletedItemsBasketEntity->setEntityClassName(get_class($entity));
            $deletedItemsBasketEntity->setItemId($entity->getId());
            if ($entity instanceof CrudEntityInterface) {
                $deletedItemsBasketEntity->setTitle($entity->getBasketTitle());
            }

            $this->entityManager->persist($deletedItemsBasketEntity);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->logger->error('Error BasketDeletedItems ', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
            $this->entityManager->rollback();
        }

    }

    public function saveInBasketByIdArrays(CrudMultyItemFormSuccessEventInterface $event)
    {

        if (!empty($event->getIdArray())) {
            try {
                $this->entityManager->beginTransaction();
                $this
                    ->entityManager
                    ->getRepository(DeletedItemsBasketEntity::class)
                    ->deleteItemIfExistsByIdArray($event->getEntityClassName(), $event->getIdArray());

                $entityClassName = $event->getEntityClassName();
                $entity = new $entityClassName();

                foreach ($event->getIdArray() as $id) {
                    $deletedItemsBasketEntity = new DeletedItemsBasketEntity();
                    $deletedItemsBasketEntity->setCreatedAt(new \DateTime('now'));
                    $deletedItemsBasketEntity->setEntityClassName($event->getEntityClassName());
                    $deletedItemsBasketEntity->setItemId($id);

                    if ($entity instanceof CrudEntityInterface) {
                        $deletedItemsBasketEntity->setTitle($entity->getBasketTitle());
                    }
                    $this->entityManager->persist($deletedItemsBasketEntity);
                }

                $this->entityManager->flush();
                $this->entityManager->commit();

            } catch (\Exception $exception) {
                $this->logger->error('Error BasketDeletedItems ', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]);
                $this->entityManager->rollback();
            }
        }
    }
}