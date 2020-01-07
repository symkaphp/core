<?php


namespace Symka\Core\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symka\Core\Entity\SiteConfigEntity;

class SiteConfigService
{
    private ?SiteConfigEntity $siteConfig;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->siteConfig = $entityManager
            ->getRepository(SiteConfigEntity::class)
            ->getConfigByDomain($requestStack->getCurrentRequest()->getHost());
    }

    public function get(): ?SiteConfigEntity
    {

    }

}