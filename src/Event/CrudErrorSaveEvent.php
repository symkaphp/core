<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Symka\Core\Interfaces\CrudErrorSaveInterface;

class CrudErrorSaveEvent extends CrudErrorDataEventAbstract implements CrudErrorSaveInterface
{

}