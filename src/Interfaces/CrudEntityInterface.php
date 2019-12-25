<?php


namespace Symka\Core\Interfaces;


use Doctrine\ORM\EntityManagerInterface;

interface CrudEntityInterface
{
    public function getEntity(EntityManagerInterface $entityManager, ?int $id): CrudEntityInterface;
    public function getClassName(): string;
}