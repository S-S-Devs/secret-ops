// FORMULARIOS
function initFormularios() {
  const inputs = document.querySelectorAll('input[type="text"], textarea, input[type="email"], input[type="number"]');
  const form = document.getElementById('personal-info-form');

  inputs.forEach(input => {
    input.addEventListener('focus', function () {
      this.style.backgroundColor = '#140f24';
    });

    input.addEventListener('blur', function () {
      this.style.backgroundColor = '';
      this.value = this.value.toUpperCase();
    });
  });

  document.getElementById("datos").onclick = function (event) {
    event.preventDefault();
    let valid = true;
    const form = document.getElementById("registro");
    const inputs = form.querySelectorAll("input, select");
    inputs.forEach(input => {
      if (!input.value) {
        valid = false;
        input.style.borderColor = 'red';
      } else {
        input.style.borderColor = '';
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

function mostrarLogin() {
  document.getElementById("login").style.display = "block";
  document.getElementById("secret-ops").style.display = "none";
}

function mostrarRegistro() {
  document.getElementById("secret-ops").style.display = "none";
  document.getElementById("registro").style.display = "block";
}

function volverRegistro() {
  document.getElementById("registro").style.display = "none";
  document.getElementById("secret-ops").style.display = "block";
}

function volverLogin() {
  document.getElementById("secret-ops").style.display = "block";
  document.getElementById("login").style.display = "none";
}

window.onload = function() {
  initFormularios();
}