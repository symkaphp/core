<?php


namespace Symka\Core\Interfaces;

use Doctrine\ORM\Query;

interface CrudGridInterface
{
    public function getGridQuery(): Query;
}