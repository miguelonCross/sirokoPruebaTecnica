<?php

declare(strict_types=1);

namespace App\Controller\CheckoutController;

use App\dto\OrderDTO;
use App\UseCase\CheckoutProcess\CheckoutProcess;
use App\UseCase\CheckoutProcess\CheckoutProcessRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class CheckoutController extends AbstractController
{
    public function __construct(
        private CheckoutProcess $checkoutProcess,
    ) {
    }

    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function execute(#[MapRequestPayload] CheckoutControllerRequest $request): JsonResponse
    {
        try {
            $order = $this->checkoutProcess->execute(new CheckoutProcessRequest(new Uuid($request->client_uuid), $request->pan, $request->holder, $request->address))->order;
            if (!is_null($order)) {
                return new JsonResponse((new OrderDTO($order))->toArray());
            }

            return new JsonResponse(['error' => 'Shopping cart not found'], 404);
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
