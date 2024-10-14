<?php
session_start();

class Auth {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0)
        {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password']))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                return true;
            }

            else
            {
                return false; // contraseña incorrecta
            }
        }
        else
        {
            return false; // usuario no encontrado 
        }
    }

    public function isloggedin()
    {
        return isset($_SESSION['user_id']);
    }
    public function logout()
    {
        session_unset();
        session_destroy();
    }
}