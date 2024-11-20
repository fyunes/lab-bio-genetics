<?php
// alta-complejidad.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../back-end/login.php");
    exit;
}

require '../back-end/classes/db.php';

// Validar y obtener la práctica seleccionada
$practica_id = isset($_GET['practica_id']) ? (int)$_GET['practica_id'] : 0;

// Verificar que la práctica existe y es de alta complejidad
$sql = "SELECT ID_Practica, TipoPractica FROM practicas WHERE ID_Practica = ? AND ID_Practica <= 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $practica_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: selector-alta-complejidad.php");
    exit;
}

$practica = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Complejidad - BioGenetics</title>
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
        <h1>Alta Complejidad - <?php echo htmlspecialchars($practica['TipoPractica']); ?></h1>
        <div class="form-container">
            <h2>Agende su turno</h2>
            <form id="appointment-form" method="POST" action="agendarTurno.php" enctype="multipart/form-data">
                <input type="hidden" name="practica_id" value="<?php echo $practica_id; ?>">
                
                <div class="form-group">
                    <label for="fecha_turno">Fecha del turno:</label>
                    <input type="date" id="fecha_turno" name="fecha_turno" required 
                           min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group file-upload">
                    <label for="orden_medica">Orden Médica (PDF, JPG o PNG):</label>
                    <input type="file" id="orden_medica" name="orden_medica" 
                           accept=".pdf,.jpg,.png" required>
                </div>
                
                <button type="submit">AGENDAR TURNO</button>
            </form>
            <div id="mensaje"></div>
        </div>
    </main>

    <script>
    document.getElementById('appointment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
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
                setTimeout(() => {
                    window.location.href = 'index.html';  
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('mensaje').innerHTML = 
                `<div class="error">Ocurrió un error al procesar su solicitud</div>`;
        });
    });
    </script>
</body>
</html>