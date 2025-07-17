<?php

namespace App\UseCase\GetProductByUUID;

use App\Entity\Product;
use Symfony\Component\Uid\Uuid;

class GetProductByUUID
{
    public function __construct(){
    }

    public function execute(string $uuid): ?Product
    {
        //Simulamos una tabla de base de datos para obtener el producto mediante su UUID
        $products = file_get_contents(__DIR__ . '/../../mocks/mocks_product.json');
        $products = json_decode($products, true);

        foreach($products['products'] as $product){
            if($product['uuid'] === $uuid){
                return new Product(new Uuid($product['uuid']), $product['name'], $product['price'], $product['description'], $product['category']);
            }
        }
        return null;
    }
}
