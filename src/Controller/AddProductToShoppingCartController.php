<?php

namespace App\Controller;

use App\dto\AddProductToShoppingCartRequest;
use App\utils\ShoppingCartUtils;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;

final class AddProductToShoppingCartController extends AbstractController
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
    )
    {
    }

    #[Route('/shoppingCart/add', name: 'add_product_to_shopping_cart', methods: ['POST'])]
    public function execute(#[MapRequestPayload] AddProductToShoppingCartRequest $request): JsonResponse
    {
        try {
            $clientUUID = $request->client_uuid;
            ShoppingCartUtils::addOrUpdateItem($clientUUID, $request->product, $this->cacheItemPool);
            $cart = $this->cacheItemPool->getItem($clientUUID)->get();
            return new JsonResponse($cart);
        }catch (Exception | \TypeError | ValidationFailedException | ValidatorException | HttpException $exception)
        {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }
}
