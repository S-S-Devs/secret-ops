<?php
include '../includes/db_config.php';
session_start();

if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $map_name = $_POST['map_name'];
            $map_description = $_POST['map_description'];
            $map_image = '';

            if (isset($_FILES['map_image']) && $_FILES['map_image']['error'] == UPLOAD_ERR_OK) {
                $map_image = file_get_contents($_FILES['map_image']['tmp_name']);
            }

            $sql = "INSERT INTO mapas (nombre, descripcion, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            $null = null;
            $stmt->bind_param("ssb", $map_name, $map_description, $null);
            $stmt->send_long_data(2, $map_image);

            if ($stmt->execute()) {
                echo "<script>alert('Mapa añadido exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'modify':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $map_id = $_POST['map_id'];
            $map_name = $_POST['map_name'];
            $map_description = $_POST['map_description'];
            $map_image = '';

            if (isset($_FILES['map_image']) && $_FILES['map_image']['error'] == UPLOAD_ERR_OK) {
                $map_image = file_get_contents($_FILES['map_image']['tmp_name']);
                $sql = "UPDATE mapas SET nombre = ?, descripcion = ?, imagen = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $null = null;
                $stmt->bind_param("ssbi", $map_name, $map_description, $null, $map_id);
                $stmt->send_long_data(2, $map_image);
            } else {
                $sql = "UPDATE mapas SET nombre = ?, descripcion = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $map_name, $map_description, $map_id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Mapa modificado exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'delete':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $map_id = $_POST['map_id'];

            $sql = "DELETE FROM mapas WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $map_id);

            if ($stmt->execute()) {
                echo "<script>alert('Mapa eliminado exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        echo "Acción no válida.";
        break;
}
?>