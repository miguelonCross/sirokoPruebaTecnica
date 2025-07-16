<?php

namespace App\Controller;

use App\dto\ShoppingCartRequest;
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
use Symfony\Contracts\Cache\CacheInterface;

final class ShoppingCartController extends AbstractController
{
    public function __construct(
        private CacheInterface $cache,
    )
    {
    }

    #[Route('/shoppingCart', name: 'app_shopping_cart', methods: ['POST'])]
    public function execute(#[MapRequestPayload] ShoppingCartRequest $request): JsonResponse
    {
        try {
            $clientUUID = $request->client_uuid;

            $cart = ShoppingCartUtils::getCart($clientUUID, $this->cache);
            return new JsonResponse($cart->toArray());
        }catch (Exception | \TypeError | ValidationFailedException | ValidatorException | HttpException $exception){
            return new JsonResponse([$exception->getMessage()], 400);
        }
    }
}
