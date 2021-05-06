<?php

namespace App\Controller;

use App\Entity\TodoItem;
use App\Service\TodoItemService;
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
    private $todoItemService;

    /**
     * TodoItemsController constructor.
     * @param TodoItemService $todoItemService
     */
    public function __construct(TodoItemService $todoItemService)
    {
        $this->todoItemService = $todoItemService;
    }

    /**
     * @Route("", name="add_item", methods={"POST"})
     */
    public function addTodoItem(Request $request): JsonResponse
    {
        $content = $request->get('content', '');

        $newTodoItem = $this->todoItemService->addNewItem($content);

        return $this->prepareResponse($newTodoItem, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="get_one_todoItem", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function getTodoItem(int $id): JsonResponse
    {
        $todoItem = $this->todoItemService->getItem($id);

        return $this->prepareResponse($todoItem);
    }

    /**
     * @Route("", name="get_not_completed_todoItems", methods={"GET"})
     */
    public function getNotCompleted(): JsonResponse
    {
        $todoItems = $this->todoItemService->findNotCompleted();

        return $this->prepareResponse($todoItems);
    }

    /**
     * @Route("/completed", name="get_completed_todoItems", methods={"GET"})
     */
    public function getCompleted(): JsonResponse
    {
        $todoItems = $this->todoItemService->finCompleted();

        return $this->prepareResponse($todoItems);
    }

    /**
     * @Route("/{id}", name="update_todoItem", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function markAsCompleted(int $id): JsonResponse
    {
        $todoItem = $this->todoItemService->markAsCompleted($id);

        return $this->prepareResponse($todoItem);
    }

    /**
     * @Route("/{id}", name="delete_todoItem", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function deleteTodoItem(int $id): JsonResponse
    {
        $this->todoItemService->removeItem($id);

        return new JsonResponse(['status' => 'TODO item deleted'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param TodoItem[]|TodoItem $todoItem
     * @return array
     */
    private function prepareResponse($data, $status = Response::HTTP_OK): JsonResponse
    {
        $_data = [];

        if (is_array($data)) {
            foreach ($data as $todoItem) {
                $_data[] = $this->todoItemService->todoItemToArray($todoItem);
            }
        } else {
            $_data = $this->todoItemService->todoItemToArray($data);
        }

        return new JsonResponse($_data, $status);
    }
}
