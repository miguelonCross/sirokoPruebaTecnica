<?php

namespace App\Controller;

use App\dto\RemoveProductoFromShoppingCartRequest;
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

final class RemoveProductFromShoppingCartController extends AbstractController
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
    )
    {
    }

    #[Route('/shoppingCart/remove', name: 'app_remove_product_from_shopping_cart', methods: ['DELETE'])]
    public function index(#[MapRequestPayload] RemoveProductoFromShoppingCartRequest $request): JsonResponse
    {
        try {
            $clientUUID = $request->client_uuid;
            $productCode = $request->code;

            $isDeleted = ShoppingCartUtils::deleteProduct($clientUUID, $productCode, $this->cacheItemPool);

            if (is_null($isDeleted)){
                return new JsonResponse(['message' => 'Shopping cart not found'], 404);
            } elseif (!$isDeleted){
                return new JsonResponse(['message' => 'Product not found in shopping cart'], 404);
            } else {
                return new JsonResponse(['message' => 'Product removed'], 200);
            }
        }catch (Exception | \TypeError | ValidationFailedException | ValidatorException | HttpException $exception)
        {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }
}
