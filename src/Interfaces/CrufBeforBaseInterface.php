<?php


namespace Symka\Core\Interfaces;


interface CrufBeforBaseInterface
{
    public function __construct(CrudEntityInterface $entity, ?int $id = null);

    public function getFormData(): ?CrudEntityInterface;
    public function getId(): ?int;
}