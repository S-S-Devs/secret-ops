<?php
include '../includes/db_config.php';

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
        echo "<script>alert('Registro exitoso!'); window.location.href = '../login/login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
  <title>Registro - Secret Ops</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $(function() {
        $("#birthdate").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            maxDate: "+0d"
        });
    });
  </script>
</head>
<body>
  <div id="registro">
    <form id="registro-form" class="form-content" method="post" action="registro.php">
        <h3>Registro</h3>
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="birthdate">Fecha de Nacimiento:</label>
        <input type="text" id="birthdate" name="birthdate" required><br>

        <label for="country">País:</label>
        <select id="country" name="country" required>
            <option value="">Seleccione...</option>
            <option value="usa">Estados Unidos</option>
            <option value="mexico">México</option>
            <option value="spain">España</option>
            <option value="other">Otro</option>
        </select><br>

        <label for="role">Tipo de Cuenta:</label>
        <select id="role" name="role" required>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select><br>

        <button type="submit">Registrarse</button>
        <button type="button" onclick="location.href='../index.php'">Volver</button>
    </form>
  </div>
</body>
</html>