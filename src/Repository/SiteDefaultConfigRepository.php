<?php

namespace Symka\Core\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symka\Core\Entity\SiteDefaultConfigEntity;

class SiteDefaultConfigRepository extends EntityRepository
{

    public function getQueryDefaultOptions(?string $domain = null) : ?SiteDefaultConfigEntity
    {

    }
}