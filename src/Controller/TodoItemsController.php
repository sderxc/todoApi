<?php

namespace App\Controller;

use App\Repository\TodoItemRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class todoItemSiteController
 * @package App\Controller
 *
 * @Route(path="/todoitems")
 */
class TodoItemsController
{
    private $todoItemRepository;

    /**
     * TodoItemsController constructor.
     * @param TodoItemRepository $todoItemRepository
     */
    public function __construct(TodoItemRepository $todoItemRepository)
    {
        $this->todoItemRepository = $todoItemRepository;
    }

    /**
     * @param array $todoItemsArray
     * @return array
     */
    private function prepareResponse(array $todoItemsArray): array
    {
        $data = [];

        foreach ($todoItemsArray as $todoItem) {
            $data[] = $todoItem->jsonSerialize();
        }

        return $data;
    }

    /**
     * @Route("", name="add_item", methods={"POST"})
     */
    public function addTodoItem(Request $request): JsonResponse
    {
        $content = $request->get('content', '');

        $newTodoItem = $this->todoItemRepository->addNewItem($content);

        return new JsonResponse($newTodoItem, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="get_one_todoItem", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getTodoItem(int $id): JsonResponse
    {
        $todoItem = $this->todoItemRepository->getItem($id);

        return new JsonResponse($todoItem, Response::HTTP_OK);
    }

    /**
     * @Route("", name="get_not_completed_todoItems", methods={"GET"})
     */
    public function getNotCompleted(): JsonResponse
    {
        $todoItems = $this->todoItemRepository->findNotCompleted();

        return new JsonResponse($this->prepareResponse($todoItems), Response::HTTP_OK);
    }

    /**
     * @Route("/completed", name="get_completed_todoItems", methods={"GET"})
     */
    public function getCompleted(): JsonResponse
    {
        $todoItems = $this->todoItemRepository->finCompleted();

        return new JsonResponse($this->prepareResponse($todoItems), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_todoItem", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function markAsCompleted(int $id): JsonResponse
    {
        $todoItem = $this->todoItemRepository->getItem($id);

        $todoItem->setIsCompleted();
        $this->todoItemRepository->updateItem($todoItem);

        return new JsonResponse($todoItem, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete_todoItem", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function deleteTodoItem(int $id): JsonResponse
    {
        $todoItem = $this->todoItemRepository->getItem($id);

        $this->todoItemRepository->removeItem($todoItem);

        return new JsonResponse(['status' => 'TODO item deleted'], Response::HTTP_NO_CONTENT);
    }
}
