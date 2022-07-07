<?php 


namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index( Router $router) {

        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }//$router->render es el método para mostrar una vista

    public static function nosotros( Router $router) {
        
        
        $router->render('paginas/nosotros');
    }

    public static function propiedades( Router $router ) {
        
        $propiedades = Propiedad::all();
                
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    
    public static function propiedad( Router $router) {
        $id = validarORedireccionar('/propiedades');
        // Buscar la propiedad por su id
        $propiedad = Propiedad::find($id);
        
        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog( Router $router ) {
        
        
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router ) {
        
        
        $router->render('/paginas/entrada');
    }
    
    public static function contacto( Router $router ) {

        $mensaje = null;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
           $respuestas = $_POST['contacto']; 
            
           // Crear una instancia de PHPMailer 
           $mail = new PHPMailer();// Aquí estamos creando un nuevo objeto

            // Configurar SMTP
            $mail->isSMTP();// La función para el envío
            $mail->Host = 'smtp.mailtrap.io'; //La dirección del host de mailtrap
            $mail->SMTPAuth = true;//Esto significa que nos tenemos que autenticar para que funcione
            $mail->Username = '491165c4ebfe6c'; // El usuario que nos asignó Mailtrap
            $mail->Password = '218ec7447d13dd'; // El password asignado a nuestro usuario de Mailtrap
            $mail->SMTPSecure = 'tls'; // Configuración para que los e-mails vayan por un canal seguro (tls transfer )
            $mail->Port = 2525;

            // Configurar el contenido del E-Mail
            $mail->setFrom('admin@bienesraices.com');// le ponemos admin para evitar que vaya a la bandeja de correos no deseados
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un Nuevo Mensaje';
            
            // Habilitar HTML
            $mail->isHTML(true);// Acá le decimos que el contenido es HTML
            $mail->CharSet = 'UTF-8'; // UTF-8 es para indicar que vamos a recibir contenido con acentos, o caracteres específicos del idioma español

            // Definir el contenido. Aquí definimos el código para ver en Mailtrap lo que el usuario escribió en el formulario de contacto
            $contenido = '<html>';
            $contenido .= '<p>Tienes un Nuevo Mensaje</p>';
            $contenido .= '<p>Nombre:  ' . $respuestas['nombre']  . '</p>';            
            
            // Enviar de forma condicional algunos campos de email o teléfono
            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligió ser contactado por Teléfono</p>';
                $contenido .= '<p>Teléfono:  ' . $respuestas['telefono']  . '</p>';
                $contenido .= '<p>Fecha que desea ser contactado:  ' . $respuestas['fecha']  . '</p>';
                $contenido .= '<p>Hora:  ' . $respuestas['hora']  . '</p>';
            }else {
                // Es E-Mail, entonces agregamos el campo de email
                $contenido .= '<p>Eligió ser contactado por E-Mail</p>';
                $contenido .= '<p>E-Mail:  ' . $respuestas['email']  . '</p>';
            }
           
            $contenido .= '<p>Mensaje:  ' . $respuestas['mensaje']  . '</p>';
            $contenido .= '<p>Vende o Compra:  ' . $respuestas['tipo']  . '</p>';
            $contenido .= '<p>Precio o Presupuesto:  $' . $respuestas['precio']  . '</p>';
            $contenido .= '<p>Prefiere ser contactado por:  ' . $respuestas['contacto']  . '</p>';            
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es Texto Alternativo sin HTML';

            //Enviar el E-Mail
            if($mail->send()) {
                $mensaje = "Mensaje enviado correctamente";
            }else {
                $mensaje = "El Mensaje No Se Pudo Enviar....";
            }


        }   
        
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}