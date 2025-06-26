<?php
// KM = Librerías necesarias
require __DIR__ . '/../../vendor/autoload.php';
require_once "../../Config/conexion.php";
require '../../libreria/PHPMailer/src/Exception.php';
require '../../libreria/PHPMailer/src/PHPMailer.php';
require '../../libreria/PHPMailer/src/SMTP.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_FILES['archivoExcel']) && $_FILES['archivoExcel']['error'] === UPLOAD_ERR_OK) {
    $mime = $_FILES['archivoExcel']['type'];
    $extensionesValidas = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ];

    if (!in_array($mime, $extensionesValidas)) {
        header("Location: ../../html/pagina_empleado/admin_agregar_apoyo.php?error=archivo");
        exit;
    }

    $conexion = Conexion();
    $archivo = $_FILES['archivoExcel']['tmp_name'];
    $spreadsheet = IOFactory::load($archivo);
    $hoja = $spreadsheet->getActiveSheet();
    $filas = $hoja->toArray();

    $erroresFila = [];

    for ($i = 1; $i < count($filas); $i++) {
        $f = array_map('trim', $filas[$i]); // KM = Limpiar espacios

        // KM = Capturar columnas
        [$doc, $correo, $nombre, $apellido, $fechaNac, $genero, $telefono, $direccion, $codCiu, $codRol, $tarjeta, $especial, $clavePlano] = $f;
        $claveHash = password_hash($clavePlano, PASSWORD_DEFAULT);

        // KM = Validaciones fuertes
        if (
            empty($doc) || !ctype_digit($doc) || strlen($doc) < 6 ||
            empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
            empty($nombre) || empty($apellido) ||
            empty($fechaNac) || empty($genero) ||
            empty($telefono) || !ctype_digit($telefono) || strlen($telefono) < 7 ||
            empty($direccion) || empty($codCiu) || empty($codRol) || empty($clavePlano)
        ) {
            $erroresFila[] = "Fila $i: Datos inválidos o incompletos.";
            continue;
        }

        // KM = Validar edad
        $fechaN = DateTime::createFromFormat('Y-m-d', $fechaNac);
        $edad = $fechaN ? $fechaN->diff(new DateTime())->y : 0;
        if (!$fechaN || $edad < 18 || $edad > 80) {
            $erroresFila[] = "Fila $i: Edad no permitida ($edad años).";
            continue;
        }

        // KM = Validar género
        if (!in_array($genero, ['F', 'M', 'O'])) {
            $erroresFila[] = "Fila $i: Género inválido: '$genero'. Usa F, M u O.";
            continue;
        }

        // KM = Foto por género
        $foto = match ($genero) {
            'F' => 'icono_default_mujer.png',
            'M' => 'icono_default_hombre.png',
            default => 'icono_default_otro.png',
        };

        // KM = Verifica duplicados
        $stmtCheck = $conexion->prepare("SELECT 1 FROM personaApoyo WHERE docApo = ? OR corApo = ?");
        $stmtCheck->bind_param("ss", $doc, $correo);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            $erroresFila[] = "Fila $i: Documento o correo duplicado.";
            continue;
        }

        // KM = Insertar persona de apoyo
        $stmt = $conexion->prepare("INSERT INTO personaApoyo 
        (docApo, corApo, nomApo, apeApo, fecNacApo, genApo, telApo, dirApo, codCiuApo, codRolApo, numTarProApo, espProApo, pswApo, fotoPerfil, estadoApo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");
        $stmt->bind_param("ssssssssssssss", $doc, $correo, $nombre, $apellido, $fechaNac, $genero, $telefono, $direccion, $codCiu, $codRol, $tarjeta, $especial, $claveHash, $foto);

        if (!$stmt->execute()) {
            $erroresFila[] = "Fila $i: Error al insertar en la base de datos.";
            continue;
        }

        // KM = Buscar nombre del rol
        $stmtRol = $conexion->prepare("SELECT nomRol FROM rol WHERE codRol = ?");
        $stmtRol->bind_param("i", $codRol);
        $stmtRol->execute();
        $resRol = $stmtRol->get_result();
        $rolNombre = ($row = $resRol->fetch_assoc()) ? $row['nomRol'] : 'Sin rol';

        // KM = Enviar correo
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'redapoyofemenino@gmail.com';
            $mail->Password = 'fcvlxktguryptrzv';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('redapoyofemenino@gmail.com', 'Red Femenina de Apoyo');
            $mail->addAddress($correo, $nombre);
            $mail->isHTML(true);

            $encabezado = match ($genero) {
                'F' => "Hola $nombre 💜",
                'M' => "Hola $nombre 💙",
                default => "Hola $nombre 💛"
            };

            $mail->Subject = "¡Bienvenid@ a la Red Femenina!";
            $mail->Body = "
                <h2>$encabezado</h2>
                <p>Fuiste registrada como persona de apoyo en nuestra plataforma.</p>
                <p><strong>Rol asignado:</strong> $rolNombre</p>
                <p><strong>Documento:</strong> $doc</p>
                <p><strong>Contraseña:</strong> $clavePlano</p>
                <p>Por favor inicia sesión y cambia tu contraseña lo antes posible.</p>
                <br><em>Este mensaje fue generado automáticamente. No responder.</em>
            ";
            $mail->send();
        } catch (Exception $e) {
            file_put_contents(__DIR__ . "/errores_email_$doc.txt", "Error: " . $mail->ErrorInfo);
        }
    }

    // KM = Si hubo errores, redirigir con detalles
    if (!empty($erroresFila)) {
        session_start();
        $_SESSION['errores_importacion'] = $erroresFila;
        header("Location: ../../html/pagina_empleado/admin_agregar_apoyo.php?importado=parcial");
        exit;
    }

    header("Location: ../../html/pagina_empleado/admin_agregar_apoyo.php?importado=ok");
    exit;
} else {
    header("Location: ../../html/pagina_empleado/admin_agregar_apoyo.php?error=subida");
    exit;
}
