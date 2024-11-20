<?php
require 'classes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM obras_sociales WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "Obra social eliminada con éxito";
    } else {
        echo "Error al eliminar la obra social: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>