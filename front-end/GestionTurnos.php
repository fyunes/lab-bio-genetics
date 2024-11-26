<?php
session_start();
require '../back-end/classes/db.php';

// Simple verificación de sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: loginUsuario.html");
    exit();
}

// Función para obtener todos los turnos con detalles
function obtenerTurnos($conn) {
    $query = "SELECT 
                t.ID_Turno, 
                t.Fecha_turno, 
                t.Estado, 
                p.Nombre AS NombrePaciente, 
                pr.TipoPractica,
                t.OrdenMedica
              FROM turno t
              JOIN paciente p ON t.ID_Paciente = p.ID_Paciente
              JOIN practicas pr ON t.ID_Practica = pr.ID_Practica
              ORDER BY t.Fecha_turno";
    
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiar_estado'])) {
    $turno_id = $_POST['turno_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $nueva_fecha = isset($_POST['nueva_fecha']) ? $_POST['nueva_fecha'] : null;

    if ($nueva_fecha) {
        $update_query = "UPDATE turno SET Estado = ?, Fecha_turno = ? WHERE ID_Turno = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $nuevo_estado, $nueva_fecha, $turno_id);

    } else {
        $update_query = "UPDATE turno SET Estado = ? WHERE ID_Turno = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $nuevo_estado, $turno_id);
    }
    
    if ($stmt->execute()) {
        $mensaje = "Turno actualizado exitosamente";
    } else {
        $mensaje = "Error al actualizar el turno";
    }
}

// Obtener turnos
$turnos = obtenerTurnos($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Turnos - BioGenetics</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #660066;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            height: 200px;
            margin: 20px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow-y: auto; 
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .turnos-tabla {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px; 
            margin-top: 10px;
        }
        .turnos-tabla th, .turnos-tabla td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .turnos-tabla th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }
        .estado-pendiente { color: orange; font-weight: bold; }
        .estado-confirmado { color: green; font-weight: bold; }
        .estado-cancelado { color: red; font-weight: bold; }
        .mensaje {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
        }
        form select, form input[type="submit"] {
            margin: 5px 0;
            font-size: 13px;
        }
        form select {
            padding: 3px;
        }
        form input[type="submit"] {
            padding: 3px 10px;
            cursor: pointer;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 3px;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        form input[type="date"] {
            width: 50%; 
            padding: 5px;
            font-size: 14px;
            margin: 5px 0;
        }

    .mensaje {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        color: #333;
        text-align: center;
        opacity: 1;  
       }
    </style>

</head>
<body>
    <div class="container">
        <h1>Gestión de Turnos</h1>
        
        <?php if (isset($mensaje)): ?>
            <div id="alerta" class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <table class="turnos-tabla">
            <thead>
                <tr>
                    <th>ID Turno</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Paciente</th>
                    <th>Práctica</th>
                    <th>Orden Médica</th>
                    <th>Acciones: Modificar Fecha y Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($turnos as $turno): ?>
                <tr>
                    <td><?php echo $turno['ID_Turno']; ?></td>
                    <td><?php echo $turno['Fecha_turno']; ?></td>
                    <td class="estado-<?php echo strtolower($turno['Estado']); ?>">
                        <?php echo $turno['Estado']; ?>
                    </td>
                    <td><?php echo $turno['NombrePaciente']; ?></td>
                    <td><?php echo $turno['TipoPractica']; ?></td>
                    <td>
                        <?php if($turno['OrdenMedica']): ?>
                            <a href="<?php echo $turno['OrdenMedica']; ?>" target="_blank">Ver Orden</a>
                        <?php endif; ?>
                    </td>
                    <td>
    <form method="POST">
        <input type="hidden" name="turno_id" value="<?php echo $turno['ID_Turno']; ?>">
        <input type="date" name="nueva_fecha" value="<?php echo date('Y-m-d', strtotime($turno['Fecha_turno'])); ?>">
        <select name="nuevo_estado">
            <option value="Pendiente" <?php echo ($turno['Estado'] == 'Pendiente' ? 'selected' : ''); ?>>Pendiente</option>
            <option value="Confirmado" <?php echo ($turno['Estado'] == 'Confirmado' ? 'selected' : ''); ?>>Confirmado</option>
            <option value="Cancelado" <?php echo ($turno['Estado'] == 'Cancelado' ? 'selected' : ''); ?>>Cancelado</option>
        </select>
        <input type="submit" name="cambiar_estado" value="Actualizar">
    </form>
</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>

        setTimeout(function() {
            var alerta = document.getElementById('alerta');
            if (alerta) {
                alerta.style.transition = 'opacity 2s ease';  
                alerta.style.opacity = '0';
            }
        }, 5000);

    </script>
</body>
</html>