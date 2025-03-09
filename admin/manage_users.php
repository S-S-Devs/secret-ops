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
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $birthdate = $_POST['birthdate'];
            $country = $_POST['country'];
            $role = $_POST['role'];

            $sql = "INSERT INTO usuarios (username, email, password, birthdate, country, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $username, $email, $password, $birthdate, $country, $role);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario añadido exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'delete':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_POST['user_id'];

            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario eliminado exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
            $conn->close();
        }
        break;

    case 'modify':
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_POST['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $country = $_POST['country'];

            $sql = "UPDATE usuarios SET username = ?, email = ?, country = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $country, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario modificado exitosamente!'); window.location.href = '../admin.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
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