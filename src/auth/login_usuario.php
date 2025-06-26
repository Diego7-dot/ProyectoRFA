<?php
// DI = Inicio de sesion usuarios
session_start();

// DI = Conexion a la bd
include(__DIR__ . "/../../Config/conexion.php");
$con = Conexion();

// KM = Si se solicita la acción de cerrar sesión (logout) mediante un parámetro GET, se ejecuta la función de logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // KM= limina todas las variables de sesión
    $_SESSION = array();

    // KM = Si se utilizan cookies para la sesión, se destruye la cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // KM = Destruye la sesión
    session_destroy();

    // KM = Redirige al usuario al login (según el snippet proporcionado)
    header("Location: http://localhost/Proyecto_real/src/login_usuario.php");
    exit();
}

// KM = Si la solicitud es de tipo POST se asume que se intenta iniciar sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // KM = Comprueba que se hayan enviado los campos 'corUsu' y 'psw'
    if (isset($_POST['corUsu'], $_POST['psw'])) {
        // KM = Elimina espacios en blanco y asigna el valor del campo 'corUsu' a la variable $email
        $email = trim($_POST['corUsu']);
        // KM = Elimina espacios en blanco y asigna el valor del campo 'psw' a la variable $password
        $password = trim($_POST['psw']);

        // KM = Verifica que ninguno de los campos esté vacío
        if (empty($email) || empty($password)) {
            header("Location: ../../html/pagina_principal/inicio_sesion_usuario.php?error=campos_vacios");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../../html/pagina_principal/inicio_sesion_usuario.php?error=credenciales_invalidas");
            exit();
        }

        // KM = Define la consulta SQL para obtener el usuario por su correo
        $query = "SELECT * FROM usuario WHERE corUsu = ?";
        // KM = Prepara la consulta SQL
        if ($stmt = $con->prepare($query)) {
            // KM = Vincula el parámetro $email a la consulta preparada
            $stmt->bind_param('s', $email);
            // KM = Ejecuta la consulta
            $stmt->execute();
            // KM = Obtiene el resultado de la consulta
            $result = $stmt->get_result();

            // KM =  Verifica si se encontró algún registro (usuario)
            if ($result->num_rows > 0) {
                // KM = Obtiene la fila de datos del usuario como un arreglo asociativo
                $row = $result->fetch_assoc();
                // KM = Verifica si la contraseña proporcionada coincide con el hash almacenado en la base de datos
                if (password_verify($password, $row['psw'])) {
                    session_regenerate_id(true);
                    // KM = Guardar datos del usuario en la sesión
                    $_SESSION['corUsu'] = $row['corUsu'];
                    $_SESSION['nomUsu'] = $row['nomUsu'];
                    $_SESSION['genUsu'] = $row['genUsu'];

                    // KM = Trae la imagen de foto de perfil
                    $_SESSION['fotoPerfil'] = !empty($row['fotoPerfil'])
                        ? $row['fotoPerfil']
                        : match ($row['genUsu']) {
                            'F' => 'icono_default_mujer.png',
                            'M' => 'icono_default_hombre.png',
                            'O' => 'icono_default_otro.png',
                            default => 'icono_default_otro.png'
                        };

                    // KM = Consulta para que me guarde los inicios de sesion
                    $insertLog = $con->prepare("INSERT INTO loginUsuario (corUsuLogUsu, fecLogUsu) VALUES (?, NOW())");
                    $insertLog->bind_param('s', $row['corUsu']);
                    $insertLog->execute();
                    $insertLog->close();

                    // KM  = Redirigir al usuario autenticado
                    header("Location: http://localhost/Proyecto_real_v1/Proyecto_real/html/pagina_usuario/inicio_usuario.php");
                    exit();
                } else {
                    // KM = Si la contraseña es incorrecta, redirige al inicio de sesión con un error
                    header("Location: ../../html/pagina_principal/inicio_sesion_usuario.php?error=credenciales_invalidas&correo=" . urlencode($email));
                    exit();
                }
            }
        }
    }
}
// KM = Cerrar las verificaciones
if (isset($stmt)) $stmt->close();
if (isset($con)) $con->close();
