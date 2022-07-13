<?php

namespace App\Models;

class Usuario
{
//------------****--------- CONSULTAS --------****--------
static function listar(){
  if($usuarios = Ayudador::getDatos('usuarios','*')){
    $resulUsuarios = Arreglar::paraJsonPlural($usuarios);
    foreach ($resulUsuarios as $cliente) {
      $telefono =  Ayudador::fila($cliente['id'],"id_usuario", "telefono", "datos_factura") ;
      if($cliente['tipo'] != 1){
        $lista[] = [
          "id" => $cliente['id'],
          "name" => $cliente['nombres'],
          "email" => $cliente['correo'],
          "phone" => $telefono['telefono']         
        ];
      }
    }
    return $lista;
  }else return false;
}

  //Retorna la informacion de una entrega por medio del codigo 
  static function login($user){
    $usuario = Ayudador::datos($user['email'], "usuarios", "correo", "singular");
    if($usuario != false ){
      if($usuario['clave'] == md5($user['password'])){
        return $usuario;
      }
    }else return false;
  }

  static function show($idUsuario, $campo){
    if($usuario = Ayudador::datos($idUsuario,'usuarios',$campo,'singular')){
     
      $datosFactura = Ayudador::datos($idUsuario, "datos_factura", "id_usuario", "singular");
      $datosEntrega = Ayudador::datos($idUsuario, "datos_entrega", "id_usuario", "singular");
      
      $respuestaLimpia['usuario'] = Arreglar::paraJsonSingular($usuario);
      if ($datosFactura != false && $datosEntrega != false) {
        $respuestaLimpia['usuario']['datosFactura'] = Arreglar::paraJsonSingular($datosFactura);
        $respuestaLimpia['usuario']['datosEntrega'] = Arreglar::paraJsonSingular($datosEntrega);
      }

      return $respuestaLimpia;
    }else return false;
  }


//------------****--------- REGISTROS --------****--------
    static function crear($usuario){
       
        include (APP_PATH . 'config/database.php');
        $pass = md5($usuario['password']);     
        $sql =  "INSERT INTO usuarios (correo, clave) VALUES(:correo, :clave)";
  
        $sentencia = $pdo->prepare($sql);
  
        $sentencia->bindParam(':correo', $usuario['email']);
        $sentencia->bindParam(':clave', $pass);
            
        if($sentencia->execute()) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
    }

    // REGISTRO DE USUARIO CON RED SOCIAL
		static function crearCuentaConRedSocial($loginUsuario)
		{ 
      include (APP_PATH . 'config/database.php');
        
      $sql =  "INSERT INTO usuarios (nombres, correo, id_google, id_facebook, img_google, img_facebook) 
      VALUES(:nombres, :correo, :id_google, :id_facebook, :img_google, :img_facebook)";

      $sentencia = $pdo->prepare($sql);

      //validamos si los ids de las redes existen o sino asignar 0
      $google = $loginUsuario['googleId'] ?? 0;
      $facebook = $loginUsuario['facebookId'] ?? 0;
      $avatarG = "";
      $avatarF = "";
      //validamos la imagen del avatar
      if($google != 0):
        $avatarG = $loginUsuario['imageUrl'];
      elseif($facebook != 0):
          $avatarF = $loginUsuario['imageUrl'];    
      endif;

      $sentencia->bindParam(':nombres', $loginUsuario['name']);
      $sentencia->bindParam(':correo', $loginUsuario['email']);
      $sentencia->bindParam(':id_facebook', $facebook);
      $sentencia->bindParam(':id_google', $google);
      $sentencia->bindParam(':img_google', $avatarG);
      $sentencia->bindParam(':img_facebook', $avatarF);
      
      if($sentencia->execute()) return true; 
      else return false; 
    }

  //------------****--------- ACTUALIZACIONES --------****--------
  //Actualizar avatar o id de redes sociales
  static function actualizarAvatar($loginUsuario, $redSocial = "id_facebook", $avatar = "img_facebook", $keyRedSocial = "facebookId")
  {
    include (APP_PATH . 'config/database.php');
    
    //Actualizar registro completo 
    $sql =  "UPDATE usuarios SET $redSocial = :redSocial, $avatar = :avatar
    WHERE correo = :correo ";

    $sentencia = $pdo->prepare($sql);
    
    $sentencia->bindParam(':correo', $loginUsuario['email']);
    $sentencia->bindParam(':redSocial', $loginUsuario[$keyRedSocial]);
    $sentencia->bindParam(':avatar', $loginUsuario['imageUrl']);
    
    $sentencia->execute(); 
    if($sentencia->rowCount() > 0) return true; 
    else return Validar::mensajeSql($sentencia->errorInfo());

  }
  
  static function actualizarToken($token, $correo){
    include (APP_PATH . 'config/database.php');

    //Actualizar registro completo 
    $sql =  "UPDATE usuarios SET token = :token
    WHERE correo = :correo ";

    $sentencia = $pdo->prepare($sql);
    
    $sentencia->bindParam(':correo', $correo);
    $sentencia->bindParam(':token', $token);
    $sentencia->execute();
    if($sentencia->rowCount()):
      return true;
    else:
      return false;
    endif;
  }

  static function actualizarClave($clave, $correo){
          include (APP_PATH . 'config/database.php');

          $clave = md5($clave);

          //Actualizar registro completo 
					$sql =  "UPDATE usuarios SET clave = :clave
          WHERE correo = :correo ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':correo', $correo);
					$sentencia->bindParam(':clave', $clave);
          $sentencia->execute();
					if($sentencia->rowCount()):
						return true;
          else:
						return false;
					endif;
  }

  static function actualizar($usuario){
    include (APP_PATH . 'config/database.php');

     //Actualizar registro completo 
     $sql =  "UPDATE usuarios SET nombres = :nombres, adulto = :adulto,
     usar_dato_factura = :usar_dato_factura
     WHERE id = :id ";

     $sentencia = $pdo->prepare($sql);
     
     $sentencia->bindParam(':id', $usuario['id']);
     $sentencia->bindParam(':nombres', $usuario['name']);
     $sentencia->bindParam(':adulto', $usuario['adult']);
     $sentencia->bindParam(':usar_dato_factura', $usuario['repeaInBilling']); 
   

        $sentencia->execute(); 
        if($sentencia->rowCount() > 0) return true; 
        else return Validar::mensajeSql($sentencia->errorInfo());
  }

  static function tasa($idAdmin, $tasa){
          include (APP_PATH . 'config/database.php');
					$sql =  "UPDATE usuarios SET tasa = :tasa
          WHERE id = :id ";

          $sentencia = $pdo->prepare($sql);
          
          $sentencia->bindParam(':id', $idAdmin);
					$sentencia->bindParam(':tasa', $tasa);
          $sentencia->execute();
					if($sentencia->rowCount()):return true; else:return false; endif;			
  }
    
  //------------****--------- ELIMINAR --------****--------
  static function eliminar($idUser){

    include (APP_PATH . 'config/database.php');
      $sql = "DELETE FROM usuarios WHERE id = :id OR correo = :id";
      $sentencia = $pdo->prepare($sql);
      $sentencia->bindParam(':id', $idUser);
      $sentencia->execute();
      $filaAfectadas = $sentencia->rowCount();
      if($filaAfectadas > 0) return true; 
      else return Validar::mensajeSql($sentencia->errorInfo());
      
  }
}
