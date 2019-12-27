<?php

namespace Symka\Core\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symka\Core\Entity\SiteDefaultConfigEntity;

class SiteDefaultConfigEntityRepository extends ServiceEntityRepository
{
    public function getQueryDefaultOptions(?string $domain = null) : ?SiteDefaultConfigEntity
    {

    }
}