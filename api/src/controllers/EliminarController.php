<?php 
  namespace App\Controllers;
  
	use App\Models\Registrar;
	use App\Models\Consultar;
  use App\Models\Eliminar;
  use App\Models\Actualizar;
  use App\Models\Metodo;

	
	class EliminarController
	{
    protected $json;
    //Recibir JSON
		public function __construct()
		{
      if ($_SERVER["REQUEST_METHOD"] == "POST") 
      {
        if(isset($_POST['json'])):
          $this->json = json_decode($_POST['json']);
        else: 
          $this->json = null;
        endif;
      }
    }
    //Eliminar favorito
    public function favorito()
    {
        //normalizamos datos para eliminar
        $datos = [
          "id_uno" => $this->json->idUser, 
          "id_dos" => $this->json->idProduct,
          "id_tres" => $this->json->model
        ];
       
        if(Eliminar::tipoTres($datos, "favoritos", "id_usuario", "id_producto", "modelo")):
          $respuesta = ["mensaje" => "Se elimino correctamente"];
          http_response_code(200);
          echo json_encode($respuesta);
        else:
          $respuesta = ["mensaje" => "No existe el favorito y no se logro hacer ninguna acción."];
          http_response_code(400);
          echo json_encode($respuesta);
        endif; 

    }

    // Eliminar color
		public function paletaColores()
		{
        //normalizamos datos para eliminar
        $datos = [
          "id_uno" => $this->json->id, 
        ];
        if(Eliminar::tipoUno($datos, "colores", "id")):
            $respuesta = ["mensaje" => "Se elimino correctamente"];
            http_response_code(200);
            echo json_encode($respuesta);
        else:
          $respuesta = ["mensaje" => "Color no existe"];
          http_response_code(406);
          echo json_encode($respuesta);
        endif; 
      
    }

    // Eliminar producto
		public function producto()
		{
      //variables
      $heredo = false;
      //Obtenemos el id del producto
      $id = $this->json->id;
      $datos['id_uno'] = $id;
      //consultamos la informacion del producto PADRE o HIJO a eliminar
      if($producto = Consultar::datos($id, "productos", "id", "")):else:
        echo '{"mensajeBackEnd": "Este producto no existe verifique su envio"}';
      endif;

      #verificamos si es padre ---------------------------
      if($producto['es_padre'] > 0):

        //Llamamos a los hijos 
        $hijos = Consultar::getHijos($producto['codigo']);
        
        //Heredamos al hijo mayor
        foreach ($hijos as $hijo):
          if($hijo['id'] != $id && $heredo == false):
            //otorgamos la primojenitura 
              if(Actualizar::heredero($hijo['codigo'], $producto['codigo'])):
                if(Actualizar::nuevoPadre($hijo['id'])):
                  $heredo = true;
                else: echo '{"mensajeBackEnd":"Error al asignar el nuevo padre"}'; endif;
              else: echo '{"mensajeBackEnd":"Error al otorgar herencia de padre"}'; endif;
          endif;
        endforeach;
      endif;
       
        //Eliminamos las tallas primero
        if(Eliminar::tipoUno($datos, "tallas", "id_producto") == false):
            echo '{"mensajeBackEnd": "Este producto no tiene tallas asignadas}"';
            http_response_code(401);
          else:
        
          //Eliminamos las imagenes de del producto PADRE
              $imagenes = explode(",",$producto['img']);
              if(count($imagenes) > 0):
                  foreach ($imagenes as $imagen):
                    if(unlink("../img/".$imagen)):
                    else: echo '{"mensajeBackEnd": "Las imagenes no se encuentran en el directorio}"'; endif; 
                  endforeach;    
                else:
                 echo '{"mensajeBackEnd": "No hay imagenes para eliminar}"';
              endif;    
        endif;

          //Eliminamos el producto
          if(Eliminar::tipoUno($datos, "productos", "id")):
              echo '{"mensaje": "Producto Eliminado correctamente"}';
              http_response_code(200);
            else:
              echo '{"mensaje": "Fallo al eliminar producto"}';
              http_response_code(401);
          endif;    

      
          
    }
    //eliminar talla
    public function talla()
    {
      $datos = [
        "id_uno" => $this->json->id
      ];

      try{
        Eliminar::tipoUno($datos,"tallas", "id");
        Actualizar::stock($this->json->codigo);
        http_response_code(200);
      }catch(Exception $e){
        $respuesta = ["mensaje" => "no elimino la talla"];
        http_response_code(406);
        echo json_encode($respuesta);
      }
    }
    

    //eliminar imagen 
    public function eliminarImagen()
    {
      $datos = $this->json;
      try{
        Eliminar::eliminarImagen($datos);
        http_response_code(200);
      }catch(Exception $e){
        $respuesta = ["mensaje" => "no elimino la imagen"];
        http_response_code(400);
        echo json_encode($respuesta);
      }
    }

}//cierre de la clase
		




	

