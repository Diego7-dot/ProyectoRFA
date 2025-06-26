<?php
// KM = Actualizar la disponibilidad de la persona de apoyo
ob_start(); // Limpia cualquier salida previa para evitar errores de cabecera

require_once("../../Config/conexion.php");
$con = Conexion();

$input = json_decode(file_get_contents("php://input"), true);
$docApo = $input["docApoDisp"] ?? '';
$disponibilidad = $input["disponibilidad"] ?? [];
$estadoGeneral = (int)($input["estGen"] ?? 1);
$motivo = trim($input["motivo"] ?? '');
$motivoGuardado = $motivo;

// KM = Guarda un log de depuración (opcional)
file_put_contents("log_disponibilidad_debug.txt", print_r($disponibilidad, true));

try {
    $con->begin_transaction();

    // KM = Obtener la disponibilidad actual en BD
    $stmtActuales = $con->prepare("SELECT * FROM disponibilidad_template WHERE docApoDisp = ?");
    $stmtActuales->bind_param("s", $docApo);
    $stmtActuales->execute();
    $res = $stmtActuales->get_result();

    $diasEnBD = [];
    while ($row = $res->fetch_assoc()) {
        $diasEnBD[$row['diaSem']] = $row;
    }

    $diasNuevos = [];
    foreach ($disponibilidad as $item) {
        $dia = $item["dia"] ?? '';
        $horaIni = $item["horaIni"] ?? '';
        $horaFin = $item["horaFin"] ?? '';
        $fecIni = empty($item["fechaIni"]) || $item["fechaIni"] === '0000-00-00' ? null : $item["fechaIni"];
        $fecFin = empty($item["fechaFin"]) || $item["fechaFin"] === '0000-00-00' ? null : $item["fechaFin"];
        $observacion = empty($item["observacion"]) ? null : $item["observacion"];

        if (!$dia || !$horaIni || !$horaFin) continue;
        $diasNuevos[] = $dia;

        if (isset($diasEnBD[$dia])) {
            $row = $diasEnBD[$dia];
            $idTemplate = $row['idTemplate'];

            // KM = Si está inactivo, reactívalo
            if ($row['activo'] == 0) {
                $stmtReactivar = $con->prepare("UPDATE disponibilidad_template SET activo = 1 WHERE idTemplate = ?");
                $stmtReactivar->bind_param("i", $idTemplate);
                $stmtReactivar->execute();

                // KM = Guardar en historial
                $stmtHist = $con->prepare("INSERT INTO historialEdicionDisponibilidad (idTemplate, docApoDisp, campoModificado, valorAnterior, valorNuevo, motivoCambio) VALUES (?, ?, 'activo', '0', '1', ?)");
                $stmtHist->bind_param("iss", $idTemplate, $docApo, $motivo);
                $stmtHist->execute();
            }

            $campos = [
                'horaIni' => $horaIni,
                'horaFin' => $horaFin,
                'fecIni' => $fecIni,
                'fecFin' => $fecFin,
                'obsDis' => $observacion
            ];

            foreach ($campos as $campo => &$nuevoValor) {
                if ($nuevoValor === '' || $nuevoValor === '0000-00-00') $nuevoValor = null;
            }
            unset($nuevoValor);

            foreach ($campos as $campo => $nuevoValor) {
                $valorAnterior = $row[$campo];
                if ($valorAnterior === '' || $valorAnterior === '0000-00-00') $valorAnterior = null;

                if (is_null($nuevoValor) && is_null($valorAnterior)) continue;
                if ((string)$valorAnterior !== (string)$nuevoValor) {
                    $stmtHist = $con->prepare("INSERT INTO historialEdicionDisponibilidad (idTemplate, docApoDisp, campoModificado, valorAnterior, valorNuevo, motivoCambio) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmtHist->bind_param("isssss", $idTemplate, $docApo, $campo, $valorAnterior, $nuevoValor, $motivo);
                    $stmtHist->execute();

                    if (is_null($nuevoValor)) {
                        $stmtUpdate = $con->prepare("UPDATE disponibilidad_template SET $campo = NULL WHERE idTemplate = ?");
                        $stmtUpdate->bind_param("i", $idTemplate);
                    } else {
                        $stmtUpdate = $con->prepare("UPDATE disponibilidad_template SET $campo = ? WHERE idTemplate = ?");
                        $stmtUpdate->bind_param("si", $nuevoValor, $idTemplate);
                    }

                    $stmtUpdate->execute();
                }
            }
        } else {
            // KM = Revisar si ya existe un día desactivado
            $stmtExisteInactivo = $con->prepare("SELECT idTemplate FROM disponibilidad_template WHERE docApoDisp = ? AND diaSem = ? AND activo = 0");
            $stmtExisteInactivo->bind_param("ss", $docApo, $dia);
            $stmtExisteInactivo->execute();
            $resInactivo = $stmtExisteInactivo->get_result();

            if ($rowInactivo = $resInactivo->fetch_assoc()) {
                $idTemplateInactivo = $rowInactivo['idTemplate'];

                $fecIni = $fecIni ?: null;
                $fecFin = $fecFin ?: null;
                $observacion = $observacion ?: null;

                $stmtReactivar = $con->prepare("UPDATE disponibilidad_template 
                SET horaIni = ?, horaFin = ?, fecIni = ?, fecFin = ?, obsDis = ?, activo = 1 
                WHERE idTemplate = ?");
                if (!$stmtReactivar) {
                    throw new Exception("Error preparando reactivación: " . $con->error);
                }
                $stmtReactivar->bind_param("sssssi", $horaIni, $horaFin, $fecIni, $fecFin, $observacion, $idTemplateInactivo);
                if (!$stmtReactivar->execute()) {
                    throw new Exception("Error al reactivar disponibilidad: " . $stmtReactivar->error);
                }

                if ($stmtReactivar->affected_rows === 0) {
                    throw new Exception("No se reactivó el registro inactivo (id=$idTemplateInactivo).");
                }

                $stmtHist = $con->prepare("INSERT INTO historialEdicionDisponibilidad 
                (idTemplate, docApoDisp, campoModificado, valorAnterior, valorNuevo, motivoCambio) 
                VALUES (?, ?, 'activo', '0', '1', ?)");
                $stmtHist->bind_param("iss", $idTemplateInactivo, $docApo, $motivo);
                $stmtHist->execute();
            } else {
                // KM = Si no existe el día ni activo ni inactivo, lo insertamos como nuevo
                $stmtInsert = $con->prepare("INSERT INTO disponibilidad_template 
                (docApoDisp, diaSem, horaIni, horaFin, fecIni, fecFin, obsDis, activo, estGen) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1)");
                $stmtInsert->bind_param("sssssss", $docApo, $dia, $horaIni, $horaFin, $fecIni, $fecFin, $observacion);
                $stmtInsert->execute();

                if ($stmtInsert->affected_rows === 0) {
                    throw new Exception("No se pudo insertar el nuevo día de disponibilidad ($dia).");
                }
            }
        }
    }

    // KM = Desactivar días eliminados desde el formulario
    foreach ($diasEnBD as $dia => $row) {
        if (!in_array($dia, $diasNuevos)) {
            $idTemplate = $row['idTemplate'];

            $stmtHist = $con->prepare("INSERT INTO historialEdicionDisponibilidad (idTemplate, docApoDisp, campoModificado, valorAnterior, valorNuevo, motivoCambio) VALUES (?, ?, 'activo', '1', '0', ?)");
            $stmtHist->bind_param("iss", $idTemplate, $docApo, $motivo);
            $stmtHist->execute();

            $stmtInactivar = $con->prepare("UPDATE disponibilidad_template SET activo = 0 WHERE idTemplate = ?");
            $stmtInactivar->bind_param("i", $idTemplate);
            $stmtInactivar->execute();
        }
    }

    // KM = Actualizar estado general de disponibilidad
    if ($estadoGeneral === 0 && !empty($motivo)) {
        $stmtEstado = $con->prepare("UPDATE disponibilidad_template SET estGen = 0 WHERE docApoDisp = ?");
        $stmtEstado->bind_param("s", $docApo);
        $stmtEstado->execute();

        $stmtMotivo = $con->prepare("UPDATE disponibilidad_template SET motDis = ? WHERE docApoDisp = ? AND activo = 1");
        $stmtMotivo->bind_param("ss", $motivo, $docApo);
        $stmtMotivo->execute();
    } elseif ($estadoGeneral === 1) {
        $stmtEstado = $con->prepare("UPDATE disponibilidad_template SET estGen = 1, motDis = NULL WHERE docApoDisp = ?");
        $stmtEstado->bind_param("s", $docApo);
        $stmtEstado->execute();
    }

    $con->commit();
    ob_end_clean();

    echo json_encode([
        "exito" => true,
        "mensaje" => "Disponibilidad actualizada correctamente.",
        "motivoGuardado" => $motivoGuardado
    ]);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(["exito" => false, "mensaje" => "Error interno: " . $e->getMessage()]);
}
