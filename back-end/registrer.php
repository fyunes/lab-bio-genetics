<?php
require 'classes/db.php';
require 'classes/Auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //verificar si el correo ya esta registrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        echo "El correo ya esta registrado. Intente con otro.";
    }
    else
    {
        //encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //insertar usuario en la base de datos 
        $query = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $nombre, $email, $hashed_password);

        if ($stmt->execute())
        {
            echo "Registro exitoso. Ahora puede iniciar sesion.";
            header("location: login.php");
            exit();
        }
        else
        {
            echo "Error en el registro: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
    </head>
    <body>
        <h2>Registrarse</h2>
        <form method="POST" action="registrer.php">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <p>Ya tienes una cuenta? <a href="login.php">Inicia sesion aqui</a>.</p>
    </body>
</html>