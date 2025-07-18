<?php

declare(strict_types=1);

namespace App\Controller\ShoppingCartController;

use App\dto\ShoppingCartDTO;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUID;
use App\UseCase\GetShoppingCartByClientUUID\GetShoppingCartByClientUUIDRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class ShoppingCartController extends AbstractController
{
    public function __construct(
        private GetShoppingCartByClientUUID $getShoppingCartByClientUUID,
    ) {
    }

    #[Route('/shoppingCart', name: 'app_shopping_cart', methods: ['POST'])]
    public function execute(#[MapRequestPayload] ShoppingCartControllerRequest $request): JsonResponse
    {
        try {
            $shoppingCart = $this->getShoppingCartByClientUUID->execute(new GetShoppingCartByClientUUIDRequest(new Uuid($request->client_uuid)))->shoppingCart;

            return new JsonResponse((new ShoppingCartDTO($shoppingCart->uuid->toRfc4122(), $shoppingCart->shoppingCartItem))->toArray());
        }catch (\Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }
    }
}
