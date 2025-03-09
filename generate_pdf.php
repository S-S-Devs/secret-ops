<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'includes/db_config.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html = '<h1>Consulta General</h1>';

// Obtener datos de usuarios
$html .= '<h2>Usuarios</h2>';
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $html .= '<table border="1" style="width:100%;border-collapse:collapse;"><tr><th>ID</th><th>Nombre de Usuario</th><th>Email</th><th>Fecha de Nacimiento</th><th>País</th><th>Rol</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["email"] . "</td><td>" . $row["birthdate"] . "</td><td>" . $row["country"] . "</td><td>" . $row["role"] . "</td></tr>";
    }
    $html .= '</table>';
} else {
    $html .= '<p>No hay usuarios registrados.</p>';
}

// Obtener datos de mapas
$html .= '<h2>Mapas</h2>';
$sql = "SELECT * FROM mapas";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $html .= '<table border="1" style="width:100%;border-collapse:collapse;"><tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["descripcion"] . "</td></tr>";
    }
    $html .= '</table>';
} else {
    $html .= '<p>No hay mapas registrados.</p>';
}

// Obtener datos de armas
$html .= '<h2>Armas</h2>';
$sql = "SELECT * FROM armas";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $html .= '<table border="1" style="width:100%;border-collapse:collapse;"><tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr>';
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["descripcion"] . "</td></tr>";
    }
    $html .= '</table>';
} else {
    $html .= '<p>No hay armas registradas.</p>';
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('consulta_general.pdf', array("Attachment" => 1));
?>