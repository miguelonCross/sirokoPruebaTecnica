<?php

namespace App\Controller\AddProductToShoppingCartController;

use App\dto\AddProductToShoppingCartRequest;
use App\Entity\ShoppingCartItem;
use App\UseCase\GetProductByUUID\GetProductByUUID;
use App\utils\ShoppingCartUtils;
use Exception;
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
    )
    {
    }

    #[Route('/shoppingCart/add', name: 'add_product_to_shopping_cart', methods: ['POST'])]
    public function execute(#[MapRequestPayload] AddProductToShoppingCartControllerRequest $request): JsonResponse
    {
        try {
            $getProductByUUID = new GetProductByUUID();
            $product = $getProductByUUID->execute($request->product_uuid);

            if (!is_null($product)) {
                $clientUUID = new Uuid($request->client_uuid);

                $shoppingCartItem = new ShoppingCartItem($product, $request->quantity);

                $shoppingCart = ShoppingCartUtils::addOrUpdateItem($clientUUID, $shoppingCartItem , $this->cacheItemPool);

                return new JsonResponse($shoppingCart->toArray());
            }

            return new JsonResponse('Product not found', 404);
        }catch (Exception | \TypeError | ValidationFailedException | ValidatorException | HttpException $exception)
        {
            return new JsonResponse($exception->getMessage(), 400);
        }
    }
}
