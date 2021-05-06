<?php

namespace App\Service;

use App\Entity\TodoItem;
use App\Exception\WrongTodoItemIdException;

class TodoItemService
{
    private TodoItemStorageInterface $storage;

    public function __construct(TodoItemStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param int $id
     * @return TodoItem
     */
    public function getItem(int $id): TodoItem
    {
        $todoItem = $this->storage->getItemById($id);

        if (!$todoItem) {
            throw new WrongTodoItemIdException(sprintf('ToDo item #%d not found', $id));
        }

        return $todoItem;
    }

    /**
     * @param string $content
     * @return TodoItem
     */
    public function addNewItem(string $content): TodoItem
    {
        return $this->transaction(function() use ($content){
            $newTodoItem = new TodoItem($content);
            $this->storage->addItem($newTodoItem);
            return $newTodoItem;
        });
    }

    public function markAsCompleted(int $id): TodoItem
    {
        return $this->transaction(function() use ($id){
            $todoItem = $this->getItem($id);
            $todoItem->setIsCompleted();
            $this->storage->updateItem($todoItem);
            return $todoItem;
        });
    }

    /**
     * @param int $id
     */
    public function removeItem(int $id): void
    {
        $this->transaction(function() use ($id){
            $todoItem = $this->getItem($id);
            $this->storage->deleteItem($todoItem);
        });
    }

    /**
     * @return TodoItem[]
     */
    public function findNotCompleted(): array
    {
        return $this->storage->getItemsByParams(['isCompleted' => TodoItem::NOT_COMPLETED]);
    }

    /**
     * @return TodoItem[]
     */
    public function finCompleted(): array
    {
        return $this->storage->getItemsByParams(['isCompleted' => TodoItem::IS_COMPLETED]);
    }

    public function todoItemToArray(TodoItem $todoItem): array
    {
        return [
            'id' => $todoItem->getId(),
            'content' => $todoItem->getContent(),
            'isCompleted' => $todoItem->getIsCompleted(),
        ];
    }

    protected function transaction(callable $function): ?TodoItem
    {
        $this->storage->startTransaction();
        try {
            $result = $function();
            $this->storage->commit();
            return $result;
        } catch (\Exception $e) {
            $this->storage->rollBackTransaction();
            throw $e;
        }
    }
}
