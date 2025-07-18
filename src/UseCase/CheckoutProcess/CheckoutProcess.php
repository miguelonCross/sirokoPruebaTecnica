<?php

declare(strict_types=1);

namespace App\UseCase\CheckoutProcess;

use App\Entity\PaymentRequest;
use App\Entity\PaymentResponse;
use App\UseCase\GenerateOrderByClientUUID\GenerateOrderByClientUUID;
use App\UseCase\GenerateOrderByClientUUID\GenerateOrderByClientUUIDRequest;
use App\UseCase\UpdateOrderStatusByUUID\UpdateOrderStatusByUUID;
use App\UseCase\UpdateOrderStatusByUUID\UpdateOrderStatusByUUIDRequest;
use Symfony\Component\Uid\Uuid;

class CheckoutProcess
{
    public function __construct(
        private GenerateOrderByClientUUID $generateOrderByClientUUID,
        private UpdateOrderStatusByUUID $updatedOrderStatusByUUID,
    ) {
    }

    public function execute(CheckoutProcessRequest $request): CheckoutProcessResponse
    {
        $order = $this->generateOrderByClientUUID->execute(new GenerateOrderByClientUUIDRequest($request->clientUUID))->order;

        if (!is_null($order)) {
            /**
             * Aquí deberíamos tener una serie de llamdas a una pasarela de pagos.
             */
            $request = new PaymentRequest($request->pan, $order->amount, $request->holder, 'AUTHORIZATION', 'BIZUM', ['address' => $request->address]);
            $response = new PaymentResponse(new Uuid('5af56fc3-fcdf-450d-9b86-9f5e05d47090'), '0', 'SUCCESS');

            $updatedOrder = $this->updatedOrderStatusByUUID->execute(new UpdateOrderStatusByUUIDRequest($order->uuid, $response->status))->order;

            return new CheckoutProcessResponse($updatedOrder);
        }

        return new CheckoutProcessResponse($order);
    }
}
