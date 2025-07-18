<?php

declare(strict_types=1);

namespace App\Controller\AddProductToShoppingCartController;

use App\Entity\ShoppingCartItem;
use App\UseCase\GetProductByUUID\GetProductByUUID;
use App\UseCase\GetProductByUUID\GetProductByUUIDRequest;
use App\utils\ShoppingCartUtils;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Exception\ValidatorException;

final class AddProductToShoppingCartController extends AbstractController
{
    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
        private GetProductByUUID $getProductByUUID,
    ) {
    }

    #[Route('/shoppingCart/add', name: 'add_product_to_shopping_cart', methods: ['POST'])]
    public function execute(#[MapRequestPayload] AddProductToShoppingCartControllerRequest $request): JsonResponse
    {
        try {
            $product = $this->getProductByUUID->execute(new GetProductByUUIDRequest(new Uuid($request->product_uuid)))->product;

            if (!is_null($product)) {
                $clientUUID = new Uuid($request->client_uuid);

                $shoppingCartItem = new ShoppingCartItem($product, $request->quantity);

                $shoppingCart = ShoppingCartUtils::addOrUpdateItem($clientUUID, $shoppingCartItem, $this->cacheItemPool);

                return new JsonResponse($shoppingCart->toArray());
            }

            return new JsonResponse('Product not found', 404);
        } catch (\Exception|\TypeError|ValidationFailedException|ValidatorException|HttpException $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }
}
