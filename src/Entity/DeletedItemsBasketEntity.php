<?php


namespace Symka\Core\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Symka\Core\Repository\DeletedItemsBasketRepository")
 * @ORM\Table(name="DeletedItemsBasket")
 */

class DeletedItemsBasketEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * Date time of create
     * @var \DateTime
     * @ORM\Column(type="datetime", name="createdAt")
     */
    private ?\DateTime $createdAt = null;

    /**
     * className template path
     * @var string|null
     * @ORM\Column(type="string", length=250, name="entityClassName")
     */
    private ?string $entityClassName = null;

    /**
     * Title
     * @var string|null
     * @ORM\Column(type="string", length=50, name="title", nullable=true)
     */
    private ?string $title = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", name="itemId")
     */
    private ?int $itemId = null;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return DeletedItemsBasketEntity
     */
    public function setCreatedAt(?\DateTime $createdAt): DeletedItemsBasketEntity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityClassName(): ?string
    {
        return $this->entityClassName;
    }

    /**
     * @param string|null $entityClassName
     * @return DeletedItemsBasketEntity
     */
    public function setEntityClassName(?string $entityClassName): DeletedItemsBasketEntity
    {
        $this->entityClassName = $entityClassName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return DeletedItemsBasketEntity
     */
    public function setTitle(?string $title): DeletedItemsBasketEntity
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    /**
     * @param int|null $itemId
     * @return DeletedItemsBasketEntity
     */
    public function setItemId(?int $itemId): DeletedItemsBasketEntity
    {
        $this->itemId = $itemId;
        return $this;
    }
}