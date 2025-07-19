# Prueba técnica para Siroko
    Como se pedia en el fichero de instrucciones, la aplicación trata de un carrito de la compra.

    Tenemos un total de 6 controladores.

    Un controlador que se encarga de generar/mostrar tu carrito según el clientUUID que le indiquemos (ShoppingCartController /shoppingCart 'POST') y también tenemos otro controlador que elimina el carrito buscando por clientUUID, si existe, y eliminarlo (DeleteShoppingCartController 'DELETE' ).
    Para almacenar el carrito he usado la memoria cache. He decidido que el carrito este asociado a un clientUUID por que en ningún caso un cliente tendrá mas de un carrito activado.

    Otro controlador se encarga de añadir nuevos artículos o editar la cantidad de los mismos pasandole el clientUUID (AddProductToShoppingCartController /shoppingCart/add).
    Tambien cabe indicar que si no existe un carrito, lo generará y añadirá el producto.

    A su vez tenemos otros dos controladores uno encargado de eliminar un producto (RemoveProductFromShoppingController /shoppingCart/add).

    Además tenemos un controlador que genera una orden indicándole un clientUUID buscará si tiene carrito activo. Una vez generada la orden se elminia el carrito (GenerateOrderController /createOrder).ç

    Por último otro endpoint procesa la orden (se falsea) mediante el order_uuid generado anteriormente (CheckOutProcess /checkout).


    Para persistir las ordenes he usado un fichero .json lamentablemente no he conseguido realizar estos test con éxito y debido a la falta de tiempo subo lo que tengo.
    De este modo evito usar bases de datos, migraciones etc. Aunque con los problemas experimentados y por la falta de tiempo no he podido realizar exitosamente los test de los casos de uso que utilizan estos ficheros.
    Posiblemente de haber generado las migraciones y entidades con las make:entity habría sido más fácil montando un docker con la base de datos.
    
## Lanzar nuestro contenedor
    docker compose up --build -d && docker compose exec -it siroko-prueba-tecnica sh

## Comandos varios
    composer test
    composer phpstan
    composer cs-fix 

