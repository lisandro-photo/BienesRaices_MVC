<?php

namespace Model;

class Vendedor extends ActiveRecord
{ // Con extends hereda las funciones de ActiveRecord
    protected static $tabla = 'vendedores';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono', 'email'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null; // valor por defecto NULL para la función guardar()
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
    }

    public function validar()
    {

        if (!$this->nombre) { //$this-> porque forma parte de la instancia (lineas 15 a 24)
            self::$errores[] = 'El nombre es obligatorio'; // self porque está como static en la linea 13
        }
        if (!$this->apellido) { //$this-> porque forma parte de la instancia (lineas 15 a 24)
            self::$errores[] = 'El apellido es obligatorio'; // self porque está como static en la linea 13
        }
        
        if (!$this->telefono) { //$this-> porque forma parte de la instancia (lineas 15 a 24)
            self::$errores[] = 'El teléfono es obligatorio'; // self porque está como static en la linea 13
        }
        if (!preg_match('/[0-9]{10}/', $this->telefono)) { // Expresión regular que valida el ingreso de un número telefónica de 10 dígitos del 0 al 9
            self::$errores[] = 'Formato de teléfono no Válido';
        }

        if (!$this->email) { //$this-> porque forma parte de la instancia (lineas 15 a 24)
            self::$errores[] = 'El E-Mail es obligatorio'; // self porque está como static en la linea 13
        }
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $this->email)) { // Expresión regular que valida el formato del E-Mail ingresado
            self::$errores[] = 'Formato de email no Válido';
        }
        
        return self::$errores;
    }
}
