<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Exception\TodoItemContentValidationException;

/**
 * @ORM\Entity(repositoryClass=TodoItemRepository::class)
 * @ORM\Table(name="todo_item",indexes={@ORM\Index(name="is_completed", columns={"is_completed"})})
 */
class TodoItem
{
    public const NOT_COMPLETED = 0;
    public const IS_COMPLETED = 1;
    public const MAX_CONTENT_LEN = 1000;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private string $content;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $isCompleted = self::NOT_COMPLETED;

    public function __construct(string $content)
    {
        $this->validateContent($content);
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return void
     */
    private function validateContent(string $content): void
    {
        if (empty($content)) {
            throw new TodoItemContentValidationException('The `content` field is empty.');
        }

        if (strlen($content) > self::MAX_CONTENT_LEN) {
            throw new TodoItemContentValidationException(sprintf('The `content` field length is too long. Max length is %d', self::MAX_CONTENT_LEN));
        }
    }

    /**
     * @return int
     */
    public function getIsCompleted(): int
    {
        return (int) $this->isCompleted;
    }

    public function setIsCompleted(): void
    {
        $this->isCompleted = self::IS_COMPLETED;
    }
}
