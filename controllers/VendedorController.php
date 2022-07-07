<?php

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedorController {
    public static function crear(Router $router) {
       
        $errores = Vendedor::getErrores();

        $vendedor = new Vendedor;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Crear una nueva instancia
            $vendedor = new Vendedor($_POST['vendedor']);
            
            // Validar que no haya campos vacios
            $errores = $vendedor->validar();
            
            // No hay errores
            if (empty($errores)) {
                //Guarda en la Base de Datos
                $vendedor->guardar(); 
            }
        }

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function actualizar(Router $router) {
        $errores = Vendedor::getErrores();
        $id = validarORedireccionar('/admin');
        // Obtener datos del vendedor a actualizar
        $vendedor = Vendedor::find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Asignar los valores
            $args = $_POST['vendedor'];
        
            // Sincronizar el objeto en memoria con lo que el usuario escribió
            $vendedor->sincronizar($args);
            
            // Validación
            $errores = $vendedor->validar();
        
            // No hay errores
            if (empty($errores)) {
                //Guarda en la Base de Datos
                $vendedor->guardar(); 
            }
        }
        

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    // En la función eliminar no se requiere (Router $router) porque $router->render se utiliza para las vistas y al eliminar no se accede a ninguna vista 
    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Valida el id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
                // Valida el tipo a eliminar
                $tipo = $_POST['tipo'];
                
                if(validarTipoContenido($tipo)) {
                    $vendedor = Vendedor::find($id);
                    $vendedor->eliminar();
                }
             }
        }
    }
}

