document.getElementById('current-date').textContent = 'Fecha actual: ' + new Date().toLocaleDateString();
document.getElementById('last-modified').textContent = 'Última modificación: ' + document.lastModified;

const browserInfo = navigator.userAgent;
let browserName, fullVersion;
if ((verOffset = browserInfo.indexOf("Chrome")) != -1) {
    browserName = "Chrome";
    fullVersion = browserInfo.substring(verOffset + 7);
} else if ((verOffset = browserInfo.indexOf("Firefox")) != -1) {
    browserName = "Firefox";
    fullVersion = browserInfo.substring(verOffset + 8);
} else if ((verOffset = browserInfo.indexOf("MSIE")) != -1) {
    browserName = "Internet Explorer";
    fullVersion = browserInfo.substring(verOffset + 5);
} else if ((verOffset = browserInfo.indexOf("Safari")) != -1) {
    browserName = "Safari";
    fullVersion = browserInfo.substring(verOffset + 7);
    if ((verOffset = browserInfo.indexOf("Version")) != -1)
        fullVersion = browserInfo.substring(verOffset + 8);
} else {
    browserName = "Unknown";
    fullVersion = "Unknown";
}
document.getElementById('browser-info').textContent = `Navegador: ${browserName}, Versión: ${fullVersion.split(' ')[0]}`;

// FORMULARIOS
function initFormularios() {
  const inputs = document.querySelectorAll('input[type="text"], textarea, input[type="email"], input[type="number"]');
  const form = document.getElementById('personal-info-form');

  inputs.forEach(input => {
    input.addEventListener('focus', function () {
      this.style.backgroundColor = '#140f24'; // Cambia el color de fondo al enfocar
    });

    input.addEventListener('blur', function () {
      this.style.backgroundColor = ''; // Restaura el color de fondo original
      this.value = this.value.toUpperCase(); // Convierte el texto a mayúsculas
    });
  });

  document.getElementById("datos").onclick = function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto
    let valid = true;
    const form = document.getElementById("registro");
    const inputs = form.querySelectorAll("input, select");
    inputs.forEach(input => {
      if (!input.value) {
        valid = false;
        input.style.borderColor = 'red'; // Marca el campo vacío en rojo
      } else {
        input.style.borderColor = ''; // Restaura el color del borde
      }
    });
    if (valid) {
      const nombre = form.elements["name"].value;
      const correo = form.elements["email"].value;
      const password = form.elements["password"].value;
      const birthdate = form.elements["birthdate"].value;
      const country = form.elements["country"].value;

      alert('Nombre: ' + nombre + '\n'
         + 'Correo: ' + correo + '\n'
         + 'Contraseña: ' + password + '\n'
         + 'Fecha de Nacimiento: ' + birthdate + '\n'
         + 'País: ' + country);  
      document.getElementById("registro").style.display = "none";
      document.getElementById("secret-ops").style.display = "block";
    } else {
      alert('Por favor, complete todos los campos obligatorios.');
    }
  }
}

// Muestra el formulario de login
function mostrarLogin() {
  document.getElementById("login").style.display = "block";
  document.getElementById("secret-ops").style.display = "none";
}

// Muestra Pagina principal
function mostrarRegistro() {
  document.getElementById("secret-ops").style.display = "none";
  document.getElementById("registro").style.display = "block";
}

// Funcion de VolverRegistro
function volverRegistro() {
  document.getElementById("registro").style.display = "none";
  document.getElementById("secret-ops").style.display = "block";
}

// Funcion de volverLogin
function volverLogin() {
  document.getElementById("secret-ops").style.display = "block";
  document.getElementById("login").style.display = "none";
}

window.onload = function() {
  initFormularios();
}
