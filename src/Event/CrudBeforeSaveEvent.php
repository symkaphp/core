<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symka\Core\Interfaces\CrudBeforeSaveEventInterface;

class CrudBeforeSaveEvent extends CrudBeforeDataEventAbstract implements CrudBeforeSaveEventInterface
{

}