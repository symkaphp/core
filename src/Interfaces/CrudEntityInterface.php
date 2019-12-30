<?php


namespace Symka\Core\Interfaces;


use Doctrine\ORM\EntityManagerInterface;
use Symka\Core\Entity\SiteDefaultConfigEntity;

interface CrudEntityInterface
{
    public function getId(): ?int;

    public function getStatusDeleted(): int;

    public function getDeletedAt(): ?\DateTime;

    public function setDeletedAt(?\DateTime $deletedAt): ?CrudEntityInterface;

    public function getStatus(): ?int;

    public function getBasketTitle(): string;

}