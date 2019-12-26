<?php


namespace Symka\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Symka\Core\Repository\SiteDefaultConfigEntityRepository")
 * @ORM\Table(name="SiteDefaultConfigEntity")
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
     * @ORM\Column(length=65, unique=true)
     */
    private $name;

    /**
     * Domain
     * @var string
     * @ORM\Column(length=65, unique=true)
     */
    private $domain;

    /**
     * Status  active|in develop
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * Date time of create
     * @var \DateTime
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private $createdAt;

    /**
     * Date time of update
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updatedAt")
     */
    private $updatedAt;

    /**
     * Date time of delete
     * @var \DateTime
     * @ORM\Column(type="datetime", name="deletedAt")
     */
    private $deletedAt;

    /**
     * User id
     * @var int
     * @ORM\Column(type="integer", name="authorId")
     */
    private $authorId;

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
    public function setDeletedAt(?\DateTime $deletedAt): SiteDefaultConfigEntity
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


}