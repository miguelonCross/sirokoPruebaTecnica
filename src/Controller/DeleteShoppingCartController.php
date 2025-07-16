<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class DeleteShoppingCartController extends AbstractController
{
    public function execute(#[MapRequestPayload] DeleteShoppingCartRequest $request): JsonResponse
    {

    }
}
