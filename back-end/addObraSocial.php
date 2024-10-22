<?php
// Incluir la conexión a la base de datos
require 'classes/db.php';

// Verificar que el formulario fue enviado correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar los datos para prevenir inyección SQL
    $nombre_obra_social = mysqli_real_escape_string($conn, $_POST['nombre_obra_social']);
    
    // Consulta SQL para insertar la obra social
    $sql = "INSERT INTO obras_sociales (nombre) VALUES ('$nombre_obra_social')";
    
    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conn, $sql)) {
        echo "Obra social agregada con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>
