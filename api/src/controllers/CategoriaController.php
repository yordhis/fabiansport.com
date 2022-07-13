<?php 
namespace App\Controllers;

use App\Models\{Categoria, Validar, Ayudador, Formulario};

class CategoriaController
{
  private $categoria;
  //Recibimos los JSON
  public function __construct(){
    $this->categoria = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
    json_decode(file_get_contents('php://input'), true),
    $_POST['json'] ?? null);
  }

  public function listar(){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
    
    //Ejecutamos la solicitud de listar todas las categorias
    if(!$categorias = Categoria::listar()){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($categorias);
    }
  } 
  
  public function show($idCateoria){
    //validamos el metodo de envio (se espera GET)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;

    //ejecutamos la accionde mostrar la categoria
    if(!$categoria = Categoria::show($idCateoria)){
      return Validar::respuestasHttp("No hay resultados", 200);
    }else{
      Validar::respuestasHttp(null,200);
      echo json_encode($categoria);
    }
  }

  public function crear(){
    //Abrir validaciones
      //validamos el metodo de envio (se espera POST)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;

      //obtenemos el nombre de la categoria para crear
      $categoria = Ayudador::quitarEspacios($this->categoria['categoria']);

      //validamos que tengamos datos en la variable categoria
      if(empty($categoria)){
        $mensaje = "No hay datos para registrar, por favor verifique el metodo de envio. Metodo recibido: ";
        return Validar::respuestasHttp($mensaje . $_SERVER['REQUEST_METHOD'], 409);
      }

      //Validamos que la categoria que queremos crear no exista
      if($mensaje = Validar::noExisteElDato("La categoria (".$categoria['nombre']. ") ya existe", 
        $categoria['nombre'], "categorias", "nombre")) {
        return $mensaje;
      }

      //validamos que no tenga caracteres extraños
      if($mensaje = Formulario::categoria($categoria)) return $mensaje;

      //validamos que los caracteres no pasen de 50 lo permitido en la DB
      if($mensaje = Validar::longitudPermitida($categoria['nombre'] , 150)) return $mensaje;
    //Cierre de validaciones

    //ejecutamos la creacion de la categoria
    $resultado = Categoria::crear($categoria);
    if($resultado === true){
      return Validar::respuestasHttp("La categoria (". $categoria['nombre'] .") fue creada con exito", 201);
    }else {
      return Validar::respuestasHttp($resultado, 409); 
    }
  } 
  
  public function actualizar(){
    
    //Abrir validaciones
      //validamos el metodo de envio (se espera PUT)
      if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
        
      //obtenemos los datos de la categoria para editarla
      $categoria = Ayudador::quitarEspacios($this->categoria['categoria']); //datosenviados
      $categoriaActual = Categoria::show($categoria['id']); //Datos de la DB actuales

      //Validamos que la categoria que queremos crear no exista
      if($mensaje = Validar::noExisteElDato("No se detecto ningun cambio en la categoria: (".$categoria['nombre'] .")",
        $categoria['nombre'] , "categorias", "nombre")) {
        echo $mensaje; 
        Validar::respuestasHttp(null, 409); return;
      }
      //validamos que los caracteres no pasen de 50 lo permitido en la DB
      if($mensaje = Validar::longitudPermitida($categoria['nombre'] , 150)) return $mensaje;

      //validamos que no tenga caracteres extraños
      if($mensaje = Formulario::categoria(["nombre" => $categoria['nombre']])) return $mensaje;
      
      //Validamos que la categoria que queremos editar si exista
      if($mensaje = Validar::siExisteElDato("Este id ". $categoria['id']  ." no esta asignado a ninguna categoria", 
      $categoria['id'], "categorias", "id")) return $mensaje;
    //Cierre validaciones

    //ejecutamos la actualizacion 
    $resultado = Categoria::actualizar($categoria);
    if($resultado === true){
      return Validar::respuestasHttp("La categoria (". $categoriaActual['categoria']['nombre'] .") fue editada con exito", 200);
    }else {
      return Validar::respuestasHttp("La categoria (". $categoriaActual['categoria']['nombre'] .") no sufrio ningún cambio", 200); 
    }

  }

  public function eliminar($idCateoria){
    //validamos el metodo de envio (se espera POST)
    if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;

    //Validamos que el id enviao exista para seguir con la accion de eliminar
    if($mensaje = Validar::siExisteElDato("El id de la categoria no existe para ejecutar la acción", 
    $idCateoria, "categorias", "id")) return $mensaje;
    //Ejecutamos la accion de eliminar
    $categoria = Categoria::eliminar($idCateoria);
    //Validamos el resultado de la acción 
    if($categoria === true){
      return Validar::respuestasHttp("Elimino la categoria de id: {$idCateoria}",200);
    }else{
      return Validar::respuestasHttp($categoria, 409);
    }
  }



}//cierre de la clase

	
