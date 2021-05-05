<?php

namespace App\Repository;

use App\Entity\TodoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method TodoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoItem[]    findAll()
 * @method TodoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoItemRepository extends ServiceEntityRepository
{

    private $manager;

    /**
     * TodoItemRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, TodoItem::class);
        $this->manager = $manager;
    }

    /**
     * @param int $id
     * @return TodoItem
     */
    public function getItem(int $id): TodoItem
    {
        $todoItem = $this->findOneBy(['id' => $id]);

        if (!$todoItem) {
            throw new NotFoundHttpException(sprintf('ToDo Item %s not found', $id));
        }

        return $todoItem;
    }

    /**
     * @param string $content
     * @return TodoItem
     */
    public function addNewItem(string $content): TodoItem
    {
        $newTodoItem = new TodoItem($content);

        $this->manager->persist($newTodoItem);
        $this->manager->flush();

        return $newTodoItem;
    }

    /**
     * @param TodoItem $todoItem
     */
    public function updateItem(TodoItem $todoItem): void
    {
        $this->manager->persist($todoItem);
        $this->manager->flush();
    }

    /**
     * @param TodoItem $todoItem
     */
    public function removeItem(TodoItem $todoItem): void
    {
        $this->manager->remove($todoItem);
        $this->manager->flush();
    }

    /**
     * @return TodoItem[]
     */
    public function findNotCompleted(): array
    {
        return $this->findBy(['isCompleted' => TodoItem::NOT_COMPLETED]);
    }

    /**
     * @return TodoItem[]
     */
    public function finCompleted(): array
    {
        return $this->findBy(['isCompleted' => TodoItem::IS_COMPLETED]);
    }
}
