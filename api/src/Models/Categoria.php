<?php 
	namespace App\Models;

  use App\Models\{Ayudador, Arreglar, Eliminar};
 
	
	class Categoria 
	{
    //CONSULTAR CATEGORIAS
    static function listar(){
      if($categorias = Ayudador::getDatos('categorias','*')){
      $respuestaLimpia['categorias'] = Arreglar::paraJsonPlural($categorias);
      return $respuestaLimpia;
      }
      else return false;
    }
    
    //CONSULTA IDIVIDUAL
    static function show($categoria){
      if($categoria = Ayudador::datos($categoria,'categorias','id','*')){
        $respuestaLimpia['categoria'] = Arreglar::paraJsonSingular($categoria);
        return $respuestaLimpia;
      }else return false;
    }

    // CREAR CATEGORIA
    static function crear($categoria){
      include (APP_PATH . 'config/database.php');
      //Registro de datos de Factura
      $sql =  "INSERT INTO categorias (nombre) 
      VALUES (:nombre)";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':nombre',  $categoria['nombre']);      
      if($sentencia->execute()) return true; 
      else{
        return Validar::mensajeSql($sentencia->errorInfo());
      }
    }

		// ACTUAIZAR CATEGORIA
		static function actualizar($categoria){ 
      include (APP_PATH . 'config/database.php');
        
        $sql = "UPDATE categorias SET nombre = :nombre
        WHERE id = :id ";
        $sentencia = $pdo->prepare($sql); 
        $sentencia->bindParam(":nombre", $categoria['nombre']);        
        $sentencia->bindParam(":id", $categoria['id']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0) return true; 
        else return false;     
		}

    // ELIMINAR CATEGORIA
    static function eliminar($idCategoria){

      include (APP_PATH . 'config/database.php');
        $sql = "DELETE FROM categorias WHERE id = :id";
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':id', $idCategoria);
        $sentencia->execute();
        $filaAfectadas = $sentencia->rowCount();
        if($filaAfectadas > 0) return true; 
        else{
          // return $sentencia->errorInfo();
          return Validar::mensajeSql($sentencia->errorInfo());
          
        } 
    }
   
	}//cierre de la clase
		
 ?>
