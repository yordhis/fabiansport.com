<?php
    namespace App\Models;

    use App\Models\Consultar;
    use App\Models\Editar;

    class Metodo 
    {
        public $nuevoNombre = 0;
        public $nombre = "nombre";
        var $pon = "token";

        function __construct()
        {
            if (function_exists("getNombreCodigo")) 
            {
               $this->nombre = getNombreCodigo() ?? "no esta definida";
            } 
            else 
            {
                $this->nombre =  $this->nombre;
            }
            
        }

        //Normalizar imagenes para el registro o actualizacion 
        
        public function normalizarImagen($files)
        {
            $images_name='';
            $elementos = count($files["name"]);

            // Normalizamos los nombres de las img
            if($elementos > 1):
                    for ($f=0; $f < $elementos; $f++): 
                    $images_name .= Metodo::imagenNombre($files["name"][$f]) . ",";
                    endfor;
                    #quitar la coma sobrante
                    return $send_img = substr($images_name,0,-1);  
                else:
                    return $send_img = Metodo::imagenNombre($files["name"][0]);
            endif;     
        }

        public function validarExistencia($datos)
        {
          if(Consultar::datosRelacion($datos['id_usuario'], $datos['id_producto'], "id_usuario", "id_producto", "favoritos", "id", "")):
          return true; else: return false; endif;
        }
        //GENERAR TOKEN
        public function generarToken($correo)
        {
          $token = md5(time().md5($correo));
          return $token;
        }

        //GENERAR CODIGO FACTURA
        public function codigo()
        {
          include (APP_PATH . 'config/database.php');
          $codigo;
          $sql = "SELECT MAX(codigo) FROM facturas ";
          $sentencia = $pdo->prepare($sql);
          $sentencia->execute();
          $codigo = $sentencia->fetch();
          
          if($codigo["MAX(codigo)"] > 0):
            $codigo = intval($codigo["MAX(codigo)"]) + 1;
          else:
            $codigo =  1310000;
          endif;

          return $codigo;
        }

        // cada metodo le pertece a una vista 
        //vista producto
        /**
         * @param nombre es el que necesitamos para comparar los nombre de las img
         */
        public function imagenNombre($nombre)
        {
            if ($nombre == '') {
               return false;
            } else {
                $img = explode('.', $nombre);
                $img[0]=rand(1, 10000).time();
                return $nuevoNombre = $img[0] . '.' . $img[1];
            }
            
                
        }
        //Documentacion "cambiarPredeterminado"
        /**
         * Esta funcion cambia todas las seldas del columna enviada en la 
         * @var campo y cabe destacar que la consulta que se realiza obtiene
         * los valores de @var campo y @var cId es decir campo id.
         * @var saltaId es el id de la moneda que se quiere colocar como pre-determinada
         * 
         */
        public function cambiarPredeterminado($tabla, $campo, $cId, $saltarId)
        {
            //Contador de vueltas
                $vueltas = 0;
                              
					//Consultamos todas las filas y retornando id y el campo X ()
                    $datoActuales = Consultar::consultaTodoCampo($tabla, $campo, $cId);
					//Obtenemos el total de filas
					$c = count($datoActuales);
			
                    foreach ($datoActuales as $datoActual) 
                    {
						$vueltas = $vueltas + 1;
                        
                        if ($datoActual[$cId] != $saltarId) 
                        {
                            if(Editar::modeloUno($datoActual[$cId], 0, $tabla,  $campo, $cId))
                            {
                                echo $vueltas;
                                if($vueltas == $c)
                                {
                                    return true;
                                }
                            }
                        }
                        else 
                        {
                            if($vueltas == $c)
                            {
                                return true;
                            }
                        }
					}		
				      

        }

        public function gestionMoneda($simbolo, $costo, $utilidad, $iva)
        {
            //consultamos los datos de la tabla moneda
            $moneda = Consultar::consultaTodo('monedas');

            //todas las conversiones se hacen a bolivares y la unica variacion  
            //es de bolivares a dolares
            //simbolo
            if($simbolo == "bs.")
            {
                $simb = "$";
            }
            else
            {
                $simb = "Bs.";
            }

            //calculo del costo 
            $costo = ($costo + (($utilidad/100)+1));

            //Calculo del costo mas IVA
            $costoMasIva = ($costo * (($iva / 100) + 1));

            switch ($simbolo) 
            {
                case '$':
                    $conversionCosto = $costo/$moneda[0]['tasa'];
                    $conversionTotal = $costoMasIva/$moneda[0]['tasa'];
                break;

                case 'bs.':
                        $conversionCosto = $costo/$moneda[1]['tasa'];
                        $conversionTotal = $costoMasIva/$moneda[1]['tasa'];
                break;
              
                case '€':
                        $conversionCosto = $costo/$moneda[2]['tasa'];
                        $conversionTotal = $costoMasIva/$moneda[2]['tasa'];
                break;

                case 'cop':
                        $conversionCosto = $costo/$moneda[3]['tasa'];
                        $conversionTotal = $costoMasIva/$moneda[3]['tasa'];
                break;
                
                default:
                   echo  "<h1>Simbolo no admitidos</h1>";
                break;
            }

            //creamos el arreglo que vamos retornar

            $arreglo = [
                "simbolo" => $simb,
                "costo" => $costo,
                "total" => $costoMasIva,
                "conversionCosto" => $conversionCosto,
                "conversionTotal" => $conversionTotal
            ];
            return $arreglo;
        }

        // Documentacion *Metodo para validar el costo*
        /**
         * Metodo para validar el costo, tomando que si el costo 
         * ingresado es mayor debe actualizar el costo de lo contrario
         * se deja el costo actual
         * 
         * los parametro a recibir son:
         * @param id sirve para ubicar el producto o objeto
         * @param inputCosto es el costo que se esta recibiendo del formulario
         * @param tabla DataBase
         * @param campoU Es el campo el cual me va permitir ubicar o comprar con el @param id
         * @param campoE Es el campo que se va a editar o actualizar
         */
        public function costoValidar($id, $inputCosto, $tabla, $campoU, $campoE)
        {
            $producto = Consultar::datos($id, $tabla, $campoU, 'singular');

            if ($inputCosto > $producto[$campoE]) 
            {
                if(Editar::modeloUno($id, $inputCosto, $tabla, $campoE, $campoU))
                {
                    return $inputCosto;
                }
                else
                {
                    echo "<script>alert('No actualizo el costo')</script>";
                }
                
            }
            else
            {
                return $producto['costo_proveedor'];
            }
        }

        // Documentacion *METODO PARA OBTENER DATO DE UN ELEMENTO*
        /**
         * parametro necesario:
         * @param id
         * @param objeto este parametro es el que se va a recorrer
         * @param campoU este es el nombre del campo que Ubicaremos
         * 
         * este metodo retorna todo el array de la posicion solicitada
         * ejemplo
         * @var array[objeto]
         */
        public function obtenerDato($id,$objetos, $campoU)
        {
            foreach ($objetos as $objeto) 
            {
                if ($id == $objeto[$campoU]) 
                {
                    return $objeto;
                }
            }

        }

        public function http_response_code($code = NULL) 
        {
          if (!function_exists('http_response_code')) 
          {
            function http_response_code($code = NULL) 
            {
    
                if ($code !== NULL) {
    
                    switch ($code) {
                        case 100: $text = 'Continue'; break;
                        case 101: $text = 'Switching Protocols'; break;
                        case 200: $text = 'OK'; break;
                        case 201: $text = 'Created'; break;
                        case 202: $text = 'Accepted'; break;
                        case 203: $text = 'Non-Authoritative Information'; break;
                        case 204: $text = 'No Content'; break;
                        case 205: $text = 'Reset Content'; break;
                        case 206: $text = 'Partial Content'; break;
                        case 300: $text = 'Multiple Choices'; break;
                        case 301: $text = 'Moved Permanently'; break;
                        case 302: $text = 'Moved Temporarily'; break;
                        case 303: $text = 'See Other'; break;
                        case 304: $text = 'Not Modified'; break;
                        case 305: $text = 'Use Proxy'; break;
                        case 400: $text = 'Bad Request'; break;
                        case 401: $text = 'Unauthorized'; break;
                        case 402: $text = 'Payment Required'; break;
                        case 403: $text = 'Forbidden'; break;
                        case 404: $text = 'Not Found'; break;
                        case 405: $text = 'Method Not Allowed'; break;
                        case 406: $text = 'Not Acceptable'; break;
                        case 407: $text = 'Proxy Authentication Required'; break;
                        case 408: $text = 'Request Time-out'; break;
                        case 409: $text = 'Conflict'; break;
                        case 410: $text = 'Gone'; break;
                        case 411: $text = 'Length Required'; break;
                        case 412: $text = 'Precondition Failed'; break;
                        case 413: $text = 'Request Entity Too Large'; break;
                        case 414: $text = 'Request-URI Too Large'; break;
                        case 415: $text = 'Unsupported Media Type'; break;
                        case 500: $text = 'Internal Server Error'; break;
                        case 501: $text = 'Not Implemented'; break;
                        case 502: $text = 'Bad Gateway'; break;
                        case 503: $text = 'Service Unavailable'; break;
                        case 504: $text = 'Gateway Time-out'; break;
                        case 505: $text = 'HTTP Version not supported'; break;
                        default:
                            exit('Unknown http status code "' . htmlentities($code) . '"');
                        break;
                    }
    
                    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
    
                    header($protocol . ' ' . $code . ' ' . $text);
    
                    $GLOBALS['http_response_code'] = $code;
    
                } else {
    
                    $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
    
                }
    
                return $code;
    
            }
          }
           

        }

        public function getNombreCodigo($codigo = NULL)
        {
            switch ($codigo) 
            {
                case 100: return $text = 'Continue'; break;
                case 101: return $text = 'Switching Protocols'; break;
                case 200: return $text = 'OK';   break;
                case 201: return $text = 'Created'; break;
                case 202: return $text = 'Accepted'; break;
                case 203: return $text = 'Non-Authoritative Information'; break;
                case 204: return $text = 'No Content'; break;
                case 205: return $text = 'Reset Content'; break;
                case 206: return $text = 'Partial Content'; break;
                case 300: return $text = 'Multiple Choices'; break;
                case 301: return $text = 'Moved Permanently'; break;
                case 302: return $text = 'Moved Temporarily'; break;
                case 303: return $text = 'See Other'; break;
                case 304: return $text = 'Not Modified'; break;
                case 305: return $text = 'Use Proxy'; break;
                case 400: return $text = 'Bad Request'; break;
                case 401: return $text = 'Unauthorized'; break;
                case 402: return $text = 'Payment Required'; break;
                case 403: return $text = 'Forbidden'; break;
                case 404: return $text = 'Not Found'; break;
                case 405: return $text = 'Method Not Allowed'; break;
                case 406: return $text = 'Not Acceptable'; break;
                case 407: return $text = 'Proxy Authentication Required'; break;
                case 408: return $text = 'Request Time-out'; break;
                case 409: return $text = 'Conflict'; break;
                case 410: return $text = 'Gone'; break;
                case 411: return $text = 'Length Required'; break;
                case 412: return $text = 'Precondition Failed'; break;
                case 413: return $text = 'Request Entity Too Large'; break;
                case 414: return $text = 'Request-URI Too Large'; break;
                case 415: return $text = 'Unsupported Media Type'; break;
                case 500: return $text = 'Internal Server Error'; break;
                case 501: return $text = 'Not Implemented'; break;
                case 502: return $text = 'Bad Gateway'; break;
                case 503: return $text = 'Service Unavailable'; break;
                case 504: return $text = 'Gateway Time-out'; break;
                case 505: return $text = 'HTTP Version not supported'; break;
                default:
                    //exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
               
            } 
        }

        public function getRespuesta($codigo)
        {
            if (http_response_code($codigo)) {
                $variables["respuesta_text"] = Metodo::getNombreCodigo($codigo);
                $variables["respuesta_codigo"] = $codigo;
                return $variables;
            } else {
                echo "no found responce";
            }
        }


        // Asignar informacion de un arreglo a otro
        public function arregloAarreglo($arreglo_1, $arreglo_2, $name_asoc, $class, $metodo)
        {
          $count = 0;
          foreach ($arreglos as $arreglo) {
            $arreglos[$count]["$name_asoc"] = $class->$metodo($arreglo['id']);
            $count++;
          }
          return $arreglos;
        }


         //Metodo para recorrer un arreglo y asignar datos en un arreglo por id
        public function recorrerAll($arreglos, $name_asoc, $class, $metodo)
        {
          $count = 0;
          foreach ($arreglos as $arreglo) {
            $arreglos[$count]["$name_asoc"] = $class->$metodo($arreglo['id']);
            $count++;
          }
          return $arreglos;
        }

        public function recorrerOne($arreglo, $name_asoc, $class, $metodo)
        {
            $arreglo["$name_asoc"] = $class->$metodo($arreglo['id']);
            return $arreglo;
        }
        
       


    }//cierre de clase

    //ejecutando pruebas http_response_code

    //echo "el nombre es: " . Metodo::getNombreCodigo(204) . PHP_EOL;
    //echo "la respuesta es la siguiente: ";
    //var_dump(Metodo::getRespuesta(204));
    //echo PHP_EOL;

?>
