<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symka\Core\Interfaces\CrudEntityInterface;

abstract class CrudAfterDataEventAbstract extends Event
{
    protected CrudEntityInterface $entity;

    public function __construct(CrudEntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public function getFormData(): ?CrudEntityInterface
    {
        return $this->entity;
    }
}