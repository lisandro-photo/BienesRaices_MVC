<?php


$db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_BD']);
//$db = new mysqli('localhost', 'root', 'LICHA', 'bienes_raices');
$db->set_charset("utf8");

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    
    exit;
}


//function conectarDB() {
//    $db = mysqli_connect('localhost', 'root', 'LICHA', 'bienes_raices');
/**1 el servidor, 2 el usuario, 3 contraseña del usuario 4 la base de datos a la que vamos a conectar. Importante escribir correctamente el nombre de la base de datos a la que se conectará, al igual que los nombres de los demás datos */

//    if($db) {
//        echo "";

//    } else {
//        echo "Error no se pudo conectar";
//        exit;
//    }
//    //Exit va a hacer que las siguientes lineas no se ejecuten
//}

//<?php 

/**Conexion a la base de datos */
