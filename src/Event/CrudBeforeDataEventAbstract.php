<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symka\Core\Interfaces\CrudEntityInterface;

abstract class CrudBeforeDataEventAbstract extends Event
{
    protected CrudEntityInterface $entity;
    protected ?int $id = null;

    public function __construct(CrudEntityInterface $entity, ?int $id = null)
    {
        $this->entity = $entity;
        $this->id = $id;
    }

    public function getFormData(): ?CrudEntityInterface
    {
        return $this->entity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}