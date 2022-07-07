<?php

namespace Model;

class Admin extends ActiveRecord {
    // Base de datos
    protected static $tabla = 'usuarios'; // protected porque solo necesitamos acceder a ellos dentro de esta clase 
    protected static $columnasDB = ['id', 'email', 'password'];

    public $id; // Son public porque se accederemos a ellos en esta clase y también en la clase LoginController
    public $email;
    public $password;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
    }

    public function validar() {
        if(!$this->email) {
            self::$errores[] = 'El E-Mail es obligatorio';
        }
        if(!$this->password) {
            self::$errores[] = 'El Password es obligatorio';
        }

        return self::$errores;
    }

    public function existeUsuario() {
        // Revisar si un usuario existe o no
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";// Siempre es recomendable indicar el límite de 1 para los resultados que traiga el query.

        $resultado = self::$db->query($query);

        if(!$resultado->num_rows) {
            self::$errores[] = 'El Usuario no existe';
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado) {
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);
       
        if(!$autenticado) {
           self::$errores[] = 'El Password es incorrecto'; 
        }
        return $autenticado;
    }

    public function autenticar() {
        session_start();
        
        // Llenar el arreglo de session
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = true;

        header('Location: /admin');
    }

}