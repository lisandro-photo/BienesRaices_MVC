<?php

namespace Model;

class Propiedad extends ActiveRecord { // Gracias a la herencia extends ActiveRecord la clase Propiedad está disponible 
    protected static $tabla = 'propiedades';
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];


    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;// valor por defecto NULL para la función guardar()
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function validar()
    {

        if (!$this->titulo) { //$this-> porque forma parte de la instancia (lineas 15 a 24)
            self::$errores[] = 'Debes añadir un titulo'; // self porque está como static en la linea 13
        }

        if (!$this->precio) {
            self::$errores[] = 'El precio es obligatorio';
        }

        if (strlen($this->descripcion) < 50) { //strlen controla que la cantidad de caracteres ingresados por el usuario sean mayores que 50 
            self::$errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
        }

        if (!$this->habitaciones) {
            self::$errores[] = 'El Número de habitaciones debe ser mayor o igual a 1';
        }

        if (!$this->wc) {
            self::$errores[] = 'El Número de wc debe ser mayor o igual a 1';
        }

        if (!$this->estacionamiento) {
            self::$errores[] = 'El Número de lugares de estacionamientos es obligatorio';
        }

        if (!$this->vendedorId) {
            self::$errores[] = 'Elige un vendedor';
        }

        if (!$this->imagen) {
            self::$errores[] = 'La imagen de la Propiedad es obligatoria';
        }

        return self::$errores;
    }
}

