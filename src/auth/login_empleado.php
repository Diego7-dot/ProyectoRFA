<?php
// KM = Verficia el iniciar sesion
session_start();

// KM = Conexion a la base de datos
require_once(__DIR__ . "/../../Config/conexion.php");
$con = Conexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['documento'], $_POST['codRolApo'], $_POST['password'])) {
        $documento = trim($_POST['documento']);
        $rol = intval($_POST['codRolApo']); // KM = Busca el rol como entero (Codigo rol)
        $password = trim($_POST['password']);

        if (empty($documento) || empty($rol) || empty($password)) {
            echo "Error: campos vacíos";
            exit();
        }

        // KM = Busca a la persona de apoyo en la tabla
        $sql = "SELECT p.*, r.nomRol 
        FROM personaApoyo p 
        JOIN rol r ON p.codRolApo = r.codRol 
        WHERE p.docApo = ? AND p.codRolApo = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $documento, $rol);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_object();

            // KM = Verifica si lq persona de apoyo esta activa (Se me habia olvidado jajaja)
            if (!$row->estadoApo) {
                // KM = Obtener el motivo de la desactivacion en el historial
                $sqlHistorial = "SELECT motivoCambio FROM historialEstadoPersonaApoyo WHERE docApo = ? ORDER BY fechaCambio DESC LIMIT 1";
                $stmtHist = $con->prepare($sqlHistorial);
                $stmtHist->bind_param("s", $documento);
                $stmtHist->execute();
                $resHist = $stmtHist->get_result();
                $motivo = ($resHist->num_rows > 0) ? $resHist->fetch_object()->motivoCambio : "No se especificó el motivo.";

                // KM = Redirige pasando el motivo
                header("Location: ../../html/pagina_principal/inicio_sesion_empleado.php?error=inhabilitado&motivo=" . urlencode($motivo));
                exit();
            }

            if (password_verify($password, $row->pswApo)) {
                // KM = Guarda sesion para la pagina de inicio 
                $_SESSION['docApo'] = $row->docApo; // KM = Documento
                $_SESSION['nomApo'] = $row->nomApo; // KM = Nombre
                $_SESSION['rol'] = $row->codRolApo; // KM = Codigo del rol
                $_SESSION['fotoPerfil'] = $row->fotoPerfil; // kKM = Foto del perfil
                $_SESSION['genApo']     = $row->genApo; // KM = Genero de la persona de apoyo
                $_SESSION['nomRolApo']  = $row->nomRol; // KM = Nombre del rol

                // KM = Registrar el inicio de sesion en loginPersonaApoyo
                $fechaLogin = date("Y-m-d H:i:s");
                $insertLogin = $con->prepare("INSERT INTO loginPersonaApoyo (docApoLogApo, fecLogApo) VALUES (?, ?)");
                $insertLogin->bind_param("ss", $row->docApo, $fechaLogin);
                $insertLogin->execute();
                $insertLogin->close();

                // KM = Redirige segun el rol (No es lo recomendable? Esto va a cambiar en la segunda version)
                switch ($row->codRolApo) {
                    case 1: // KM = Administrador
                        header("Location: ../../html/pagina_empleado/inicio_empleado_admin.php");
                        break;
                    case 2: // KM = Psicologo
                        header("Location: ../../html/pagina_empleado/empleado_psicologo/inicio_empleado_psicologo.php");
                        break;
                    case 3: // KM = Asesor
                        header("Location: ../../html/pagina_empleado/empleado_asesor/inicio_empleado_asesor.php");
                        break;
                    case 4: // KM = Enfermero
                        header("Location: ../../html/pagina_empleado/empleado_enfermera/inicio_empleado_enfermero.php");
                        break;
                    case 5: // KM = Consultor Legal
                        header("Location: ../../html/pagina_empleado/empleado_consultor/inicio_empleado_consultor.php");
                        break;
                    default:
                        echo "Error: Rol no válido";
                        break;
                }
                exit();
            } else {
                header("Location: ../../html/pagina_principal/inicio_sesion_empleado.php?error=credenciales_invalidas&documento=" . urlencode($documento));
                exit();
            }
        } else {
            header("Location: ../../html/pagina_principal/inicio_sesion_empleado.php?error=credenciales_invalidas&documento=" . urlencode($documento));
            exit();
        }
    } else {
        header("Location: ../../html/pagina_principal/inicio_sesion_empleado.php?error=credenciales_invalidas&documento=" . urlencode($documento));
        exit();
    }
}
