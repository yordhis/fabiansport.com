<?php


define('ROUTES', [
   
		// Producto
		'productos' => ['controllers' => 'Producto', 'action' => 'index'],
		  'productosFiltro' => ['controllers' => 'Producto', 'action' => 'indexFiltro'],
      'producto' => ['controllers' => 'Producto', 'action' => 'show'],
      'productosAdmin' => ['controllers' => 'Producto', 'action' => 'indexAdmin'],
      'productosAdminFiltro' => ['controllers' => 'Producto', 'action' => 'indexAdminFiltro'],
      'productoDetalle' => ['controllers' => 'Producto', 'action' => 'productoDetalle'],
      'crearProducto' => ['controllers' => 'Producto', 'action' => 'crear'],
      'actualizarProducto'   => ['controllers' => 'Producto', 'action' => 'actualizar'],
      'eliminarProducto'  => ['controllers' => 'Producto', 'action' => 'eliminar'],
      'eliminarImagen'  => ['controllers' => 'Img', 'action' => 'eliminarImagen'],
      'actualizarDescuento'   => ['controllers' => 'Producto', 'action' => 'descuento'],
      'buscador'  => ['controllers' => 'Producto', 'action' => 'buscador'],
      'totalStock'  => ['controllers' => 'Producto', 'action' => 'totalStock'],
    //cierre producto

    // Categoria
    'categorias' => ['controllers' => 'Categoria', 'action' => 'listar'],
      'categoria' => ['controllers' => 'Categoria', 'action' => 'show'],
      'crearCategoria' => ['controllers' => 'Categoria', 'action' => 'crear'],
      'actualizarCategoria' => ['controllers' => 'Categoria', 'action' => 'actualizar'],
      'eliminarCategoria' => ['controllers' => 'Categoria', 'action' => 'eliminar'],
    //cierre categoria

    // Talla
    'tallas' => ['controllers' => 'Talla', 'action' => 'listar'],
      'talla' => ['controllers' => 'Talla', 'action' => 'show'],
      'crearTalla' => ['controllers' => 'Talla', 'action' => 'crear'],
      'actualizarTalla' => ['controllers' => 'Talla', 'action' => 'actualizar'],
      'eliminarTalla' => ['controllers' => 'Talla', 'action' => 'eliminar'],
    //cierre tallas
    
    // Marca
    'marcas' => ['controllers' => 'Marca', 'action' => 'listar'],
      'marca' => ['controllers' => 'Marca', 'action' => 'show'],
      'crearMarca' => ['controllers' => 'Marca', 'action' => 'crear'],
      'actualizarMarca' => ['controllers' => 'Marca', 'action' => 'actualizar'],
      'eliminarMarca' => ['controllers' => 'Marca', 'action' => 'eliminar'],
    //cierre marcas

    // Genero
    'generos' => ['controllers' => 'Genero', 'action' => 'listar'],
      'genero' => ['controllers' => 'Genero', 'action' => 'show'],
      'crearGenero' => ['controllers' => 'Genero', 'action' => 'crear'],
      'actualizarGenero' => ['controllers' => 'Genero', 'action' => 'actualizar'],
      'eliminarGenero' => ['controllers' => 'Genero', 'action' => 'eliminar'],
    //cierre genero

    // Colores
    'colores' => ['controllers' => 'Color', 'action' => 'listar'],
      'color' => ['controllers' => 'Color', 'action' => 'show'],
      'crearColor' => ['controllers' => 'Color', 'action' => 'crear'],
      'actualizarColor' => ['controllers' => 'Color', 'action' => 'actualizar'],
      'eliminarColor' => ['controllers' => 'Color', 'action' => 'eliminar'],
    //ciere colores

    // Favoritos
    'favoritos' => ['controllers' => 'Favorito', 'action' => 'listar'],
      'favorito' => ['controllers' => 'Favorito', 'action' => 'show'],
      'crearFavorito' => ['controllers' => 'Favorito', 'action' => 'crear'],
      'actualizarFavorito' => ['controllers' => 'Favorito', 'action' => 'actualizar'],
      'eliminarFavorito' => ['controllers' => 'Favorito', 'action' => 'eliminar'],
    //cierre favoritos

    // FACTURA
    'facturas' => ['controllers' => 'Factura', 'action' => 'index'],
      'misCompras'  => ['controllers' => 'Factura', 'action' => 'misCompras'],
      'detalleCompra'  => ['controllers' => 'Factura', 'action' => 'detalleCompra'],
      'facturasAdmin' => ['controllers' => 'Factura', 'action' => 'indexAdmin'],
      'factura' => ['controllers' => 'Factura', 'action' => 'show'],
      'crearFactura' => ['controllers' => 'Factura', 'action' => 'crear'],
      'actualizarEstatusFactura'  => ['controllers' => 'Factura', 'action' => 'actualizarEstatusFactura'],
      'asignarInfoEntrega'  => ['controllers' => 'Factura', 'action' => 'asignarInfoEntrega'],
      'actualizarFactura' => ['controllers' => 'Factura', 'action' => 'actualizar'],
      'eliminarFactura' => ['controllers' => 'Factura', 'action' => 'eliminar'],
      'enviarCorreo' => ['controllers' => 'Factura', 'action' => 'enviarCorreo'],
    //cierre factura
    
    // Informacion de factura info_factura
    'carrito' => ['controllers' => 'Carrito', 'action' => 'listar'],
      'traerUnPoductoCarrito' => ['controllers' => 'Carrito', 'action' => 'traerUnPoductoCarrito'],
      'crearCarrito' => ['controllers' => 'Carrito', 'action' => 'crear'],
      'actualizarUnProductoDelCarrito' => ['controllers' => 'Carrito', 'action' => 'actualizar'],
      'eliminarCarrito' => ['controllers' => 'Carrito', 'action' => 'eliminar'],
      'eliminarProductoDelCarrito' => ['controllers' => 'Carrito', 'action' => 'eliminarProductoDelCarrito'],
    //cierre Informacion de factura info_factura

    // Informacion de factura info_factura
    'infoFactura' => ['controllers' => 'Infofactura', 'action' => 'show'],
      'crearInfoFactura' => ['controllers' => 'Infofactura', 'action' => 'crear'],
      'actualizarInfoFactura' => ['controllers' => 'Infofactura', 'action' => 'actualizar'],
      'eliminarInfoFactura' => ['controllers' => 'Infofactura', 'action' => 'eliminar'],
    //cierre Informacion de factura info_factura

    // Informacion de factura info_Entrega
    'infoEntrega' => ['controllers' => 'Infoentrega', 'action' => 'show'],
      'crearInfoEntrega' => ['controllers' => 'Infoentrega', 'action' => 'crear'],
      'actualizarInfoEntrega' => ['controllers' => 'Infoentrega', 'action' => 'actualizar'],
      'eliminarInfoEntrega' => ['controllers' => 'Infoentrega', 'action' => 'eliminar'],
    //cierre Informacion de factura info_Entrega

    // Informacion de factura dato_factura
    'datoFactura' => ['controllers' => 'Datofactura', 'action' => 'show'],
      'crearDatoFactura' => ['controllers' => 'Datofactura', 'action' => 'crear'],
      'actualizarDatoFactura' => ['controllers' => 'Datofactura', 'action' => 'actualizar'],
      'eliminarDatoFactura' => ['controllers' => 'Datofactura', 'action' => 'eliminar'],
    //cierre Informacion de factura dato_factura

    // Informacion de factura dato_entrega
    'datoEntrega' => ['controllers' => 'Datoentrega', 'action' => 'show'],
      'crearDatoEntrega' => ['controllers' => 'Datoentrega', 'action' => 'crear'],
      'actualizarDatoEntrega' => ['controllers' => 'Datoentrega', 'action' => 'actualizar'],
      'eliminarDatoEntrega' => ['controllers' => 'Datoentrega', 'action' => 'eliminar'],
    //cierre Informacion de factura dato_entrega

    //Usuario
    'login'  => ['controllers' => 'Usuario', 'action' => 'login'],
    'clientes'  => ['controllers' => 'Usuario', 'action' => 'listar'],
    'misDatos'  => ['controllers' => 'Usuario', 'action' => 'misDatos'],
    'loginRedSocial'  => ['controllers' => 'Usuario', 'action' => 'loginRedSocial'],
    'registrarUsuario' => ['controllers' => 'Usuario', 'action' => 'registrarUsuario'],
    'actualizarUsuario'   => ['controllers' => 'Usuario', 'action' => 'actualizarUsuario'],
    'eliminarUsuario'   => ['controllers' => 'Usuario', 'action' => 'eliminarUsuario'],
    'recuperarClave'  => ['controllers' => 'Usuario', 'action' => 'recuperarClave'],
    'actualizarClave'  => ['controllers' => 'Usuario', 'action' => 'actualizarClave'],
    
    
    //Page
    'campana'  => ['controllers' => 'Page', 'action' => 'campana'],
    'notificaciones'  => ['controllers' => 'Page', 'action' => 'notificaciones'],
    'clic'   => ['controllers' => 'Page', 'action' => 'clic'],
    'consultarTasa'  => ['controllers' => 'Page', 'action' => 'tasa'],
    'actualizarTasa'  => ['controllers' => 'Page', 'action' => 'actualizarTasa']
   
]);
