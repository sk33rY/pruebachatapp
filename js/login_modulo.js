const formRegister = document.querySelector(".form-register");
const inputUser = document.querySelector('.form-register input[name="Nombres"]');
const inputPass = document.querySelector('.form-register input[name="password"]');
const inputEmail = document.querySelector('.form-register input[name="correo"]');
const alertaError = document.querySelector(".form-register .alerta-error");
const alertaExito = document.querySelector(".form-register .alerta-exito");

const userNameRegex = /^[a-zA-Z0-9\_\-]{4,16}$/;
export const emailRegex = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
export const passwordRegex = /^.{4,12}$/;

export const estadoValidacionCampos = {
  userName: false,
  userEmail: false,
  userPassword: false,
};

document.addEventListener("DOMContentLoaded", () => {
  formRegister.addEventListener("submit", (e) => {
    e.preventDefault();
    enviarFormulario(formRegister, alertaError, alertaExito);
  });

  inputUser.addEventListener("input", () => {
    validarCampo(userNameRegex, inputUser, "El usuario tiene que ser de 4 a 16 dígitos y solo puede contener, letras y guión bajo.");
  });

  inputEmail.addEventListener("input", () => {
    validarCampo(emailRegex, inputEmail, "El correo solo puede contener letras, números, puntos, guiones y guión bajo.");
  });

  inputPass.addEventListener("input", () => {
    validarCampo(passwordRegex, inputPass, "La contraseña tiene que ser de 4 a 12 dígitos");
  });
});

export function validarCampo(regularExpresion, campo, mensaje) {
  const esValido = regularExpresion.test(campo.value);
  if (esValido) {
    eliminarAlerta(campo.parentElement.parentElement);
    estadoValidacionCampos[campo.name] = true;
    campo.parentElement.classList.remove("error");
  } else {
    estadoValidacionCampos[campo.name] = false;
    campo.parentElement.classList.add("error");
    mostrarAlerta(campo.parentElement.parentElement, mensaje);
  }
}

function mostrarAlerta(referencia, mensaje) {
  eliminarAlerta(referencia);
  const alertaDiv = document.createElement("div");
  alertaDiv.classList.add("alerta");
  alertaDiv.textContent = mensaje;
  referencia.appendChild(alertaDiv);
}

function eliminarAlerta(referencia) {
  const alerta = referencia.querySelector(".alerta");
  if (alerta) alerta.remove();
}

export function enviarFormulario(form, alertaError, alertaExito) {
  if (estadoValidacionCampos.userName && estadoValidacionCampos.userEmail && estadoValidacionCampos.userPassword) {
    const formData = new FormData(form);

    fetch('RegistroUser.php', {
      method: 'POST',
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        if (alertaExito) {
          alertaExito.textContent = data.message;
          alertaExito.classList.add("alertaExito");
        }
        if (alertaError) {
          alertaError.classList.remove("alertaError");
        }
        form.reset();
        setTimeout(() => {
          if (alertaExito) {
            alertaExito.classList.remove("alertaExito");
          }
        }, 3000);
      } else {
        mostrarError(data.message);
      }
    })
    .catch(error => {
      mostrarError('Hubo un error al registrar el usuario');
      console.error('Error:', error);
    });

    return;
  }

  if (alertaExito) {
    alertaExito.classList.remove("alertaExito");
  }
  if (alertaError) {
    alertaError.classList.add("alertaError");
  }
  setTimeout(() => {
    if (alertaError) {
      alertaError.classList.remove("alertaError");
    }
  }, 3000);
}


function mostrarError(mensaje) {
  alertaError.textContent = mensaje;
  alertaError.classList.add("alertaError");
  setTimeout(() => {
    alertaError.classList.remove("alertaError");
  }, 3000);
}
