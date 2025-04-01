<?php
    if (isset($_POST['nombre']) && isset($_POST['edad']) && isset($_POST['correo'])) {
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $correo = $_POST['correo'];

        // Validaciones
        if  (!empty($nombre) && !empty($edad)  && !empty($correo)) {
            if (!is_numeric($edad)) {
                echo "Alerta!!!! La edad debe de ser un número";
            } else {
                echo "<h3>Datos recibidos correctamente</h3>";
                echo "El nombre $nombre es válido!!!<br>";
                echo "La edad $edad es válida!!! <br>";
                echo "El correo $correo es válido!!! <br>";
            }
        } else {
            echo "Alerta!!!! No puedes dejar campos vacíos";
        }
    } else {
            echo "Alerta!!!! Define la variable";
    }
    echo "<br>";
    echo "<form action='index.html' method='GET'>";
    echo "<button>Volver al formulario</button>";
    echo "</form>";
?>