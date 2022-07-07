<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas()
    {
        session_start();
        
        $auth = $_SESSION['login'] ?? null;

        // Arreglo de rutas protegidas..
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];
        $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'];
        //$urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        $splitURL = explode('?', $urlActual);
        // debuguear($splitURL);

        
        if($metodo === 'GET') {
            $fn = $this->rutasGET[$splitURL[0]] ?? null;
        } else {
            $fn = $this->rutasPOST[$splitURL[0]] ?? null;
        }

        // Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('Location: /');
            //return;// Detiene la ejecución del código
        }

        if($fn) {
            //La URL existe y tiene una función asignada
            call_user_func($fn, $this);            
        } else {
            echo "Página no encontrada";
        }
    }

    // Muestra una vista
    public function render($view, $datos = [] ) {

        
        foreach($datos as $key => $value) {
            $$key = $value;// El doble signo pesos $$ significa variable de variable
        }

        ob_start(); // Inicia el Almacenamiento en memoria durante un momento...
        include  __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();// Limpia el buffer
        include __DIR__ . "/views/layout.php";
    }
}