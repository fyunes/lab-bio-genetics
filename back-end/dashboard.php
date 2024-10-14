<?php 
require 'classes/db.php';
require 'classes/Auth.php';

$auth = new Auth($conn);

if(!$auth->isloggedin())
{
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
    </head>
    <body>
        <h1>Laboratorio Bio Genetics</h1>
        <h2>Bienvenido <?php echo $_SESSION['user_name'];?>!</h2>

        <label for="especialidades">Especialidades:</label>
        <select id="especialdades" onchange="handleSelection(this)">
            <option value="">Seleccione</option>
            <option value="extracciones">Extracciones</option>
            <option value="analisis">Analisis</option>
        </select>

        <div id="resultado" style="margin-top: 20px;"></div>

        <script>
            function handleSelection(selectElement)
            {
                const resultadoDiv = document.getElementById('resultado');
                const selectedValue = selectElement.value;

                if(selectedValue === "extracciones")
                {
                    resultadoDiv.innerHTML = "<h3>Has seleccionado Extracciones.</h3>";
                }
                else if (selectedValue === "analisis")
                {
                    resultadoDiv.innerHTML = "<h3>Has seleccionado Analisis.</h3>";
                }
                else 
                {
                    resultadoDiv.innerHTML = ""; // si no se selecciona nada queda en blanco
                }
            }
        </script>
        <a href="logout.php">Cerrar Sesion</a>
    </body>
</html>

