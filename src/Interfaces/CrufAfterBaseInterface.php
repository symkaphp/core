<?php


namespace Symka\Core\Interfaces;


interface CrufAfterBaseInterface
{
    public function __construct(CrudEntityInterface $entity);
    public function getFormData(): ?CrudEntityInterface;
}