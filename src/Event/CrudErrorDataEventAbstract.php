<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symka\Core\Interfaces\CrudEntityInterface;

abstract class CrudErrorDataEventAbstract extends Event
{
    protected ?CrudEntityInterface $entity;
    protected ?int $id = null;
    protected \Exception $exception;

    public function __construct(?CrudEntityInterface $entity, \Exception $exception, ?int $id = null)
    {
        $this->entity = $entity;
        $this->id = $id;
        $this->exception = $exception;
    }

    public function getFormData(): ?CrudEntityInterface
    {
        return $this->entity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getException(): \Exception
    {
        return $this->exception;
    }
}