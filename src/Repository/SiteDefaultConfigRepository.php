<?php
declare(strict_types=1);

namespace Symka\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symka\Core\Entity\SiteDefaultConfigEntity;
use Twig\Extension\GlobalsInterface;

class SiteDefaultConfigRepository extends EntityRepository
{
    public function getConfigByDomain(string $domain): ?SiteDefaultConfigEntity
    {
        return $this->getByDomainQueryBuilder($domain)->getQuery()->getOneOrNullResult();
    }

    public function getByDomainQueryBuilder(string $domain): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.domain=:domain');
        $qb->setParameter('domain',$domain);
        return $qb;
    }
}