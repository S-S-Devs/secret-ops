<?php
include '../includes/db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: ../admin.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión - Secret Ops</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <div id="login">
    <form id="login-form" method="post" action="login.php">
        <h3>Inicio de Sesión</h3>
        <label for="login-username">Nombre de Usuario:</label>
        <input type="text" id="login-username" name="username" required><br>

        <label for="login-password">Contraseña:</label>
        <input type="password" id="login-password" name="password" required><br>

        <button type="submit">Iniciar Sesión</button>
        <button type="button" onclick="location.href='../index.php'">Volver</button>
    </form>
  </div>
</body>
</html>