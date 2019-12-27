<?php


namespace Symka\Core\Interfaces;

interface CrudErrorBaseInterface
{

    public function __construct(CrudEntityInterface $entity, \Exception $exception, ?int $id = null);
    public function getFormData(): ?CrudEntityInterface;
    public function getException(): \Exception;
    public function getId(): ?int;
}