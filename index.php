<?php
include 'includes/db_config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secret Ops</title>
  <!--FUENTES-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
  <!--FONT AWESOME-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  <!--ESTILOS-->
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div id="secret-ops">
    <div class="container">
      <nav class="navbar">
        <img src="img/download.png" alt="Ops Logo" class="nav-brand">
        <ul class="nav-menu">
          <li><a href="/index.php#inicio">Inicio</a></li>
          <li><a href="/index.php#mapas">Mapas</a></li>
          <li><a href="/index.php#movilidad">Movilidad</a></li>
          <li><a href="/index.php#clases_y_armas">Clases y Armas</a></li>
          <li><a href="/index.php#modo_zombies">Modo Zombies</a></li>
        </ul>
        <?php if(isset($_SESSION['username'])): ?>
          <div class="nav-buttons">
            <button><a href="logout.php">Cerrar Sesión</a></button>
          </div>
        <?php else: ?>
          <div class="nav-buttons">
            <button><a href="login/login.php">Inicio</a></button>
            <button><a href="registro/registro.php">Registro</a></button>
          </div>
        <?php endif; ?>
        <ul class="nav-menu-right">
          <li><a href="#"><i class="fas fa-search"></i></a></li>
        </ul>
      </nav>
      <hr>

      <section class="Banner-two" id="blackops6" style="background-image: url('img/Call of Duty Black Ops 6.png');">
        <div class="content">
          <h2>Black Ops 6 Information Catalog</h2>
          <p>Descubre todo sobre el nuevo lanzamiento de Black Ops 6, sus características, modos de juego y más.</p>
          <a href="#" class="btn">Conocer más <i class="fas fa-chevron-right"></i></a>
        </div>
      </section>

      <h2 class="titulo-seccion">Mapas</h2>
      <div class="mapas" id="mapas">
        <?php
        // Conexión a la base de datos y consulta de los mapas
        $sql = "SELECT nombre, descripcion, imagen FROM mapas";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($row['imagen']) . "' alt='" . $row['nombre'] . "'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>" . $row['descripcion'] . "</p>";
            echo "</div>";
          }
        } else {
          echo "No hay mapas disponibles.";
        }
        ?>
      </div>

      <h2 class="titulo-seccion">Armas y clases</h2>
      <div class="armas" id="clases_y_armas">
        <?php
        // Conexión a la base de datos y consulta de las armas
        $sql = "SELECT nombre, descripcion, imagen FROM armas";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<img src='data:image/jpeg;base64," . base64_encode($row['imagen']) . "' alt='" . $row['nombre'] . "'>";
            echo "<h3>" . $row['nombre'] . "</h3>";
            echo "<p>" . $row['descripcion'] . "</p>";
            echo "</div>";
          }
        } else {
          echo "No hay armas disponibles.";
        }
        ?>
      </div>

      <section class="Banner-two" id="modo_zombies" style="background-image: url('img/modo-zombies.png');">
        <div class="content">
          <h2>Modo Zombies</h2>
          <p>El modo Zombies de Black Ops 6 se reveló por completo e incluye una característica que los fanáticos siempre han deseado.</p>
          <a href="#" class="btn">Conocer más <i class="fas fa-chevron-right"></i></a>
        </div>
      </section>

      <footer>
        <div class="footer-container">
          <div class="footer-section">
            <h3>Sobre Nosotros</h3>
            <p>Secret Ops es una comunidad dedicada a proporcionar la información más reciente y precisa sobre Black Ops 6. Desde mapas hasta armas, encontrarás todo lo que necesitas saber aquí.</p>
          </div>
          <div class="footer-section">
            <h3>Enlaces Rápidos</h3>
            <ul>
              <li><a href="#inicio">Inicio</a></li>
              <li><a href="#mapas">Mapas</a></li>
              <li><a href="#movilidad">Movilidad</a></li>
              <li><a href="#clases_y_armas">Clases y Armas</a></li>
              <li><a href="#modo_zombies">Modo Zombies</a></li>
            </ul>
          </div>
          <div class="footer-section">
            <h3>Síguenos</h3>
            <ul class="socials">
              <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
              <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#"><i class="fab fa-instagram"></i></a></li>
              <li><a href="#"><i class="fab fa-youtube"></i></a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; 2025 Secret Ops. Todos los derechos reservados.</p>
          <div id="browser-info"></div>
        </div>
      </footer>
    </div>
  </div>
  
  <!-- Scroll Reveal -->
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="js/main.js"></script>
  <script src="js/scripts.js"></script>
</body>
</html>