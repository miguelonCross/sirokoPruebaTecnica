<?php

declare(strict_types=1);

namespace App\Controller\DeleteShoppingCartController;

use App\utils\ShoppingCartUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\CacheInterface;

class DeleteShoppingCartController extends AbstractController
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    #[Route('/shoppingCart', name: 'app_shopping_cart_delete', methods: ['DELETE'])]
    public function execute(#[MapRequestPayload] DeleteShoppingCartControllerRequest $request): JsonResponse
    {
        try {
            ShoppingCartUtils::deleteCart(new Uuid($request->client_uuid), $this->cache);

            return new JsonResponse();
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }
    }
}
