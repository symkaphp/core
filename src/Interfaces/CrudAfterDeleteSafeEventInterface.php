<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


interface CrudAfterDeleteSafeEventInterface extends CrufAfterBaseInterface
{
    public const NAME = 'crud.after.delete.safe.event';
}