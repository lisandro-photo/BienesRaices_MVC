<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {
    public static function index(Router $router) {

        $propiedades = Propiedad::all();

        $vendedores = Vendedor::all();

        $resultado = $_GET['resultado'] ?? null; //Muestra mensaje condicional Trabaja con ?resultado=1 del archivo crear.php --?? null hace que ya no tenga errores el crud

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
    }//El arreglo asociativo es el que conecta con las $$key de Router.php, donde 'propiedades' es el key. Trae las vistas

    public static function crear(Router $router) {
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();// Arreglo con mensajes de errores
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** Crea una nueva instancia */
            $propiedad = new Propiedad($_POST['propiedad']);
        
            /** SUBIDA DE ARCHIVOS */
        
            //Generar un nombre único para la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        
            // Setear la imagen
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                // Realiza un Resize a la imagen con intervention
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600); // tmp_name almacena temporalmente en memoria del servidor. Fit va a   recortar la imagen en 800 x 600 para aligerarla
                $propiedad->setImagen($nombreImagen);
            }
        
            // Validar
            $errores = $propiedad->validar();
            
            if (empty($errores)) {
        
                //Crear la carpeta para subir imagenes
                if (!is_dir(CARPETA_IMAGENES)) { //is_dir verifica si la carpeta existe o no. Con "!" negamos la condición
                    mkdir(CARPETA_IMAGENES); //Si no existe la carpeta, mkdir crea una carpeta
                }
        
                //Guarda la imagen en el servidor
                $image->save(CARPETA_IMAGENES . $nombreImagen);//CARPETA_IMAGENES es una constante definida en funciones.php
        
                //Guarda en la Base de Datos
                $propiedad->guardar(); // El símbolo -> indica una función
            }
        }   
        
        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }
    
    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();

        // Método POST para actualizar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Asignar los atributos
            $args = $_POST['propiedad'];
           
            $propiedad->sincronizar($args);
    
            // Validación
            $errores = $propiedad->validar();
    
            // Subida de archivos
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";//Generar un nombre único para la imagen
            
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                // Realiza un Resize a la imagen con intervention
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600); // tmp_name almacena temporalmente en memoria del servidor. Fit va a   recortar la imagen en 800 x 600 para aligerarla
                $propiedad->setImagen($nombreImagen);
            }
          
            //Revisar que el array de errores este vacío
            if (empty ($errores)) {
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    // almacenar la imagen
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar(); 
            }
        }

        // Router para la vista
        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
        
            if ($id) {
                $tipo = $_POST['tipo'];
                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }
}