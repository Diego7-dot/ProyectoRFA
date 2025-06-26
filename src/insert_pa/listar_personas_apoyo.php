<?php
// KM = Listar personas de apoyo (excluye administradores)
require_once("../../Config/conexion.php");
$con = Conexion();

header("Content-Type: application/json");

try {
    // KM = Recibir filtros opcionales
    $docFiltro = $_GET['documento'] ?? ''; // KM = Filtro por el documento
    $estadoFiltro = $_GET['estado'] ?? ''; // KM = Filtro por el estado
    $rolFiltro = $_GET['rol'] ?? ''; // KM = Filtro por el rol

    $params = [];
    $types = '';

    $sql = "SELECT pa.*, r.nomRol, c.nomCiu 
            FROM personaApoyo pa
            JOIN rol r ON pa.codRolApo = r.codRol
            JOIN ciudad c ON pa.codCiuApo = c.codCiu
            WHERE r.nomRol <> 'Administrador'";

    // KM = Filtro por documento exacto
    if (!empty($docFiltro)) {
        $sql .= " AND pa.docApo = ?";
        $params[] = $docFiltro;
        $types .= "s";
    }

    // KM = Filtro por estado
    if ($estadoFiltro === 'activo') {
        $sql .= " AND pa.estadoApo = TRUE";
    } elseif ($estadoFiltro === 'inactivo') {
        $sql .= " AND pa.estadoApo = FALSE";
    }

    // kM = Filtro por rol
    if (!empty($rolFiltro) && $rolFiltro !== 'todos') {
        $sql .= " AND r.nomRol LIKE ?";
        $params[] = trim($rolFiltro);
        $types .= "s";
    }

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar la consulta']);
        exit;
    }

    // KM = Vincular parámetros si existen
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $personas = [];
    while ($row = $res->fetch_assoc()) {
        $personas[] = $row;
    }

    echo json_encode($personas);
} catch (Exception $e) {
    echo json_encode(['error' => 'Ocurrió un error al listar: ' . $e->getMessage()]);
}
