<?php
session_start(); // Iniciar sesión para manejar mensajes
require 'classes/db.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $password = trim($_POST['password']);
    $sexo = trim($_POST['sexo']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = trim($_POST['fecha-nacimiento']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $obra_social = trim($_POST['obra_social']); // Este será el ID de la obra social

    // Verificar que los campos no estén vacíos
    if (empty($nombre) || empty($apellido) || empty($password) || empty($sexo) || empty($dni) || empty($fecha_nacimiento) || empty($telefono) || empty($email) || empty($obra_social)) {
        $_SESSION['error'] = "Por favor complete todos los campos.";
        header("Location: ../front-end/register.html?error=empty_fields");
        exit();
    }

    // Verificar si el correo ya está registrado
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El correo ya está registrado
        $_SESSION['error'] = "El correo ya está registrado. Intente con otro.";
        header("Location: ../front-end/register.html?error=email_exists");
        exit();
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar los datos del usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellido, password, sexo, dni, fecha_nacimiento, telefono, email, obra_social_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssss", $nombre, $apellido, $hashed_password, $sexo, $dni, $fecha_nacimiento, $telefono, $email, $obra_social);

        if ($stmt->execute()) {
            // Registro exitoso, redirigir al login
            $_SESSION['success'] = "Registro exitoso. Ahora puede iniciar sesión.";
            header("Location: ../front-end/loginUsuario.html");
            exit();
        } else {
            // Error en la inserción, redirigir al registro
            $_SESSION['error'] = "Hubo un error al registrar el usuario. Inténtelo de nuevo.";
            header("Location: ../front-end/register.html?error=registration_failed");
            exit();
        }
    }
    
    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>


