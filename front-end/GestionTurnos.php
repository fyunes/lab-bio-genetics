<?php
session_start();
require '../back-end/classes/db.php';

// Verificación de sesión
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
    
    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Función para eliminar turno
function eliminarTurno($conn, $turno_id) {
    $delete_query = "DELETE FROM turno WHERE ID_Turno = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $turno_id);
    
    if ($stmt->execute()) {
        return "Turno eliminado exitosamente";
    } else {
        return "Error al eliminar el turno";
    }
}

// Función para actualizar turno
function actualizarTurno($conn, $turno_id, $nuevo_estado, $nueva_fecha) {
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
        return "Turno actualizado exitosamente";
    } else {
        return "Error al actualizar el turno";
    }
}

// Manejo de eliminación de turno a través de AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_turno_id'])) {
    $turno_id = $_POST['eliminar_turno_id'];
    echo eliminarTurno($conn, $turno_id); // Enviar el mensaje de éxito/error
    exit(); // Detener el script para evitar que el código continúe ejecutándose
}

// Manejo de actualización de turno a través de AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_turno_id'])) {
    $turno_id = $_POST['actualizar_turno_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $nueva_fecha = isset($_POST['nueva_fecha']) ? $_POST['nueva_fecha'] : null;
    
    echo actualizarTurno($conn, $turno_id, $nuevo_estado, $nueva_fecha);
    exit();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #660066;
            margin: 0;
            padding: 0;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 3px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-eliminar:hover {
            background-color: #c82333;
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

        .btn {
            display: inline-block;
            background-color: #814b81;
            color: white;
            text-decoration: none;
            padding: 20px 50px;
            border-radius: 5px;
            margin-bottom: 150px;
            max-width: 120px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #660066;
        }
    </style>

</head>
<body>
    <div class="container">
        <h1>Gestión de Turnos</h1>

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
                <?php if (count($turnos) > 0): ?>
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
                            <form method="POST" class="form-actualizar-turno" data-turno-id="<?php echo $turno['ID_Turno']; ?>">
                                <input type="hidden" name="actualizar_turno_id" value="<?php echo $turno['ID_Turno']; ?>">
                                <input type="date" name="nueva_fecha" value="<?php echo date('Y-m-d', strtotime($turno['Fecha_turno'])); ?>">
                                <select name="nuevo_estado">
                                    <option value="Pendiente" <?php echo ($turno['Estado'] == 'Pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                    <option value="Confirmado" <?php echo ($turno['Estado'] == 'Confirmado' ? 'selected' : ''); ?>>Confirmado</option>
                                    <option value="Cancelado" <?php echo ($turno['Estado'] == 'Cancelado' ? 'selected' : ''); ?>>Cancelado</option>
                                </select>
                                <input type="submit" value="Actualizar">
                            </form>
                            <button class="btn-eliminar" onclick="eliminarTurno(<?php echo $turno['ID_Turno']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay turnos disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="admin.html" class="btn">Volver al panel</a>
    </div>

    <script>
        // Función para eliminar un turno con confirmación SweetAlert
        function eliminarTurno(turno_id) {
            Swal.fire({
                title: '¿Está seguro que desea eliminar este turno?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Llamada AJAX para eliminar el turno
                    $.ajax({
                        url: 'GestionTurnos.php',
                        type: 'POST',
                        data: {
                            eliminar_turno_id: turno_id
                        },
                        success: function(response) {
                            Swal.fire('Eliminado!', response, 'success')
                                .then(function() {
                                    location.reload(); // Recargar la página para actualizar la lista
                                });
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el turno', 'error');
                        }
                    });
                }
            });
        }

        // Función para actualizar el turno
        $(document).on('submit', '.form-actualizar-turno', function(e) {
            e.preventDefault();

            var form = $(this);
            var turno_id = form.data('turno-id');
            var nuevo_estado = form.find('select[name="nuevo_estado"]').val();
            var nueva_fecha = form.find('input[name="nueva_fecha"]').val();

            $.ajax({
                url: 'GestionTurnos.php',
                type: 'POST',
                data: {
                    actualizar_turno_id: turno_id,
                    nuevo_estado: nuevo_estado,
                    nueva_fecha: nueva_fecha
                },
                success: function(response) {
                    Swal.fire('Actualizado!', response, 'success')
                        .then(function() {
                            location.reload(); // Recargar la página para ver los cambios
                        });
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo actualizar el turno', 'error');
                }
            });
        });
    </script>
</body>
</html>
