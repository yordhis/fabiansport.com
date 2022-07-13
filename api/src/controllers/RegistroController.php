<?php

namespace App\Controllers;


use App\Views;
use App\Models\Registrar;
use App\Models\Consultar;
use App\Models\Metodo;
use App\Models\Alerta;
use App\Models\DataPage;
use App\Models\Actualizar;
use App\Models\Correo;


class RegistroController
{
  protected $json;
  public $formData = null;


  //Recibir JSON
  public function __construct()
  {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // se accede a un objeto por medio de flechas
      if (isset($_POST['json'])) :

        if ($this->json = json_decode($_POST['json'])) : else :
          echo '{"mensaje":"El JSON no se pudo decodificar, chequee su JSON"}';
          return;
        endif;
      else :
        $this->json = null;
      endif;
    }
  }



  // REGISTRO DE USUARIO
  public function usuario()
  {
    $json =  $this->json;

    if (Registrar::usuario($json)) :
      //Aperturamos datos de entrega y factura
      //obtenemos el id del usuario
      $json->id = Consultar::getDato($json->email, "usuarios", "id", "correo");
      $usuario = Consultar::datos($json->email, "usuarios", "correo", "");
      Registrar::datosEntrega($json);
      Registrar::datosFactura($json);

      //retornamos la informacion del usuario
      $respuesta = DataPage::usuario($usuario, "id_usuario");
      http_response_code(201);
      echo json_encode($respuesta);
      return;

    else :
      $respuesta = ["mensaje" => "El correo ingresado ya existe"];
      http_response_code(409);
      echo json_encode($respuesta);
      return;
    endif;
  }

  //REGISTRO DE USUARIO
  public function paletaColores()
  {

    if (Registrar::color($_POST['color'])) :
      $respuesta = ["mensaje" => "Registro exitoso"];
      http_response_code(201);
      echo json_encode($respuesta);
    else :
      $respuesta = ["mensaje" => "El color {$_POST['color']} ya existe"];
      http_response_code(409);
      echo json_encode($respuesta);
    endif;
  }

  // REGISTRO DE FAVORITOS
  public function favorito()
  {

    if (Registrar::favorito($this->json)) :
      echo '{"mensaje": "Registro exitoso"}';
      http_response_code(201);
    else :
      echo '{"mensaje": "Registro de favorito Fallido "}';
      http_response_code(404);
    endif;
  }

  // REGISTRO DE PRODUCTO

  public function producto()
  {
    $formData = $this->json;
    # var_dump($formData);
    $arregloProducto;
    $arregloTallas;
    $i = 0;
    //DIRECTORIO
    $url = "../img/";
    $imagenes = $_FILES[0];
    $elementos = count($imagenes["name"]);
    //Normalizar imagenes para el registro
    $send_img = Metodo::normalizarImagen($imagenes);


    $arregloProducto = [
      "codigo" => $formData->codigo,
      "nombre" => $formData->name,
      "genero" => $formData->filtro->sex->id,
      "categoria" => $formData->filtro->categoria->id,
      "marca" => $formData->filtro->marca->id,
      "descuento" => $formData->descuento,
      "costo" => $formData->costo,
      "descripcion" => $formData->descripcion,
      "caracteristicas" => $formData->caracteristicas,
      "img" => $send_img,
      "id_color" => $formData->id_color,
      "es_padre" => $formData->es_padre,
      "mi_padre" => $formData->mi_padre
    ];

    // verificamos que el color no se repita dentro de la familia
    if (Consultar::datosRelacion($arregloProducto['mi_padre'], $arregloProducto['id_color'], "mi_padre", "id_color", 'productos', '*', '')) :
      echo '{"mensaje": "este color ya existe en tu familia de producto elije otro"}';
      return;
    endif;

    // Insertar producto
    if (Registrar::producto($arregloProducto)) :
      $idProducto = Consultar::fila($arregloProducto['codigo'], "codigo", "id", "productos");
      # Mandar a guardar las imagenes en el directorio
      if ($elementos > 1) :
        $img_name = explode(",",  $send_img);
        for ($x = 0; $x < $elementos; $x++) :
          $img = $imagenes["tmp_name"][$x];
          if (!move_uploaded_file($img, $url . $img_name[$x])) echo "{'mensaje': 'La imagen no se pudo guardar'}";

        endfor;
      endif;
    else:



      $respuestaCode = ["mensaje" => "Este codigo ya existe"];
      http_response_code(409);
      echo json_encode($respuestaCode);
      return false;
    endif;

    foreach ($formData->sizes as $size) {
      $arregloTalla =
        [
          "codigo" => $arregloProducto['codigo'],
          "nombre" => $size->name,
          "cantidad" => $size->quantity,
          "id_producto" => $idProducto[0]
        ];
      if (Registrar::talla($arregloTalla)) : else :
        $respuesta = ["mensaje" => "No se registro la talla"];
        http_response_code(409);
        echo json_encode($respuesta);
        return;
      endif;
    }
    # Actualizamos la existencia del producto registrado
    Actualizar::stock($formData->codigo);

    //contador incrementa
    $i++;
    # Validamos si el registro si el registro se ejecuto
    if ($i > 0) :
      $respuesta = ["mensaje" => "Registro exitoso"];
      http_response_code(201);
      echo json_encode($respuesta);
    else :
      $respuesta = ["mensaje" => "Fallo el registro"];
      http_response_code(400);
      echo json_encode($respuesta);
    endif;
  }

  // REGISTRO DE FACTURA
  public function factura()
  {
    $codigo;
    $estatus = false;
    $nombreEditado = "paypal";

    $factura = $this->json;

    //Normalizando la imagen

    if (isset($_FILES['image'])) :
      $url = "../img/comprobantes/";
      $nombre = $_FILES['image']['name'];
      $archivo = $_FILES['image']['tmp_name'];
      $nombreEditado = Metodo::imagenNombre($nombre);
      //movemos el archivo del comprobante
      if (move_uploaded_file($archivo, $url . $nombreEditado) == false) :
        echo '{"mensaje": "Fallo al mover la imagen del comprobante"}';
      endif;
    endif;
    //Generamos el codigo de la factura
    $factura->pay->code = Metodo::codigo();

    $factura->pay->image = $nombreEditado; //comprobante

    //Registramos los datos de la factura
    if (Registrar::factura($factura->pay)) :

      //registrar los productos de la factura
      foreach ($factura->products as $producto) :
        if (Registrar::carrito($producto, $factura->pay->code) == false) :
          echo '{"mensaje":"Fallo al registrar el producto"}';
          return;
        endif;
      endforeach;
      $idVenta = Consultar::getDato($factura->pay->code, "facturas", "id", "codigo");
      //enviamos el correo de notificacion
      if ($factura->pay->idUser != 1) :
        Correo::compra($factura->pay);
      endif;

      //Finalizamos y damos una respuesta
      http_response_code(201);
      echo json_encode(["code" => $factura->pay->code, "idVenta" => $idVenta]);
    else :
      echo '{"mensaje": "Fallo al registrar la factura"}';
      http_response_code(400);
    endif;
  }


  public function infoFactura()
  {

    $infoFactura = $this->json;

    //Registramos la info de la factura del usuario
    if (Registrar::infoFactura($infoFactura->invoice,  $infoFactura->code)) :
      //Registramos La info de la Entrega de usuario
      if (Registrar::infoEntrega($infoFactura->delivery, $infoFactura->code)) :
        //Respuesta del registro de datos de la factura para la entrega y facturacion
        if (Actualizar::usuarioFactura($infoFactura)) :
          http_response_code(201);
          echo json_encode(["mensaje" => "¡Gracias por su compra!"]);
        endif;
      endif;
    else :
      http_response_code(400);
      echo json_encode(["mensaje" => "Ups, Un error en el registro de sus datos."]);
    endif;
  }
}//cierre de la clase
