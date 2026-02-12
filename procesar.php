<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1.- Conexion
include("conexion.php");

// 2.- Recibir datos de manera segura (formulario)
$razon_social = mysqli_real_escape_string($conexion, $_POST['razon_social']);
$rfc          = mysqli_real_escape_string($conexion, $_POST['rfc']);
$domicilio    = mysqli_real_escape_string($conexion, $_POST['domicilio']);
$poblacion    = mysqli_real_escape_string($conexion, $_POST['poblacion']);
$colonia      = mysqli_real_escape_string($conexion, $_POST['colonia']);
$cp           = mysqli_real_escape_string($conexion, $_POST['cp']);
$estado       = mysqli_real_escape_string($conexion, $_POST['estado']);
$email        = mysqli_real_escape_string($conexion, $_POST['email']);
$telefono     = mysqli_real_escape_string($conexion, $_POST['telefono']);
$web          = mysqli_real_escape_string($conexion, $_POST['web']);
$firma        = mysqli_real_escape_string($conexion, $_POST['firma_digital']);

// --- VALIDACIÓN DE RFC DUPLICADO ---
$checkRFC = "SELECT rfc FROM formulario_clientes WHERE rfc = '$rfc' LIMIT 1";
$resultado = mysqli_query($conexion, $checkRFC);

if (mysqli_num_rows($resultado) > 0) {
    header("Location: index.html?error=rfc_existente");
    exit(); 
}

// 3.- Directorio en servidor (linux)
$ruta_archivos = '/srv/www/htdocs/formulario_clientes/uploads/';
$base_dir = $ruta_archivos . $rfc . "/";

if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true);
}

// 4.- Función para procesar archivos
function subirArchivo($file_input, $dest_dir) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = basename($_FILES[$file_input]['name']);
        $ruta_final = $dest_dir . $nombre_archivo;
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $ruta_final)) {
            return $nombre_archivo;
        }
    }
    return null;
}

// --- GENERACIÓN DE PDF DE PRIVACIDAD (FPDF) ---
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 85, 150); 
$pdf->Cell(0, 10, utf8_decode('PIHCSA PARA HOSPITALES S.A. DE C.V.'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_decode('COMPROBANTE DE ACEPTACIÓN DE POLÍTICA DE PRIVACIDAD'), 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);

$contenido_legal = "En la ciudad de Morelia, Michoacán, con fecha " . date('d/m/Y') . " a las " . date('H:i:s') . ", el usuario que se identifica como:\n\n" .
"RAZÓN SOCIAL: " . $razon_social . "\n" .
"RFC: " . $rfc . "\n" .
"CORREO: " . $email . "\n\n" .
"Declara bajo protesta de decir verdad que ha leído íntegramente la POLÍTICA DE PRIVACIDAD...";

$pdf->MultiCell(0, 6, utf8_decode($contenido_legal));
$pdf->Ln(20);
$pdf->SetFillColor(240, 240, 240);
$pdf->Rect(20, $pdf->GetY(), 170, 40, 'F'); 
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, utf8_decode('EVIDENCIA DE FIRMA DIGITAL'), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Courier', 'B', 18); 
$pdf->Cell(0, 10, utf8_decode($firma), 0, 1, 'C'); 
$pdf->Line(60, $pdf->GetY() - 2, 150, $pdf->GetY() - 2);

$nombre_archivo_pdf = "AVISO_PRIVACIDAD_FIRMADO.pdf";
$ruta_destino_pdf = $base_dir . $nombre_archivo_pdf;
$pdf->Output('F', $ruta_destino_pdf);

// --- PROCESAR DOCUMENTOS SEGÚN LA ELECCIÓN ---
// Se procesan todos, los que no se enviaron devolverán null automáticamente
$path_licencia      = subirArchivo('pdf_licencia', $base_dir);
$path_aviso_rs      = subirArchivo('pdf_aviso_rs', $base_dir);
$path_funcionamiento = subirArchivo('pdf_funcionamiento', $base_dir); // La nueva columna
$path_domicilio     = subirArchivo('pdf_domicilio', $base_dir);
$path_ine_rep       = subirArchivo('pdf_ine_responsable', $base_dir);
$path_ine_rs        = subirArchivo('pdf_ine_responsable_sanitario', $base_dir);

// Procesar Imágenes
$img_fachada = subirArchivo('img_fachada', $base_dir);
$img_almacen = subirArchivo('img_almacen', $base_dir);

// 5.- INSERT FINAL (Coincidiendo con tus 21 columnas de la imagen)
$query = "INSERT INTO formulario_clientes (
    razon_social, domicilio, poblacion, colonia, cp, estado, rfc, pagina_web, telefono, email, firma_digital,
    doc_licencia_sanitaria, doc_aviso_responsableSanitario, doc_aviso_funcionamiento, 
    doc_ine_responsableSanitario, doc_ine_representanteLegal, doc_comprobante_domicilio,
    img_fachada, img_almacen
) VALUES (
    '$razon_social', '$domicilio', '$poblacion', '$colonia', '$cp', '$estado', '$rfc', '$web', '$telefono', '$email', '$nombre_archivo_pdf',
    " . ($path_licencia ? "'$path_licencia'" : "NULL") . ", 
    " . ($path_aviso_rs ? "'$path_aviso_rs'" : "NULL") . ", 
    " . ($path_funcionamiento ? "'$path_funcionamiento'" : "NULL") . ", 
    " . ($path_ine_rs ? "'$path_ine_rs'" : "NULL") . ", 
    " . ($path_ine_rep ? "'$path_ine_rep'" : "NULL") . ", 
    " . ($path_domicilio ? "'$path_domicilio'" : "NULL") . ", 
    " . ($img_fachada ? "'$img_fachada'" : "NULL") . ", 
    " . ($img_almacen ? "'$img_almacen'" : "NULL") . "
)";

if (mysqli_query($conexion, $query)) {
    header("Location: index.html?status=success");
    exit(); 
} else {
    echo "Error en MariaDB: " . mysqli_error($conexion);
}
?>