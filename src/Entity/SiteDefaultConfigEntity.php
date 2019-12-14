<?php


namespace Symka\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Symka\Core\Repository\SiteDefaultConfigEntityRepository")
 */

class SiteDefaultConfigEntity
{

    const STATUS_ACTIVE = 1;
    const STATUS_IN_DEVELOP = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Site name
     * @var string
     *
     * @ORM\Column(size="150", unique=true)
     */
    private $name;

    /**
     * Domain
     * @var string
     * @ORM\Column(size="150", unique=true)
     */
    private $domain;

    /**
     * Status  active|in develop
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;

    public function __construct()
    {
        $this->setStatus(self::STATUS_ACTIVE);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }



}