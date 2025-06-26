<?php
// KM = Generar pdf con la plantilla semanal

// KM = LLamar a la libreria
require_once(__DIR__ . '/../../../libreria/fpdf186/fpdf.php');

// KM = Clase para mostrar el logo y el nombre de la pagina
class PDFConLogo extends FPDF
{
    function Header()
    {
        $this->Image(__DIR__ . '/../../../iconos/pagina_principal/logo_fem_icon.png', 10, 10, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(120, 94, 160);
        $this->SetXY(35, 15);
        $this->Cell(0, 10, 'Red Femenina de Apoyo', 0, 1, 'L');
        $this->SetY(35);
    }
}

/**
 * Convierte UTF-8 → ISO-8859-1 (Latin-1) para FPDF
 */

function toISO($txt)
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $txt);
}

/**
 * Genera el PDF con la plantilla semanal.
 *
 * @param string $docApo
 * @param string $nombre
 * @param array  $disponibilidad  [['dia'=>'lunes','horaIni'=>'08:00','horaFin'=>'12:00'], …]
 * @return FPDF
 */

// KM = Funcion que me da el formato del pdf mandado 
function generarPDFDisponibilidad($docApo, $nombre, $disponibilidad)
{
    // KM = Reorganizar la informacion
    $tabla = [];               // ['lunes'=>['08:00 - 12:00', '14:00 - 18:00'], …]
    foreach ($disponibilidad as $fila) {
        $dia = strtolower($fila['dia']);
        $hora = substr($fila['horaIni'], 0, 5) . ' - ' . substr($fila['horaFin'], 0, 5); // guion ascii
        $tabla[$dia][] = $hora;
    }

    $pdf = new PDFConLogo(); // KM = Llamamos a la clase previamente creada
    $pdf->AddPage();

    // KM = Titulo principal
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(120, 94, 160);
    $pdf->Cell(0, 10, 'Disponibilidad registrada', 0, 1, 'C');
    $pdf->Ln(3);

    // KM = Datos personales
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0);
    $pdf->Cell(30, 8, 'Nombre:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, toISO($nombre), 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 8, 'Documento:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, $docApo, 0, 1);
    $pdf->Ln(6);

    // KM = Tabla de disponibilidad
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(204, 153, 255);
    $pdf->SetTextColor(255);
    $pdf->Cell(55, 10, 'Dia', 1, 0, 'C', true);
    $pdf->Cell(0, 10, 'Bloques de hora', 1, 1, 'C', true);

    // KM = Filas de datos
    $pdf->SetFont('Arial', '', 11);
    $pdf->SetTextColor(0);

    $orden = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];

    foreach ($orden as $dia) {
        $bloques = $tabla[$dia] ?? ['—'];
        foreach ($bloques as $i => $rang) {
            $pdf->Cell(55, 8, $i === 0 ? ucfirst(toISO($dia)) : '', 1, 0, 'C');
            $pdf->Cell(0,  8, toISO($rang),                          1, 1, 'C');
        }
    }

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 10);
    $texto = "Nota: esta plantilla sera la base para la generación automática "
        . "de tu disponibilidad semanal. Podrás editarla antes del domingo "
        . "11:59 p. m. de cada semana.\n\nCon cariño,\nRed Femenina de Apoyo";
    $pdf->MultiCell(0, 6, toISO($texto));

    return $pdf;
}
