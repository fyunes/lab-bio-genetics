<?php
$conn = new mysqli("localhost", "root", "", "sistema_usuarios");

if ($conn->connect_error)
{
    die("Error en la conexion: " . $conn->connect_error);
}
?>