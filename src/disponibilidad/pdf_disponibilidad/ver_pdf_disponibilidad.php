<?php
// KM = Esta es una prueba visual del archivo pfd disponibilidad
require_once('../../../libreria/fpdf186/fpdf.php');

require('generar_pdf_disponibilidad.php');

$docApo = '11111111111';
$nombre = 'Keren Daniela';
$disponibilidad = [
    ['fecha' => '2025-06-24', 'horaIni' => '08:00', 'horaFin' => '12:00'],
    ['fecha' => '2025-06-26', 'horaIni' => '10:00', 'horaFin' => '14:00'],
    ['fecha' => '2025-06-28', 'horaIni' => '09:00', 'horaFin' => '13:00']
];

$pdf = generarPDFDisponibilidad($docApo, $nombre, $disponibilidad);
$pdf->Output(); // KM = Muestra el PDF en el navegador
