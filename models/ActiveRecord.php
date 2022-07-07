<?php

namespace Model;

class ActiveRecord {

    //Base de Datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';
    
    // Errores o Validación
    protected static $errores = [];

    // Definir la conexión a la BD
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            // Para Actualizar el id NO tiene que estar en NULL
            $this->actualizar();
        } else {
            // En caso de que el id esté en NULL entonces se CREARÁ un nuevo registro
            $this->crear();
        }
    }

    public function crear()
    {
        // Sanitizar los datos para asegurarnos que nadie vaya a meter datos maliciosos en nuestra BD
        $atributos = $this->sanitizarDatos();

        //Insertar en la base de datos con la forma orientada a objetos
        $query = "INSERT INTO " . static::$tabla . " ( "; // Importante dejar el espacio después del paréntesis de apertura y antes de las comillas dobles 
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' "; // Importante dejar el espacio después de las comillas dobles y antes de los paréntesis
        $query .= join("', '", array_values($atributos)); //Importante las comillas dobles después del join y antes del array_values
        $query .= " ') ";
        //debuguear($query);
        $resultado = self::$db->query($query);

        // Mensaje de éxito o error
        if ($resultado) {
            //Redireccionar al usuario
            header('Location: /admin?resultado=1'); // ?resultado=1 es para que se genere el mensaje de creado correctamente
        }
    }

    public function actualizar()
    {
        // Sanitizar los datos para asegurarnos que nadie vaya a meter datos maliciosos en nuestra BD
        $atributos = $this->sanitizarDatos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla . "  SET ";
        $query .=  join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 ";

        $resultado = self::$db->query($query);

        if ($resultado) {
            //Redireccionar al usuario
            header('Location: /admin?resultado=2'); // ?resultado=2  genera el mensaje de actualizado correctamente ver index linea 48
        }
    }

    // Eliminar un registro
    public function eliminar()
    {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        if($resultado) {
            $this->borrarImagen();
            header('location: /admin?resultado=3');
        }
    }

    // Identificar y unir los atributos (columnas de datos) de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue; // Cuando vamos a crear un nuevo registro el id todavía no existe, por eso lo ignoramos con un if continue
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarDatos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) { // Foreach recorre cada uno de los atributos de un arreglo
            $sanitizado[$key] = self::$db->escape_string($value); // Una vez sanitizado el dato lo grabamos en la BD
        }
        return $sanitizado;
    }

    // Subida de archivos
    public function setImagen($imagen)
    {
        // Elimina la imagen previa
        if (!is_null($this->id)) {
            // Comprobar si existe el archivo
            $this->borrarImagen();
        }

        // Asignar al atributo de imagen el nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    // Eliminar el archivo
    public function borrarImagen() {
        // Comprobar si existe el archivo
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    // Validación
    public static function getErrores()
    {
        return static::$errores;
    }

    public function validar()
    {
        static::$errores = [];
        return static::$errores;
    }

    //Lista todas las propiedades
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtiene determinado número de registros. 
    public static function get($cantidad)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    public static function consultarSQL($query)
    {
        // Consultar la Base de Datos
        $resultado = self::$db->query($query);
        // Iterar los resultados
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        // Liberar la memoria
        $resultado->free();
        //Retornar los resultados
        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) { // si no está vacío entonces se ejecuta $this->$key = $value;
                $this->$key = $value;
            }
        }
    }
}