<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../back-end/login.php");
    exit;
}

require '../back-end/classes/db.php';

// Obtener la información de la práctica de análisis bioquímicos
$sql = "SELECT ID_Practica, TipoPractica, Descripcion FROM practicas WHERE ID_Practica = 6";
$result = mysqli_query($conn, $sql);

if ($result->num_rows === 0) {
    die("Error: Práctica no encontrada");
}

$practica = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis Bioquímicos - BioGenetics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="img/logo.png" alt="BioGenetics Logo">
            <span class="brand-name">BioGenetics</span>
        </div>
        <nav>
            <div class="user-menu">
                <img src="img/icon.png" alt="User Icon" class="user-icon">
                <div class="dropdown-menu">
                    <a href="../back-end/logout.php" class="logout-btn">Cerrar Sesión</a>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="main-content">   
        <h1>Análisis Bioquímicos</h1>
        <div class="form-container">
            <h2>Agende su turno</h2>
            <form id="appointment-form" method="POST" action="agendarTurno.php" enctype="multipart/form-data">
                <input type="hidden" name="practica_id" value="6">
                
                <div class="form-group">
                    <label for="fecha_turno">Fecha del turno:</label>
                    <input type="date" 
                           id="fecha_turno" 
                           name="fecha_turno" 
                           required 
                           min="<?php echo date('Y-m-d'); ?>"
                           class="form-control">
                </div>
                
                <div class="form-group file-upload">
                    <label for="orden_medica">Orden Médica (PDF, JPG o PNG):</label>
                    <input type="file" 
                           id="orden_medica" 
                           name="orden_medica" 
                           accept=".pdf,.jpg,.png" 
                           required 
                           class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary">AGENDAR TURNO</button>
            </form>
            <div id="mensaje"></div>
        </div>
    </main>

    <script>
    document.getElementById('appointment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar fecha
        const fechaTurno = document.getElementById('fecha_turno').value;
        const hoy = new Date().toISOString().split('T')[0];
        
        if (fechaTurno < hoy) {
            document.getElementById('mensaje').innerHTML = 
                '<div class="error">La fecha del turno no puede ser anterior a hoy</div>';
            return;
        }
        
        // Validar archivo
        const ordenMedica = document.getElementById('orden_medica').files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (ordenMedica && ordenMedica.size > maxSize) {
            document.getElementById('mensaje').innerHTML = 
                '<div class="error">El archivo es demasiado grande. El tamaño máximo es 5MB.</div>';
            return;
        }
        
        let formData = new FormData(this);
        
        // Mostrar indicador de carga
        document.getElementById('mensaje').innerHTML = 
            '<div class="info">Procesando su solicitud...</div>';
        
        fetch('agendarTurno.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let mensaje = document.getElementById('mensaje');
            if (data.error) {
                mensaje.innerHTML = `<div class="error">${data.error}</div>`;
            } else if (data.success) {
                mensaje.innerHTML = `<div class="success">${data.success}</div>`;
                // Deshabilitar el formulario después de un envío exitoso
                document.getElementById('appointment-form').reset();
                document.querySelector('button[type="submit"]').disabled = true;
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('mensaje').innerHTML = 
                '<div class="error">Ocurrió un error al procesar su solicitud. Por favor, intente nuevamente.</div>';
        });
    });
    </script>
</body>
</html>