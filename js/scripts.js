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
  };
}

function mostrarConfirmacion() {
  let userName = document.getElementById('username').value;
  let email = document.getElementById('email').value;
  let birthdate = document.getElementById('birthdate').value;
  let country = document.getElementById('country').value;
  alert(`Usuario: ${userName}\nCorreo: ${email}\nFecha de nacimiento: ${birthdate}\nPaís: ${country}`);
  volverRegistro();
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