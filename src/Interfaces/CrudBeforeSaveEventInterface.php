<?php
declare(strict_types=1);

namespace Symka\Core\Interfaces;

interface CrudBeforeSaveEventInterface extends CrufBeforBaseInterface
{
    public const NAME = 'crud.before.save.event';
}