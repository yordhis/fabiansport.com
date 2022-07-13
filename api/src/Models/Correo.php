<?php

namespace App\Models;
//soporte@fabiansport.com
class Correo
{
    static function compra($factura) {

        $cabeceras = "From: Fabian Sport <soporte@fabiansport.com>\n" //La persona que envia el correo
        . "Reply-To: soporte@fabiansport.com\n";
        $cabeceras  .= "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-type: text/html; charset=utf-8\r\n";
        //envio al cliente
        $asunto_client = "Felicidades por tu compra {$factura['name']}"; //El asunto
        $email_client = "{$factura['email']}"; //destino
        //envio al administrador
        $asunto_bussine = "{$factura['name']} realizo una compra";//asunto
        $email_bussine = "soporte@fabiansport.com, fabiansportpe@gmail.com"; //destino empresa
        
        $contenidoBodyClient = "
        <html>
        <body>
            <p>
            ¡Hola {$factura['name']}!<br>
            Le confirmamos que hemos recibido su compra. En breve nos comunicaremos con usted brindándole respuesta a su solicitud.
            </p><br>

            <ul>
                <li>Codigo de compra: {$factura['code']}</li>
                <li>Tipo de pago: {$factura['typePayment']}</li>
                <li>Titular: {$factura['titular']}</li>
                <li>Fecha: {$factura['date']}</li>
                <li>Monto S/: {$factura['mount']}</li>
                <li>Monto USD/: {$factura['mountUSD']}</li>
                <li>Referencia de pago: {$factura['refPayment']}</li>
                <li>Correo: {$factura['email']}</li>
            </ul><br>
            
            <a href='https://www.fabiansport.com/login'>
                Ingresa aquí para ver los detalles de tu compra: www.fabiansport.com
            </a><br>

                <p>
                    Gracias por preferirnos.
                </p><br>
    
                        
        </body>
        </html>
        ";
        
        $contenidoBodyBussine = "
        <html>
        <body>
            <h1>
            ¡Has realizado una venta!<br>
            </h1><br>

            <h3>Datos de la venta</h3>
            <br>
            <ul>
                <li>Nombre: {$factura['titular']}</li>
                <li>Codigo de venta: {$factura['code']}</li>
                <li>Tipo de pago: {$factura['typePayment']}</li>
                <li>Fecha: {$factura['date']}</li>
                <li>Monto S/: {$factura['mount']}</li>
                <li>Monto USD/: {$factura['mountUSD']}</li>
                <li>Referencia de pago: {$factura['refPayment']}</li>
                <li>Correo: {$factura['email']}</li>
            </ul><br>
    
            <a href='https://www.fabiansport.com'>
                Has clic aquí para ingresar al sistema y ver tus ventas.
            </a><br>
                        
        </body>
        </html>
        ";

        //Enviamos el mensaje y comprobamos el resultado
       
           $resultadoAdmin = @mail($email_bussine, $asunto_bussine ,$contenidoBodyBussine ,$cabeceras);
           $resultadoCliente = @mail($email_client, $asunto_client ,$contenidoBodyClient ,$cabeceras);
        
           if (!$resultadoCliente) {
            return "El correo del cliente no se envio"; 
        
        }elseif(!$resultadoAdmin) {
            return "El correo del administrador no se envio"; 
        
        }else {
            return true;
        }
    }    
    
    static function recuperarClave($email, $token) {

            $cabeceras = "From: Fabian Sport <soporte@fabiansport.com>\n" //La persona que envia el correo
            . "Reply-To: soporte@fabiansport.com\n";
            $cabeceras  .= "MIME-Version: 1.0\r\n";
            $cabeceras .= "Content-type: text/html; charset=utf-8\r\n";
            $asunto_client = "Recuperación de clave"; //El asunto
            $email_client = "$email"; //cambiar cliente
            
          
            $contenidoBodyClient = "
            <html>
                <body>
    
                    <p> Para recuperar su clave haga clic en el siguiente enlace. </p>
                    <br><br>
                        
                    <a href=\"https://www.fabiansport.com/ingresar-clave?token=$token&email=$email\">
                        https://www.fabiansport.com/ingresar-clave?token=$token&email=$email
                    </a>
                      
                   
                </body>
            </html>
            ";
    
            //Enviamos el mensaje y comprobamos el resultado
            if (@mail($email_client, $asunto_client ,$contenidoBodyClient ,$cabeceras)) {
                return true;
            } else {
                return false;
            }
    }    
    
}
