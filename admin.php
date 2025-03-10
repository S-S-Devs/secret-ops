<?php
session_start();
if ($_SESSION['user_type'] != 'admin') {
    header("Location: index.php");
    exit;
}
include 'includes/db_config.php';

$modify_user = null;
$modify_map = null;
$modify_arm = null;

$usuarios_query = "SELECT COUNT(*) as count FROM usuarios WHERE role = 'user'";
$admins_query = "SELECT COUNT(*) as count FROM usuarios WHERE role = 'admin'";
$mapas_query = "SELECT COUNT(*) as count FROM mapas";
$armas_query = "SELECT COUNT(*) as count FROM armas";

$usuarios_count = $conn->query($usuarios_query)->fetch_assoc()['count'];
$admins_count = $conn->query($admins_query)->fetch_assoc()['count'];
$mapas_count = $conn->query($mapas_query)->fetch_assoc()['count'];
$armas_count = $conn->query($armas_query)->fetch_assoc()['count'];

function fetch_by_id_or_name($conn, $table, $identifier) {
    $column = ($table === 'usuarios') ? 'username' : 'nombre';
    $sql = "SELECT * FROM $table WHERE id = ? OR $column = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("is", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $identifier = $_GET['identifier'];
    if ($_GET['type'] == 'user') {
        $modify_user = fetch_by_id_or_name($conn, 'usuarios', $identifier);
        if (!$modify_user) {
            echo "<script>alert('Usuario no encontrado.');</script>";
        }
    } elseif ($_GET['type'] == 'map') {
        $modify_map = fetch_by_id_or_name($conn, 'mapas', $identifier);
        if (!$modify_map) {
            echo "<script>alert('Mapa no encontrado.');</script>";
        }
    } elseif ($_GET['type'] == 'arm') {
        $modify_arm = fetch_by_id_or_name($conn, 'armas', $identifier);
        if (!$modify_arm) {
            echo "<script>alert('Arma no encontrada.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración - Secret Ops</title>
  <link rel="stylesheet" href="css/admin_styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/jquery.jqplot.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqPlot/1.0.9/plugins/jqplot.pieRenderer.min.js"></script>
  <style>
    .charts-container {
        display: flex;
        justify-content: space-around;
    }
    .chart {
        width: 45%;
    }
    .admin-section {
        display: none;
    }
  </style>
</head>
<body>
  <div id="admin-panel">
    <div class="container">
      <nav class="admin-navbar">
        <img src="./img/download.png" alt="Ops Logo" class="admin-brand">
        <ul class="admin-menu">
          <li><a href="javascript:void(0);" onclick="showSection('manage_users')">Gestionar Usuarios</a></li>
          <li><a href="javascript:void(0);" onclick="showSection('manage_maps')">Gestionar Mapas</a></li>
          <li><a href="javascript:void(0);" onclick="showSection('manage_weapons')">Gestionar Armas</a></li>
          <li><a href="javascript:void(0);" onclick="showSection('view_tables')">Consulta General</a></li>
        </ul>
        <div class="admin-buttons">
          <button><a href="logout.php">Cerrar Sesión</a></button>
        </div>
      </nav>
      <hr>

      <h2>Panel de Administración</h2>
      <p>Bienvenido, <?php echo $_SESSION['username']; ?>.</p>

      <!-- Gráficas -->
      <div id="charts" class="charts-container">
        <div id="user-chart" class="chart"></div>
        <div id="content-chart" class="chart"></div>
      </div>

      <!-- Sección Gestionar Usuarios -->
      <div id="manage_users" class="admin-section">
        <h3>Gestionar Usuarios</h3>
        <form id="add-user-form" method="post" action="admin/manage_users.php?action=add">
          <h4>Incluir Nuevo Usuario</h4>
          <label for="username">Nombre de Usuario:</label>
          <input type="text" id="username" name="username" required><br>
          <label for="email">Correo Electrónico:</label>
          <input type="email" id="email" name="email" required><br>
          <label for="password">Contraseña:</label>
          <input type="password" id="password" name="password" required><br>
          <label for="birthdate">Fecha de Nacimiento:</label>
          <input type="date" id="birthdate" name="birthdate" required><br>
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
          <button type="submit">Incluir Usuario</button>
        </form>
        
        <form id="query-user-form" method="get" action="admin.php">
          <h4>Buscar Usuario</h4>
          <input type="hidden" name="action" value="fetch">
          <input type="hidden" name="type" value="user">
          <label for="identifier">ID o Nombre de Usuario:</label>
          <input type="text" id="identifier" name="identifier" required><br>
          <button type="submit">Consultar Usuario</button>
        </form>
        <?php if ($modify_user): ?>
        <div id="modify-user-details">
          <form id="modify-user-form" method="post" action="admin/manage_users.php?action=modify">
            <h4>Modificar Usuario</h4>
            <input type="hidden" id="modify-user-id" name="user_id" value="<?php echo $modify_user['id']; ?>">
            <label for="modify-username">Nombre de Usuario:</label>
            <input type="text" id="modify-username" name="username" required value="<?php echo $modify_user['username']; ?>"><br>
            <label for="modify-email">Correo Electrónico:</label>
            <input type="email" id="modify-email" name="email" required value="<?php echo $modify_user['email']; ?>"><br>
            <label for="modify-country">País:</label>
            <select id="modify-country" name="country" required>
              <option value="usa" <?php if($modify_user['country'] == 'usa') echo 'selected'; ?>>Estados Unidos</option>
              <option value="mexico" <?php if($modify_user['country'] == 'mexico') echo 'selected'; ?>>México</option>
              <option value="spain" <?php if($modify_user['country'] == 'spain') echo 'selected'; ?>>España</option>
              <option value="other" <?php if($modify_user['country'] == 'other') echo 'selected'; ?>>Otro</option>
            </select><br>
            <button type="submit">Modificar Usuario</button>
          </form>
          <form id="delete-user-form" method="post" action="admin/manage_users.php?action=delete">
            <input type="hidden" id="delete-user-id" name="user_id" value="<?php echo $modify_user['id']; ?>">
            <button type="submit">Eliminar Usuario</button>
          </form>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sección Gestionar Mapas -->
      <div id="manage_maps" class="admin-section">
        <h3>Gestionar Mapas</h3>
        <form id="add-map-form" method="post" action="admin/manage_maps.php?action=add" enctype="multipart/form-data">
          <h4>Incluir Nuevo Mapa</h4>
          <label for="map-name">Nombre del Mapa:</label>
          <input type="text" id="map-name" name="map_name" required><br>
          <label for="map-description">Descripción:</label>
          <textarea id="map-description" name="map_description" required></textarea><br>
          <label for="map-image">Imagen:</label>
          <input type="file" id="map-image" name="map_image" required><br>
          <button type="submit">Incluir Mapa</button>
        </form>

        <form id="query-map-form" method="get" action="admin.php">
          <h4>Buscar Mapa</h4>
          <input type="hidden" name="action" value="fetch">
          <input type="hidden" name="type" value="map">
          <label for="identifier">ID o Nombre del Mapa:</label>
          <input type="text" id="identifier" name="identifier" required><br>
          <button type="submit">Consultar Mapa</button>
        </form>
        <?php if ($modify_map): ?>
        <div id="modify-map-details">
          <form id="modify-map-form" method="post" action="admin/manage_maps.php?action=modify" enctype="multipart/form-data">
            <h4>Modificar Mapa</h4>
            <input type="hidden" id="modify-map-id" name="map_id" value="<?php echo $modify_map['id']; ?>">
            <label for="modify-map-name">Nombre del Mapa:</label>
            <input type="text" id="modify-map-name" name="map_name" required value="<?php echo $modify_map['nombre']; ?>"><br>
            <label for="modify-map-description">Descripción:</label>
            <textarea id="modify-map-description" name="map_description" required><?php echo $modify_map['descripcion']; ?></textarea><br>
            <label for="modify-map-image">Imagen:</label>
            <input type="file" id="modify-map-image" name="map_image"><br>
            <button type="submit">Modificar Mapa</button>
          </form>
          <form id="delete-map-form" method="post" action="admin/manage_maps.php?action=delete">
            <input type="hidden" id="delete-map-id" name="map_id" value="<?php echo $modify_map['id']; ?>">
            <button type="submit">Eliminar Mapa</button>
          </form>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sección Gestionar Armas -->
      <div id="manage_weapons" class="admin-section">
        <h3>Gestionar Armas</h3>
        <form id="add-arm-form" method="post" action="admin/manage_weapons.php?action=add" enctype="multipart/form-data">
          <h4>Incluir Nueva Arma</h4>
          <label for="arm-name">Nombre del Arma:</label>
          <input type="text" id="arm-name" name="arm_name" required><br>
          <label for="arm-description">Descripción:</label>
          <textarea id="arm-description" name="arm_description" required></textarea><br>
          <label for="arm-image">Imagen:</label>
          <input type="file" id="arm-image" name="arm_image" required><br>
          <button type="submit">Incluir Arma</button>
        </form>

        <form id="query-arm-form" method="get" action="admin.php">
          <h4>Buscar Arma</h4>
          <input type="hidden" name="action" value="fetch">
          <input type="hidden" name="type" value="arm">
          <label for="identifier">ID o Nombre del Arma:</label>
          <input type="text" id="identifier" name="identifier" required><br>
          <button type="submit">Consultar Arma</button>
        </form>
        <?php if ($modify_arm): ?>
        <div id="modify-arm-details">
          <form id="modify-arm-form" method="post" action="admin/manage_weapons.php?action=modify" enctype="multipart/form-data">
            <h4>Modificar Arma</h4>
            <input type="hidden" id="modify-arm-id" name="arm_id" value="<?php echo $modify_arm['id']; ?>">
            <label for="modify-arm-name">Nombre del Arma:</label>
            <input type="text" id="modify-arm-name" name="arm_name" required value="<?php echo $modify_arm['nombre']; ?>"><br>
            <label for="modify-arm-description">Descripción:</label>
            <textarea id="modify-arm-description" name="arm_description" required><?php echo $modify_arm['descripcion']; ?></textarea><br>
            <label for="modify-arm-image">Imagen:</label>
            <input type="file" id="modify-arm-image" name="arm_image"><br>
            <button type="submit">Modificar Arma</button>
          </form>
          <form id="delete-arm-form" method="post" action="admin/manage_weapons.php?action=delete">
            <input type="hidden" id="delete-arm-id" name="arm_id" value="<?php echo $modify_arm['id']; ?>">
            <button type="submit">Eliminar Arma</button>
          </form>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sección Consulta General -->
      <div id="view_tables" class="admin-section">
        <h3>Consulta General</h3>
        <h4>Usuarios</h4>
        <?php
          $sql = "SELECT * FROM usuarios";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              echo "<table><tr><th>ID</th><th>Nombre de Usuario</th><th>Email</th><th>Fecha de Nacimiento</th><th>País</th><th>Rol</th></tr>";
              while ($row = $result->fetch_assoc()) {
                  echo "<tr><td>" . $row["id"] . "</td><td>" . $row["username"] . "</td><td>" . $row["email"] . "</td><td>" . $row["birthdate"] . "</td><td>" . $row["country"] . "</td><td>" . $row["role"] . "</td></tr>";
              }
              echo "</table>";
          } else {
              echo "No hay usuarios registrados.";
          }
        ?>
        <h4>Mapas</h4>
        <?php
          $sql = "SELECT * FROM mapas";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              echo "<table><tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr>";
              while ($row = $result->fetch_assoc()) {
                  echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["descripcion"] . "</td></tr>";
              }
              echo "</table>";
          } else {
              echo "No hay mapas registrados.";
          }
        ?>
        <h4>Armas</h4>
        <?php
          $sql = "SELECT * FROM armas";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              echo "<table><tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr>";
              while ($row = $result->fetch_assoc()) {
                  echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["descripcion"] . "</td></tr>";
              }
              echo "</table>";
          } else {
              echo "No hay armas registradas.";
          }
        ?>
        <form action="generate_pdf.php" method="post">
          <button type="submit">Generar PDF</button>
        </form>
      </div>
    </div>
  </div>
  
  <script>
  function showSection(sectionId) {
      var sections = document.getElementsByClassName('admin-section');
      for (var i = 0; i < sections.length; i++) {
          sections[i].style.display = 'none';
      }
      document.getElementById(sectionId).style.display = 'block';
  }

  // Mostrar la sección de inicio al cargar la página
  window.onload = function() {
      showSection('manage_users');
  };

  $(document).ready(function(){
      var userData = [
          ['Usuarios', <?php echo $usuarios_count; ?>],
          ['Administradores', <?php echo $admins_count; ?>]
      ];

      var contentData = [
          ['Mapas', <?php echo $mapas_count; ?>],
          ['Armas', <?php echo $armas_count; ?>]
      ];

      $.jqplot('user-chart', [userData], {
          seriesColors: ['#FFF000', '#0EF000'],
          seriesDefaults: {
              renderer: $.jqplot.PieRenderer,
              rendererOptions: {
                  showDataLabels: true
              }
          },
          legend: { show:true, location: 'e' }
      });

      $.jqplot('content-chart', [contentData], {
          seriesColors: ['#600080', '#00FFFF'],
          seriesDefaults: {
              renderer: $.jqplot.PieRenderer,
              rendererOptions: {
                  showDataLabels: true
              }
          },
          legend: { show:true, location: 'e' }
      });
  });
</script>
</body>
</html>