<?php

namespace App\UseCase\GetProductByUUID;

use App\Entity\Product;
use Symfony\Component\Uid\Uuid;

class GetProductByUUID
{
    public function __construct(){
    }

    public function execute(GetProductByUUIDRequest $request): GetProductByUUIDResponse
    {
        $product = null;

        //Simulamos una tabla de base de datos para obtener el producto mediante su UUID
        $products = file_get_contents(__DIR__ . '/../../mocks/mocks_product.json');
        $products = json_decode($products, true);

        foreach($products['products'] as $product){
            if($product['uuid'] === $request->productUUID){
                $product = new Product(
                    new Uuid($product['uuid']),
                    $product['name'],
                    $product['price'],
                    $product['description'],
                    $product['category']
                );
            }
        }
        return new GetProductByUUIDResponse($product);
    }
}
