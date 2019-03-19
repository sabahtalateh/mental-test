<?php

namespace App\Entity;

use App\Serialization\Group;
use Carbon\CarbonImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="todo_item")
 * @ORM\HasLifecycleCallbacks
 */
class TodoItem
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @JMS\Expose
     * @JMS\Groups({Group::USER})
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", name="title", nullable=false, length=255)
     *
     * @JMS\Expose
     * @JMS\Groups({Group::USER})
     */
    protected $title;

    /**
     * @var string|null
     * @ORM\Column(type="text", name="description", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Groups({Group::USER})
     */
    protected $description;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     *
     * @JMS\Expose
     * @JMS\Type("DateTime")
     * @JMS\Groups({Group::USER})
     */
    protected $createdAt;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     *
     * @JMS\Expose
     * @JMS\Type("DateTime")
     * @JMS\Groups({Group::USER})
     */
    protected $updatedAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="done", nullable=false, options={"default"=false})
     *
     * @JMS\Expose
     * @JMS\Groups({Group::USER})
     */
    protected $done = false;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return CarbonImmutable|null
     */
    public function getCreatedAt(): ?CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param CarbonImmutable|null $createdAt
     */
    public function setCreatedAt(?CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return CarbonImmutable|null
     */
    public function getUpdatedAt(): ?CarbonImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param CarbonImmutable|null $updatedAt
     */
    public function setUpdatedAt(?CarbonImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * @param bool $done
     */
    public function setDone(bool $done): void
    {
        $this->done = $done;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @ORM\PrePersist
     * @throws \Exception
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    /**
     * @ORM\PreUpdate
     * @throws \Exception
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime;
    }
}