<?php
require 'classes/db.php';
require 'classes/Auth.php';

$auth = new Auth($conn);


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$email = $_POST ['email'];
$password = $_POST['password'];

if ($auth->login($email, $password))
{
    header("location: dashboard.php");
    exit();
}
else
{
    echo "Usuario o contraseña incorrectos";
}
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>login</title>
    </head>
    <body>
        <h2>Iniciar sesion</h2>
        <form method = "POST" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesion</button>
        </form>
        <p>No tienes una cuenta? <a href="registrer.php">Registrate aqui</a>.</p>
    </body>
</html>