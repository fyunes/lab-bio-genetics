<?php
// selector-alta-complejidad.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../back-end/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioGenetics - Alta Complejidad</title>
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

    <section class="main-section">
        <h1>Alta Complejidad</h1>
        <h2>Escoja la práctica</h2>

        <div class="practices">
            <div class="option" onclick="redirectTo(1)" data-value="citogenetica">Citogenética</div>
            <div class="option" onclick="redirectTo(2)" data-value="diagnostico-prenatal">Diagnóstico prenatal</div>
            <div class="option" onclick="redirectTo(3)" data-value="fish">Hibridación In Situ (FISH)</div>
            <div class="option" onclick="redirectTo(4)" data-value="biologia-molecular">Biología Molecular</div>
            <div class="option" onclick="redirectTo(5)" data-value="citometria-flujo">Citometría de flujo</div>
        </div>
    </section>

    <script>
    function redirectTo(practicaId) {
        window.location.href = `alta-complejidad.php?practica_id=${practicaId}`;
    }
    </script>
</body>
</html>