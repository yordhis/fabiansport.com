<?php
    namespace App\Controllers;

    use App\Models\{Ayudador, Datoentrega, Datofactura};
    use App\Models\{Usuario, Correo, Formulario, Validar, Arreglar};

    class UsuarioController
    {
      private $usuario = [];
      //Recibimos los JSON
      public function __construct(){
        $this->usuario = Ayudador::getDatosRecibidos($_SERVER["REQUEST_METHOD"],
        json_decode(file_get_contents('php://input'), true),
        json_decode($_POST['json'] ?? null, true));
      }

      public function listar(){
        //validamos el metodo de envio (se espera GET)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        
        // Consultamos los datos de los clientes
          $clientes = Usuario::listar();
        //Respuesta del resultado
          if(empty($clientes)){
            return Validar::respuestasHttp("No hay resultados", 200);
          }else{
            Validar::respuestasHttp(null,200);
            return json_encode($clientes);
          }
      }

      public function misDatos($idUsuario){
        //validamos el metodo de envio (se espera GET)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "GET")) return $mensaje;
        //consultamos
        $misDatos = Usuario::show($idUsuario, "id");
        //validamos el resultado
        if($misDatos == false){
         return Validar::respuestasHttp("No hay resultados", 200);
        }else{
          $user = Arreglar::misDatos($misDatos['usuario']);
          //retornamos la lista de clientes
          http_response_code(200);
          echo json_encode($user);
          return;
        }
        
      }

      public function login(){   
        //validamos el metodo de envio (se espera POST)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        //datos recibidos
        $usuario = $this->usuario;
        //Consultamos los datos del correo
        $accesoConcedido = Usuario::login($usuario);
          if ($accesoConcedido == false){
            return Validar::respuestasHttp("Usuario o contraseña invalida", 409);
          }else{ 
            $respuesta = Arreglar::usuario($accesoConcedido, "id_usuario");
            http_response_code(200);
            echo json_encode($respuesta);
          }   
      }
            
      public function loginRedSocial(){
          //validamos el metodo de envio (se espera POST)
          if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
          //Daos recibidos
          $loginUsuario = $this->usuario;
         
          if($usuario = Ayudador::datos($loginUsuario['googleId'] ?? null, "usuarios", "id_google", "")){
            //login con google     
            $respuesta = Arreglar::usuario($usuario, "id_usuario", "google");
            http_response_code(200);
            echo json_encode($respuesta);
            
          }elseif ($usuario = Ayudador::datos($loginUsuario['facebookId'] ?? null, "usuarios", "id_facebook", "")) {
            //login con facebbok
            $respuesta = Arreglar::usuario($usuario, "id_usuario", "facebook");
            http_response_code(200);
            echo json_encode($respuesta);
          }else{
            
            //----------- REGISTRAMOS EL USUARIO PARA DESPUES SER LOGEARLO -----------------//
            $resultadoCrearUsuario = Usuario::crearCuentaConRedSocial($loginUsuario);
            
            if($resultadoCrearUsuario){
                            
                $usuario = Ayudador::datos($loginUsuario['email'], "usuarios", "correo", "");
                $loginUsuario['id'] = $usuario['id'];
               
                if(!$resulDatoEntrega = Datoentrega::crear($loginUsuario)) 
                return Validar::respuestasHttp($resulDatoEntrega,409);
               
                if(!$resulDatoFactura = Datofactura::crear($loginUsuario)) 
                return Validar::respuestasHttp($resulDatoFactura,409);
               
                
                //identificamos que red soccial esta registrando
                $idenRed = $loginUsuario['google'] ?? 0;
                if($idenRed != 0){
                  $rs = "google";
                }else{
                  $rs = "facebook";
                }

                $respuesta = Arreglar::usuario($usuario, "id_usuario", $rs);
                http_response_code(201);
                echo json_encode($respuesta);
                return;

              }else{ 

                if(isset($loginUsuario['googleId'])){
                  //Validamos la accion de actualizar
                    if($resulAvatar = Usuario::actualizarAvatar($loginUsuario, "id_google", "img_google", "googleId"));
                    else return Validar::respuestasHttp($resulAvatar, 409);
                    //consultamos
                    $usuario = Ayudador::datos($loginUsuario['googleId'] ?? null, "usuarios", "id_google", "");
                    $respuesta = Arreglar::usuario($usuario, "id_usuario", "google");
                    http_response_code(200);
                    echo json_encode($respuesta);

                }elseif(isset($loginUsuario['facebookId'])){
                   //Validamos la accion de actualizar
                    if($resulAvatar = Usuario::actualizarAvatar($loginUsuario));
                    else return Validar::respuestasHttp($resulAvatar, 409); 
                    //consultamos
                    $usuario = Ayudador::datos($loginUsuario['facebookId'] ?? null, "usuarios", "id_facebook", "");
                    $respuesta = Arreglar::usuario($usuario, "id_usuario", "facebook");
                    http_response_code(200);
                    echo json_encode($respuesta);
    
                }
                
              }
          }  
      }
      
      public function registrarUsuario(){
        //validamos el metodo de envio (se espera POST)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        
        //recibimos los datos
        $usuario = $this->usuario;

        //Validamos los datos
        if($mensaje = Formulario::usuario($usuario)) return $mensaje;

        //registramos el usuario
        $resultadoCrearUsuario = Usuario::crear($usuario);

        //validamos el resultado y damos respuesta
        if ($resultadoCrearUsuario === true) {
          //Creamos las tablas de datos entrega y factura del usuario
          try {
            $datosUsuario = Usuario::show($usuario['email'], "correo");
            $usuario['id'] = $datosUsuario['usuario']['id'];
            Datoentrega::crear($usuario);
            Datofactura::crear($usuario);
            
            $respuesta = Arreglar::usuario($datosUsuario['usuario'], "id_usuario");

            //respuesta
            http_response_code(201);
            echo json_encode($respuesta);
          } catch (\Throwable $th) {
            echo $th;
          }
         
        }else{
          return Validar::respuestasHttp($resultadoCrearUsuario, 409);
        }



      }
      
      public function actualizarUsuario(){
        //validamos el metodo de envio (se espera PUT)
        if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "POST")) return $mensaje;
        //recibimos los datos
        $usuario = $this->usuario;
        $usuario['accion'] = "actualizar";
        //validamos los datos recibidos
        if($mensaje = Formulario::usuario($usuario)) return $mensaje;
        if($mensaje = Formulario::infoFactura($usuario['billingData'])) return $mensaje;
        if($mensaje = Formulario::infoEntrega($usuario['deliveryData'])) return $mensaje;
        

        //Procedemos a actualizar datos usuario
        $resultadoUsuario = Usuario::actualizar($usuario);
        $resultadoFactura = Datofactura::actualizar($usuario['billingData']);
        $resultadoEntrega = Datoentrega::actualizar($usuario['deliveryData']);

        if ($resultadoUsuario === true && 
            $resultadoFactura === true &&
            $resultadoEntrega === true) {
          return Validar::respuestasHttp("Actualización de datos satisfactoria", 200);
        }else {
          return Validar::respuestasHttp($resultadoUsuario ."-".$resultadoFactura ."-". $resultadoEntrega,409);
        }
      }

        //recuperar contraseña

        /**
         * fase 1
         * recibir correo
         * validar si existe
         * generar token de recuperacion
         * registrar token en el usuario
         * envio correo con el link
         * 
         * fase 2
         * recivimos el token del link generado
         * validamos
         * actualizamos contraseña
         *  
         */

         public function recuperarClave(){
          //validamos el metodo de envio (se espera PUT)
          if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
           //DATOS RECIBIDOS
            $recuperarClave = $this->usuario['user'];
            // print_r($recuperarClave);
            if(Ayudador::fila($recuperarClave['email'], "correo", "correo", "usuarios")){
              //generamos token
              $token = Ayudador::generarToken($recuperarClave['email']);
              //Actualizamos el token
              if(Usuario::actualizarToken($token, $recuperarClave['email']));
              else return Validar::respuestasHttp("El token no fue asignado no se puede proceder con la recuperación", 409);
              //Enviamos correo con el link(token)
                if(Correo::recuperarClave($recuperarClave['email'], $token)){          
                  http_response_code(200);
                }else{
                  return Validar::respuestasHttp("El servicio de correo fallo, vuelve a intentar mas tarde", 409);
                }

            }else{
             return Validar::respuestasHttp("Ingrese un correo valido", 409);
            }
         }

         public function actualizarClave(){
          //validamos el metodo de envio (se espera PUT)
          if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "PUT")) return $mensaje;
          //DATOS RECIBIDOS
          $recuperarClave = $this->usuario['user'];
            //consultamos datos usuario
            $usuario = Ayudador::fila($recuperarClave['email'], "correo", "*", "usuarios");
            //validamos el token
            if($usuario['token'] == $recuperarClave['token']){
              //Actualizamos clave
              if(Usuario::actualizarClave($recuperarClave['password'], $recuperarClave['email'])){
                  if(Usuario::actualizarToken("vacio", $recuperarClave['email'])) return Validar::respuestasHttp("Contraseña actualizada", 200);
                  else return Validar::respuestasHttp("La acción vaciar token fallo", 409);                   
              }else{
                return Validar::respuestasHttp("la clave no se actualizó", 409);
              }
            }else{ 
              return Validar::respuestasHttp("Este enlace ya caducó", 409);
            }
         }

         public function eliminarUsuario($idUser){
          //validamos el metodo de envio (se espera DELETE)
          if($mensaje = Validar::solicitudCorrecta($_SERVER["REQUEST_METHOD"], "DELETE")) return $mensaje;
          
          //obtenemos datos de usuario
          if(!$usuarioCorreo = Ayudador::getNombre($idUser,"usuarios", "correo")) 
          return Validar::respuestasHttp("Este id no existe", 409);
          //accion de eliminar
          $resultadoDeEliminarUsuario = Usuario::eliminar($idUser);
          if ($resultadoDeEliminarUsuario === true) {
            return Validar::respuestasHttp("el usuario de E-mail: ". $usuarioCorreo . " Fue eliminado correctamente", 200);
          }else {
            return Validar::respuestasHttp($resultadoDeEliminarUsuario, 409);
          }
         }

         
        
    }//cierre de la Objeto
    

 ?>
