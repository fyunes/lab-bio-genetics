<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BioGenetics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="logo-container">
        <a href="loginUsuario.html">
            <img src="img/logo.png" alt="BioGenetics Logo">
        </a>
        <span class="brand-name">BioGenetics</span>
    </div>
</header>

<div class="container">
    <div class="register-box">
        <form method="POST" action="../back-end/registrer.php">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" name="sexo" placeholder="Sexo" required>
            <input type="text" name="dni" placeholder="DNI" required>
            <input type="date" name="fecha-nacimiento" placeholder="Fecha de nacimiento" required>
            <input type="tel" name="telefono" placeholder="Número de teléfono" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            
            <label for="obra-social">Seleccione su obra social</label>
            <select name="obra_social" id="obra-social" required>
                <option value="">Seleccione una obra social</option>
                <?php
                    require '../back-end/classes/db.php';

                    $sql = "SELECT id, nombre FROM obras_sociales";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay obras sociales disponibles</option>';
                    }

                    mysqli_close($conn);
                ?>
            </select>
            <button type="submit">REGISTRARSE</button>
        </form>

        <div class="login-link">
            ¿Ya está registrado? <a href="loginUsuario.html">Inicie sesión aquí</a>
        </div>
    </div>
</div>

<script src="scripts.js"></script>
</body>
</html>


<script src="scripts.js"></script>
</body>
</html>
