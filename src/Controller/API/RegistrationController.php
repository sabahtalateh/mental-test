<?php

namespace App\Controller\API;

use App\Form\Type\RegistrationType;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class RegistrationController extends ApiController
{
    /**
     * @Route("/register", methods={"POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return JsonResponse
     */
    public function register(Request $request, UserManager $userManager): JsonResponse
    {
        $form = $this->createForm(RegistrationType::class);
        $body = $this->jsonBodyToArray($request);

        $form->submit($body);

        if ($form->isValid()) {
            $userManager->register($form->getData());
            return new JsonResponse(['messages' => ['Successfully Registered.']]);
        } else {
            return $this->jsonResponseFromFormErrors($form);
        }
    }
}