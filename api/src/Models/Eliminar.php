<?php
    namespace App\Models;

    use App\Models\{Ayudador, Validar};



class Eliminar 
    {
      static function tipoUno($id, $tabla, $columnaComparar){
        include (APP_PATH . 'config/database.php');
        $sql = "DELETE FROM $tabla WHERE $columnaComparar = :id";
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        $filaAfectadas = $sentencia->rowCount();
        if($filaAfectadas > 0) return true; 
        else{
          return $sentencia->infoError();
         $error = Validar::mensajeSql($sentencia->infoError());
          return $error; 
        } 
      }
      
      public function tipoDos($datos, $tabla, $celdaUno, $celdaDos)
      {
        
        include(APP_PATH . 'config/database.php');
        $sql = "DELETE FROM $tabla WHERE $celdaUno = :id_uno && $celdaDos = :id_dos";
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':id_uno', $datos['id_uno']);
        $sentencia->bindParam(':id_dos', $datos['id_dos']);
        $sentencia->execute();
        $filaAfectadas = $sentencia->rowCount();

        if($filaAfectadas > 0): return true; else: return false; endif;
      }

      public function tipoTres($datos, $tabla, $celdaUno, $celdaDos, $celdaTres)
      {
        
        include(APP_PATH . 'config/database.php');
        $sql = "DELETE FROM $tabla WHERE $celdaUno = :id_uno && $celdaDos = :id_dos && $celdaTres = :id_tres";
        $sentencia = $pdo->prepare($sql);
        $sentencia->bindParam(':id_uno', $datos['id_uno']);
        $sentencia->bindParam(':id_dos', $datos['id_dos']);
        $sentencia->bindParam(':id_tres', $datos['id_tres']);
        $sentencia->execute();
        $filaAfectadas = $sentencia->rowCount();

        if($filaAfectadas > 0): return true; else: return false; endif;
      }
      public function eliminarImagen($datos)
      {
        $imgText="";
        $modelo = Consultar::datos($datos->idModelo, "producto_color", "id", "");
        // rehacemos el array
        $imagenes = explode(",",$modelo['img']);
        //Secompran las imagenes para excluir la imagen que se 
        //quiere eliminar
          foreach ($imagenes as $imagen):
            if($datos->img != $imagen):
              $imgText .= $imagen . ","; 
            endif;
          endforeach;
          // limpiamos la coma
          $imgText = substr($imgText,0,-1);

          //Actualizamos la imagen y eliminamos de la carpeta
          if(Actualizar::img($datos->idModelo, $imgText) == true):
            $url = "../img/";
            $img = $datos->img;
            $urlImg = $url.$img;
            unlink("$urlImg");
            return true;
          else:
            return false;
          endif;

      }

      public function eliminarVariasImagen($datos)
      {
        $imgText="";
        $modelo = Consultar::fila($datos->id, "id", "img", "producto_color");
        // rehacemos el array
        $imagenes = explode(",",$modelo['img']);
      
          foreach ($imagenes as $img):
            $url = "../img/";
            $urlImg = $url.$img;
            unlink($urlImg);
          endforeach;       

      }


    }//cierre de la clase
    


?>
