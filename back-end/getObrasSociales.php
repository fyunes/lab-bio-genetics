<?php
require 'classes/db.php';

$sql = "SELECT id, nombre FROM obras_sociales";
$result = mysqli_query($conn, $sql);

$obrasSociales = [];
while ($row = mysqli_fetch_assoc($result)) {
    $obrasSociales[] = $row;
}

echo json_encode($obrasSociales);

mysqli_close($conn);
?>