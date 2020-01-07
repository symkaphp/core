<?php


namespace Symka\Core\Interfaces;


interface CrudEntitySafeDeleteInterface extends CrudEntityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 4;

    public function getDeletedAt(): ?\DateTime;

    public function setDeletedAt(?\DateTime $deletedAt): ?CrudEntityInterface;

    public function getStatus(): ?int;

    public function setStatus(?int $status): ?CrudEntityInterface;

    public function getBasketTitle(): string;

    public function getBasketItemTitle(): string;
}