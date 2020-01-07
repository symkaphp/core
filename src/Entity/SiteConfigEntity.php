<?php


namespace Symka\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symka\Core\Interfaces\CrudEntityInterface;
use Symka\Core\Interfaces\CrudEntitySafeDeleteInterface;

/**
 * @ORM\Entity(repositoryClass="Symka\Core\Repository\SiteConfigEntityRepository")
 * @ORM\Table(name="SiteConfigEntity")
 */

class SiteConfigEntity implements CrudEntitySafeDeleteInterface
{
    const STATUS_IN_DEVELOP = 2;
    const STATUS_CLOSE = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * Site name
     * @var string
     *
     * @ORM\Column(length=65, unique=true)
     */
    private ?string $name = null;

    /**
     * Domain
     * @var string
     * @ORM\Column(length=65, unique=true)
     */
    private ?string $domain = null;

    /**
     * Status  active|in develop
     * @var int
     * @ORM\Column(type="smallint")
     */
    private ?int $status = null;

    /**
     * Date time of create
     * @var \DateTime
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private ?\DateTime $createdAt = null;

    /**
     * Date time of update
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updatedAt", nullable=true)
     */
    private $updatedAt = null;

    /**
     * Date time of delete
     * @var \DateTime
     * @ORM\Column(type="datetime", name="deletedAt", nullable=true)
     */
    private ?\DateTime $deletedAt = null;

    /**
     * User id
     * @var int
     * @ORM\Column(type="integer", name="authorId", nullable=true)
     */
    private ?int $authorId = null;

    /**
     * Frontend template path
     * @var string|null
     * @ORM\Column(type="string", length=150, name="templatePath")
     */
    private ?string $templatePath = null;

    /**
     * Frontend template path
     * @var string|null
     * @ORM\Column(type="string", length=150, name="adminTemplatePath")
     */
    private ?string $adminTemplatePath = null;

    public function __construct()
    {

        $this->setStatus(self::STATUS_ACTIVE);
        $this->setTemplatePath('default');
        $this->setAdminTemplatePath('default/admin');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setStatus(?int $status): ?CrudEntityInterface
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return SiteConfigEntity
     */
    public function setCreatedAt(?\DateTime $createdAt): SiteConfigEntity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return SiteConfigEntity
     */
    public function setUpdatedAt(?\DateTime $updatedAt): SiteConfigEntity
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     * @return SiteConfigEntity
     */
    public function setDeletedAt(?\DateTime $deletedAt): ?CrudEntityInterface
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     * @return SiteConfigEntity
     */
    public function setAuthorId(?int $authorId): SiteConfigEntity
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    /**
     * @param string|null $templatePath
     * @return SiteConfigEntity
     */
    public function setTemplatePath(?string $templatePath): SiteConfigEntity
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdminTemplatePath(): ?string
    {
        return $this->adminTemplatePath;
    }

    /**
     * @param string|null $adminTemplatePath
     * @return SiteConfigEntity
     */
    public function setAdminTemplatePath(?string $adminTemplatePath): SiteConfigEntity
    {
        $this->adminTemplatePath = $adminTemplatePath;
        return $this;
    }

    public function getBasketTitle(): string
    {
        return 'Config Site';
    }

    public function getBasketItemTitle(): string
    {
        return $this->getName();
    }


}