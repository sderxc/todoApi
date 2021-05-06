<?php

namespace App\Repository;

use App\Entity\TodoItem;
use App\Service\TodoItemStorageInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method TodoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoItem[]    findAll()
 * @method TodoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoItemRepository extends ServiceEntityRepository implements TodoItemStorageInterface
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

    public function startTransaction(): void
    {
        $this->manager->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->manager->getConnection()->commit();
    }

    public function rollBackTransaction(): void
    {
        $this->manager->getConnection()->rollBack();
    }

    public function addItem($item): void
    {
        $this->manager->persist($item);
        $this->manager->flush();
    }

    public function getItemById(int $id): ?TodoItem
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getItemsByParams(array $params): array
    {
        return $this->findBy($params);
    }

    public function updateItem($item): void
    {
        $this->addItem($item);
    }

    public function deleteItem($item): void
    {
        $this->manager->remove($item);
        $this->manager->flush();
    }
}
