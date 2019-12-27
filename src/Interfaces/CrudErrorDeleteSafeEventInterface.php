<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


interface CrudErrorDeleteSafeEventInterface extends CrudErrorBaseInterface
{
    public const NAME = 'crud.error.delete.safe.event';
}