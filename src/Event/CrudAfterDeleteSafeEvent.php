<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symka\Core\Interfaces\CrudAfterDeleteSafeEventInterface;

class CrudAfterDeleteSafeEvent extends CrudAfterDataEventAbstract implements CrudAfterDeleteSafeEventInterface
{

}