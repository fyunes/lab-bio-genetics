<?php
$conn = new mysqli("localhost", "root", "", "laboratorio_medico");

if ($conn->connect_error)
{
    die("Error en la conexion: " . $conn->connect_error);
}
?>