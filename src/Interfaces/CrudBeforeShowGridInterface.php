<?php


namespace Symka\Core\Interfaces;

use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\QueryBuilder;

interface CrudBeforeShowGridInterface
{
    public const NAME = 'crud.before.show.grid';
    public function __construct(QueryBuilder $queryBuilder, PaginatorInterface $paginator, ?int $page = null);
}