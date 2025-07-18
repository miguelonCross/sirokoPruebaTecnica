<?php

namespace App\Controller\GenerateOrderController;

use App\UseCase\GenerateOrderByClientUUID\GenerateOrderByClientUUID;
use App\UseCase\GenerateOrderByClientUUID\GenerateOrderByClientUUIDRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class GenerateOrderController extends AbstractController
{
    public function __construct(
        private GenerateOrderByClientUUID $generateOrderByClientUUID,
    )
    {
    }

    #[Route('/createOrder', name: 'create_order', methods: ['POST'])]
    public function execute(#[MapRequestPayload] GenerateOrderControllerRequest $request): JsonResponse
    {
        try {
            $order = $this->generateOrderByClientUUID->execute(new GenerateOrderByClientUUIDRequest(new Uuid($request->client_uuid)))->order;

            if (!is_null($order)){
                return new JsonResponse([$order->toArray()]);
            }

            return new JsonResponse(['error' => 'Shopping cart is empty'], 400);
        }catch (\Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }
    }
}
