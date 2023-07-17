<?php
include 'bd/conexion.php';
//Obtener Sesion
$json = json_decode(file_get_contents('php://input'));
session_start(array($json->session_id));
$usuario=json_decode($_SESSION["gamerly_user"]);
//Obtener usuario
$req = mysqli_query($con,"SELECT * FROM usuarios WHERE id=$usuario->id_usuario");
$user = mysqli_fetch_assoc($req);
//Obtener Producto
$req = mysqli_query($con,"SELECT id FROM productos WHERE id=$json->product_id");
$prod = mysqli_fetch_column($req);
//Verificar si el usuario ya cuenta con un carrito asignado, caso contrario lo crea
$req = mysqli_query($con,"SELECT id FROM carrito WHERE fk_usuario=$usuario->id_usuario");
$carrito = mysqli_fetch_column($req);
if(!$carrito){
    $req = mysqli_query($con,"INSERT INTO carrito VALUES(NULL,$usuario->id_usuario)");
    $id_carrito = mysqli_insert_id($con);
}
//Registrar el producto en el carrito
$creq = mysqli_query($con,"INSERT INTO carrito_producto VALUES(NULL,$carrito,$prod,$json->cantidad)");
$carrito = mysqli_insert_id($con);
if(!$carrito){
    http_response_code(207);
    var_dump(mysqli_error($con));
    exit;
}
http_response_code(200);
exit;
?>