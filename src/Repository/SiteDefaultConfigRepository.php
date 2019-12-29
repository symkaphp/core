<?php

namespace Symka\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symka\Core\Entity\SiteDefaultConfigEntity;
use Twig\Extension\GlobalsInterface;

class SiteDefaultConfigRepository extends EntityRepository implements GlobalsInterface
{
    public function getConfigByDomain(string $domain): ?SiteDefaultConfigEntity
    {
        return $this->getByDomainQueryBuilder($domain)->getQuery()->getOneOrNullResult();
    }

    public function getGlobals(): array
    {
        return [
            'one' => 'one11'
        ];
    }

    public function getByDomainQueryBuilder(string $domain): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.domain=:domain');
        $qb->setParameter('domain',$domain);
        return $qb;
    }
}