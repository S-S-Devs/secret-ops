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
            $arm_name = $_POST['arm_name'];
            $arm_description = $_POST['arm_description'];
            $arm_image = '';

            if (isset($_FILES['arm_image']) && $_FILES['arm_image']['error'] == UPLOAD_ERR_OK) {
                $arm_image = file_get_contents($_FILES['arm_image']['tmp_name']);
            }

            $sql = "INSERT INTO armas (nombre, descripcion, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssb", $arm_name, $arm_description, $null);
            $stmt->send_long_data(2, $arm_image);

            if ($stmt->execute()) {
                echo "<script>alert('Arma añadida exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'modify':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $arm_id = $_POST['arm_id'];
            $arm_name = $_POST['arm_name'];
            $arm_description = $_POST['arm_description'];
            $arm_image = '';

            if (isset($_FILES['arm_image']) && $_FILES['arm_image']['error'] == UPLOAD_ERR_OK) {
                $arm_image = file_get_contents($_FILES['arm_image']['tmp_name']);
                $sql = "UPDATE armas SET nombre = ?, descripcion = ?, imagen = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssbi", $arm_name, $arm_description, $null, $arm_id);
                $stmt->send_long_data(2, $arm_image);
            } else {
                $sql = "UPDATE armas SET nombre = ?, descripcion = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $arm_name, $arm_description, $arm_id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Arma modificada exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'delete':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $arm_id = $_POST['arm_id'];

            $sql = "DELETE FROM armas WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $arm_id);

            if ($stmt->execute()) {
                echo "<script>alert('Arma eliminada exitosamente!'); window.location.href = '../admin.php';</script>";
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