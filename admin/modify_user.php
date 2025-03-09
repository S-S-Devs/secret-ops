<?php
include '../includes/db_config.php';
session_start();

if ($_SESSION['user_type'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

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
?>