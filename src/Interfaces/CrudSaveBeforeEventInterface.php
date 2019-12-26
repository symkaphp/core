<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


interface CrudSaveBeforeEventInterface
{
    public const NAME = 'crud.save.before.event';
    public function __construct(CrudEntityInterface $entity);

    public function getFormData(): ?CrudEntityInterface;
}