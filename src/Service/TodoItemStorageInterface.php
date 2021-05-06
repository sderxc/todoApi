<?php

namespace App\Service;

use App\Entity\TodoItem;

interface TodoItemStorageInterface
{
    public function addItem(TodoItem $item): void;

    public function getItemById(int $id): ?TodoItem;

    public function getItemsByParams(array $params): array;

    public function updateItem(TodoItem $item): void;

    public function deleteItem(TodoItem $item): void;

    public function startTransaction(): void;

    public function commit(): void;
    
    public function rollBackTransaction(): void;
}
