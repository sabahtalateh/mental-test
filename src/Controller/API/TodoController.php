<?php

namespace App\Controller\API;

use App\Controller\Pagination\PaginatedCollection;
use App\Controller\Pagination\PaginationParams;
use App\Entity\TodoItem;
use App\Form\Type\TodoItemType;
use App\Manager\TodoManager;
use App\Serialization\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class TodoController extends ApiController
{
    /**
     * @Route("/todo", methods={"POST"})
     * @param Request $request
     * @param TodoManager $todoManager
     * @return JsonResponse
     */
    public function postTodo(Request $request, TodoManager $todoManager): JsonResponse
    {
        $form = $this->createForm(TodoItemType::class);
        $body = $this->jsonBodyToArray($request);

        $form->submit($body);

        if ($form->isValid()) {
            $todoItem = $form->getData();
            $todoItem->setUser($this->getUser());
            $todoItem = $todoManager->create($todoItem);
            return $this->jsonResponse($todoItem, [Group::USER]);
        } else {
            return $this->jsonResponseFromFormErrors($form);
        }
    }

    /**
     * @Route("/todo", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function myTodos(Request $request)
    {
        $user = $this->getUser();

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')
            ->from(TodoItem::class, 't')
            ->where('t.user = :user')
            ->setParameter('user', $user);

        $paginatedCollection = PaginatedCollection::fromQueryBuilder(
            $qb,
            PaginationParams::fromRequest($request)
        );

        return $this->paginatedJsonResponse($paginatedCollection, [Group::USER]);
    }

    /**
     * @Route("/todo/{todoId}", methods={"POST"})
     * @param int $todoId
     * @param TodoManager $todoManager
     * @return JsonResponse
     */
    public function done(int $todoId, TodoManager $todoManager): JsonResponse
    {
        $todo = $this->getDoctrine()
            ->getRepository(TodoItem::class)
            ->findOneBy(['id' => $todoId, 'user' => $this->getUser()]);

        if (!$todo) {
            return new JsonResponse("Not Found", Response::HTTP_NOT_FOUND);
        }

        $todo = $todoManager->done($todo);

        return $this->jsonResponse($todo, [Group::USER]);
    }
}