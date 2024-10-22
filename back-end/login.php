<?php
require 'classes/db.php'; // Conexión a la base de datos
require 'classes/Auth.php'; // Clase de autenticación

$auth = new Auth($conn); // Instanciar la clase Auth

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Intentar iniciar sesión
    if ($auth->login($email, $password)) {
        if($email==="admin@admin.com"){
            header("Location: ../front-end/admin.html"); // Redirigir al dashboard en caso de éxito
        }else{
            header("Location: ../front-end/turnos.html"); // Redirigir al dashboard en caso de éxito
        }
    } else {
        // Redirigir a index.html si el login falla
        header("Location: ../front-end/loginUsuario.html?error=login_failed");
        exit();
    }
}
?>
