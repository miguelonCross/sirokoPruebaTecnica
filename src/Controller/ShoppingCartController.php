<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ShoppingCartController extends AbstractController
{
    #[Route('/shoppingCart', name: 'app_shopping_cart')]
    public function index(): JsonResponse
    {
        return new JsonResponse('Must failed', 400);
    }
}
