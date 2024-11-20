<?php
session_start();
require_once '../back-end/classes/db.php';

class TurnoManager {
    private $conn;
    private $errors = [];
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function validarFechaTurno($fecha) {
        $fecha_actual = date('Y-m-d');
        return $fecha >= $fecha_actual;
    }
    
    public function validarPaciente($id_paciente) {
        $stmt = $this->conn->prepare("SELECT ID_Paciente FROM paciente WHERE ID_Paciente = ?");
        if (!$stmt) {
            $this->errors[] = "Error en la preparación de la consulta: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("i", $id_paciente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function obtenerIdPaciente($id_usuario) {
        // Primero obtenemos los datos del usuario
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        if (!$stmt) {
            $this->errors[] = "Error en la preparación de la consulta: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        
        if (!$usuario) {
            $this->errors[] = "Usuario no encontrado";
            return false;
        }
        
        // Verificamos si ya existe un paciente con ese DNI
        $stmt = $this->conn->prepare("SELECT ID_Paciente FROM paciente WHERE NumDoc = ?");
        $stmt->bind_param("s", $usuario['dni']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Si existe, retornamos su ID
            $paciente = $result->fetch_assoc();
            return $paciente['ID_Paciente'];
        } else {
            // Si no existe, creamos un nuevo paciente
            $stmt = $this->conn->prepare("INSERT INTO paciente (Nombre, Apellido, NumDoc, Correo, Usuario, NumCel, ObraSocial) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", 
                $usuario['nombre'],
                $usuario['apellido'],
                $usuario['dni'],
                $usuario['email'],
                $usuario['nombre'], // Usuario será el nombre
                $usuario['telefono'],
                $usuario['obra_social_id']
            );
            
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            } else {
                $this->errors[] = "Error al crear el paciente: " . $stmt->error;
                return false;
            }
        }
    }
    
    public function validarPractica($practica_id) {
        $stmt = $this->conn->prepare("SELECT ID_Practica FROM practicas WHERE ID_Practica = ?");
        if (!$stmt) {
            $this->errors[] = "Error en la preparación de la consulta: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("i", $practica_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function guardarOrdenMedica($file) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $this->errors[] = "No se recibió ningún archivo";
            return false;
        }

        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $this->errors[] = "Error al crear el directorio de uploads";
                return false;
            }
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowed_types)) {
            $this->errors[] = "Tipo de archivo no permitido. Solo se permiten PDF, JPG y PNG";
            return false;
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            $this->errors[] = "Error al mover el archivo subido";
            return false;
        }
        
        return $target_file;
    }
    
    public function agendarTurno($fecha_turno, $id_usuario, $practica_id, $orden_medica) {
        try {
            $this->conn->begin_transaction();
            
            $id_paciente = $this->obtenerIdPaciente($id_usuario);
            if (!$id_paciente) {
                $this->conn->rollback();
                return false;
            }
            
            $stmt = $this->conn->prepare(
                "INSERT INTO turno (Fecha_turno, Estado, ID_Paciente, ID_Practica, OrdenMedica) 
                 VALUES (?, 'Pendiente', ?, ?, ?)"
            );
            
            if (!$stmt) {
                $this->conn->rollback();
                $this->errors[] = "Error en la preparación de la consulta: " . $this->conn->error;
                return false;
            }
            
            $stmt->bind_param("siis", $fecha_turno, $id_paciente, $practica_id, $orden_medica);
            
            if (!$stmt->execute()) {
                $this->conn->rollback();
                $this->errors[] = "Error al ejecutar la consulta: " . $stmt->error;
                return false;
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            $this->errors[] = "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

// Modificación del archivo agendarTurno.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Debe iniciar sesión para agendar un turno']);
        exit;
    }

    if (!isset($_POST['practica_id']) || !isset($_POST['fecha_turno']) || !isset($_FILES['orden_medica'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Faltan datos requeridos']);
        exit;
    }

    $turnoManager = new TurnoManager($conn);

    $practica_id = (int)$_POST['practica_id'];
    $fecha_turno = $_POST['fecha_turno'];

    if (!$turnoManager->validarFechaTurno($fecha_turno)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'La fecha del turno debe ser igual o posterior a la actual']);
        exit;
    }

    if (!$turnoManager->validarPractica($practica_id)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Práctica no válida']);
        exit;
    }

    $orden_medica = $turnoManager->guardarOrdenMedica($_FILES['orden_medica']);
    if (!$orden_medica) {
        header('Content-Type: application/json');
        echo json_encode(['error' => implode(', ', $turnoManager->getErrors())]);
        exit;
    }

    if ($turnoManager->agendarTurno($fecha_turno, $_SESSION['user_id'], $practica_id, $orden_medica)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => 'Turno agendado exitosamente']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => implode(', ', $turnoManager->getErrors())]);
    }
}
?>