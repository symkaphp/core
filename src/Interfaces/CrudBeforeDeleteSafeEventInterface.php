<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;


interface CrudBeforeDeleteSafeEventInterface extends CrufBeforBaseInterface
{
    public const NAME = 'crud.before.delete.safe.event';
}