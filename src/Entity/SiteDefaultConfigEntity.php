<?php


namespace Symka\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symka\Core\Interfaces\CrudEntityInterface;

/**
 * @ORM\Entity(repositoryClass="Symka\Core\Repository\SiteDefaultConfigRepository")
 * @ORM\Table(name="SiteDefaultConfig")
 */

class SiteDefaultConfigEntity implements CrudEntityInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_IN_DEVELOP = 2;
    const STATUS_CLOSE = 3;
    const STATUS_DELETED = 4;

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

    public function setStatus(?int $status): self
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
     * @return SiteDefaultConfigEntity
     */
    public function setCreatedAt(?\DateTime $createdAt): SiteDefaultConfigEntity
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
     * @return SiteDefaultConfigEntity
     */
    public function setUpdatedAt(?\DateTime $updatedAt): SiteDefaultConfigEntity
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
     * @return SiteDefaultConfigEntity
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
     * @return SiteDefaultConfigEntity
     */
    public function setAuthorId(?int $authorId): SiteDefaultConfigEntity
    {
        $this->authorId = $authorId;
        return $this;
    }

    public function getStatusDeleted(): int
    {
        return self::STATUS_DELETED;
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
     * @return SiteDefaultConfigEntity
     */
    public function setTemplatePath(?string $templatePath): SiteDefaultConfigEntity
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
     * @return SiteDefaultConfigEntity
     */
    public function setAdminTemplatePath(?string $adminTemplatePath): SiteDefaultConfigEntity
    {
        $this->adminTemplatePath = $adminTemplatePath;
        return $this;
    }

    public function getBasketTitle(): string
    {
        return 'Config Site';
    }


}