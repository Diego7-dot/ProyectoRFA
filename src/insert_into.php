<?php
include("../Config/conexion.php");
$con = Conexion();

// Verifica que la solicitud sea por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica que existan todos los campos necesarios
    if (isset($_POST["corUsu"], $_POST["nomUsu"], $_POST["fecNacUsu"], $_POST["genUsu"], $_POST["psw"])) {
        
        // Recoge y limpia los datos
        $correo = trim($_POST["corUsu"]);
        $nombre = trim($_POST["nomUsu"]);
        $fechaNacimiento = trim($_POST["fecNacUsu"]);
        $genero = trim($_POST["genUsu"]);
        $password = $_POST["psw"]; // No se usa trim para la contraseña

        // Validación: comprobar que ningún campo esté vacío
        if (empty($correo) || empty($nombre) || empty($fechaNacimiento) || empty($genero) || empty($password)) {
            die("Por favor, completa todos los campos.");
        }

        // Validación: formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            die("El correo electrónico no es válido.");
        }

        // Validación: género debe ser 'M' o 'F' o '
        if ($genero !== 'M' && $genero !== 'F' && $genero !== 'O') {
            die("El género debe ser 'M' o 'F' o 'O'.");
        }

        // Hashear la contraseña para almacenarla de forma segura
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Prepara la consulta para evitar inyección SQL (se especifican las columnas)
        $stmt = mysqli_prepare($con, "INSERT INTO usuario (corUsu, nomUsu, fecNacUsu, genUsu, psw) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error al preparar la consulta: " . mysqli_error($con));
        }

        // Asocia los parámetros a la consulta preparada
        mysqli_stmt_bind_param($stmt, "sssss", $correo, $nombre, $fechaNacimiento, $genero, $passwordHashed);

        // Ejecuta la consulta y verifica el resultado
        if (mysqli_stmt_execute($stmt)) {
         header("Location:../html/pagina_principal/registro_usuario.html");
        exit();

        } else {
            die("Error al insertar el registro: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Faltan campos en la solicitud.");
    }
} else {
    die("Método de solicitud no permitido.");
}
?>
