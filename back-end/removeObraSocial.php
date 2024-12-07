<?php
require 'classes/db.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM obras_sociales WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        // Si la eliminación fue exitosa
        $response['status'] = 'success';
        $response['message'] = 'Obra social eliminada con éxito';
    } else {
        // Si hubo un error
        $response['status'] = 'error';
        $response['message'] = 'Error al eliminar la obra social: ' . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

// Devolver la respuesta como JSON
echo json_encode($response);
?>
