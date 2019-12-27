<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symka\Core\Interfaces\CrudErrorDeleteSafeEventInterface;

class CrudErrorDeleteSafeEvent extends CrudErrorDataEventAbstract implements CrudErrorDeleteSafeEventInterface
{

}