<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symka\Core\Interfaces\CrudAfterSaveEventInterface;

class CrudAfterSaveEvent extends CrudAfterDataEventAbstract implements CrudAfterSaveEventInterface
{

}