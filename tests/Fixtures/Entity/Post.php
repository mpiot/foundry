<?php

namespace Zenstruck\Foundry\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Zenstruck\Foundry\Tests\Fixtures\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewCount = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn
     */
    private $category;

    public function __construct(string $title, string $body, string $shortDescription = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->shortDescription = $shortDescription;
        $this->createdAt = new \DateTime('now');
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function increaseViewCount(int $amount = 1): void
    {
        $this->viewCount += $amount;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function isPublished(): bool
    {
        return null !== $this->publishedAt;
    }
}