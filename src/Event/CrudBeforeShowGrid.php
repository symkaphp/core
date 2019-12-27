<?php
declare(strict_types=1);

namespace Symka\Core\Event;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symka\Core\Interfaces\CrudBeforeShowGridInterface;



class CrudBeforeShowGrid extends Event implements CrudBeforeShowGridInterface
{
    private QueryBuilder $queryBuilder;
    private PaginatorInterface $paginator;
    private ?int $page;

    public function __construct(QueryBuilder $queryBuilder, PaginatorInterface $paginator, ?int $page = null)
    {
        $this->queryBuilder = $queryBuilder;
        $this->paginator = $paginator;
        $this->page = $page;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

}