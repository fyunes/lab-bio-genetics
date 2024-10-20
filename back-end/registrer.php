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
        header("Location: ../front-end/register.html?error=email_exists");
        exit();
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
            // Registro exitoso
            echo "Registro exitoso. Ahora puede iniciar sesión.";
            header("Location: ../front-end/loginUsuario.html");
            exit();
        } else {
            // Redirigir a register.html en caso de error de inserción
            header("Location: ../front-end/register.html?error=registration_failed");
            exit();
        }
    }
}
?>

