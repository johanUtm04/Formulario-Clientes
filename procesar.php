<?php
//1.-Llamamos la conexion
include("conexion.php");

//2.-Recibir datos de manera segura
$razon_social = mysqli_real_escape_string($conexion, $_POST['razon_social']);
$rfc          = mysqli_real_escape_string($conexion, $_POST['rfc']);
$domicilio    = mysqli_real_escape_string($conexion, $_POST['domicilio']);
$poblacion    = mysqli_real_escape_string($conexion, $_POST['poblacion']);
$colonia      = mysqli_real_escape_string($conexion, $_POST['colonia']);
$cp           = mysqli_real_escape_string($conexion, $_POST['cp']);
$estado       = mysqli_real_escape_string($conexion, $_POST['estado']);
$web          = mysqli_real_escape_string($conexion, $_POST['web']);
$telefono     = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);

//3.-Lugar donde seran subidos en el servidor linux
$ruta_videos = 'C:/Users/sistemas2/Videos/uploads_pihcsa/';
$base_dir = $ruta_videos . $rfc . "/";

if (!file_exists($base_dir)) {
    mkdir($base_dir, 0777, true);
}

//4.- Funcion para procesar subida de archivos
function subirArchivo($file_input, $dest_dir) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = basename($_FILES[$file_input]['name']);
        $ruta_final = $dest_dir . $nombre_archivo;
        
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $ruta_final)) {
            return $ruta_final; 
        }
    }
    return null;
}

$path_licencia   = subirArchivo('pdf_licencia', $base_dir);
$path_aviso_rs   = subirArchivo('pdf_aviso_rs', $base_dir);
$path_aviso_func = subirArchivo('pdf_aviso_func', $base_dir);
$path_ine_rs     = subirArchivo('pdf_ine_rs', $base_dir);
$path_ine_rep    = subirArchivo('pdf_ine_rep', $base_dir);
$path_comp       = subirArchivo('pdf_comprobante', $base_dir);

// 5.-Insert en la base de datos
$query = "INSERT INTO formulario_clientes (
    razon_social, domicilio, poblacion, colonia, cp, estado, rfc, pagina_web, telefono, email,
    doc_licencia_sanitaria, doc_aviso_responsableSanitario, doc_aviso_funcionamiento, 
    doc_ine_responsableSanitario, doc_ine_representanteLegal, doc_comprobante_domicilio
) VALUES (
    '$razon_social', '$domicilio', '$poblacion', '$colonia', '$cp', '$estado', '$rfc', '$web', '$telefono', '$email',
    '$path_licencia', '$path_aviso_rs', '$path_aviso_func', '$path_ine_rs', '$path_ine_rep', '$path_comp'
)";

if (mysqli_query($conexion, $query)) {
    header("Location: index.html?status=success");
    exit(); 
} else {
    echo "Error en MariaDB: " . mysqli_error($conexion);
}
?>