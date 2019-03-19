<?php

namespace App\Controller\API;

use App\Controller\Pagination\PaginatedCollection;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ApiController constructor.
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
    public function jsonResponseFromFormErrors(FormInterface $form)
    {
        return new JsonResponse(['errors' => $this->formErrorsToPlainArray($form)], 400);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function formErrorsToPlainArray(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function jsonBodyToArray(Request $request): ?array
    {
        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return null;
        }
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }
        return $data;
    }

    /**
     * @param $object
     * @param array $groups JMS Serialization groups
     * @return string
     */
    public function toJson($object, array $groups): string
    {
        $context = SerializationContext::create()->setSerializeNull(true)->setGroups($groups);

        return $this->serializer->serialize($object, 'json', $context);
    }

    /**
     * @see Group
     *
     * @param $object
     * @param array $groups
     * @param int $code
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    public function jsonResponse($object, array $groups, int $code = 200, array $headers = [], bool $json = true): JsonResponse
    {
        $serialized = $this->toJson($object, $groups);
        return new JsonResponse($serialized, $code, $headers, $json);
    }

    /**
     * @param PaginatedCollection $collection
     * @param array $groups
     * @param int $code
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    public function paginatedJsonResponse(
        PaginatedCollection $collection,
        array $groups,
        int $code = 200,
        array $headers = [],
        bool $json = true
    ): JsonResponse
    {
        $body = [
            'list' => $collection->getObjects(),
            'pagination' => [
                'offset' => $collection->getOffset(),
                'limit' => $collection->getLimit(),
                'total' => $collection->getTotal(),
            ]
        ];

        if ($collection->hasAdditionalData()) {
            $body = array_merge($body, $collection->getAdditionalData());
        }

        $serialized = $this->toJson($body, $groups);
        return new JsonResponse($serialized, $code, $headers, $json);
    }
}