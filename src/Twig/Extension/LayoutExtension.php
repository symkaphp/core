<?php
declare(strict_types=1);

namespace Symka\Core\Twig\Extension;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symka\Core\Entity\SiteDefaultConfigEntity;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LayoutExtension extends AbstractExtension
{
    private ?SiteDefaultConfigEntity $siteConfig;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->siteConfig = $entityManager
            ->getRepository(SiteDefaultConfigEntity::class)
            ->getConfigByDomain($requestStack->getCurrentRequest()->getHost());
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('symkaLayout', [$this, 'symkaLayout'])
        ];
    }

    public function symkaLayout(bool $isAdmin = false): string
    {
        if (empty($this->siteConfig)) {
            return  $isAdmin ? "default/admin" : "default";
        }
        return $isAdmin ? $this->siteConfig->getAdminTemplatePath() : $this->siteConfig->getTemplatePath();
    }
}