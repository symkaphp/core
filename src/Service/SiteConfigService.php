<?php


namespace Symka\Core\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symka\Core\Entity\SiteDefaultConfigEntity;

class SiteConfigService
{
    private ?SiteDefaultConfigEntity $siteConfig;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->siteConfig = $entityManager
            ->getRepository(SiteDefaultConfigEntity::class)
            ->getConfigByDomain($requestStack->getCurrentRequest()->getHost());
    }

    public function get(): ?SiteDefaultConfigEntity
    {

    }

}