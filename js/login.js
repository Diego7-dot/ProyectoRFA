// Java Sc = Inicio Sesion
// Animations
const registerButton = document.getElementById("register");
const loginButton = document.getElementById("login");
const container = document.getElementById("container");

// Verificar si hay una pestaña guardada en localStorage
document.addEventListener("DOMContentLoaded", () => {
  const activePanel = localStorage.getItem("activePanel");
  
  if (activePanel === "register") {
    container.classList.add("right-panel-active");
  } else {
    container.classList.remove("right-panel-active");
  }
});

registerButton.addEventListener("click", () => {
  container.classList.add("right-panel-active");
  localStorage.setItem("activePanel", "register"); 
});

loginButton.addEventListener("click", () => {
  container.classList.remove("right-panel-active");
  localStorage.setItem("activePanel", "login"); 
});

// Validación de error en trabajador
const form = document.querySelector('form');
const role = document.getElementById('role'); 
const roleError = document.querySelector("#role-error");
const documento = document.getElementById('documento');
const documentoError = document.querySelector("#documento-error");
const password = document.getElementById('password');
const passwordError = document.querySelector("#password-error");

// Elementos del modal y el spinner
const errorModal = document.getElementById("errorModal");
const loadingSpinner = document.getElementById("loadingSpinner");
const closeButton = document.querySelector(".close");

// Función para mostrar el mensaje de error
function showError(input, message) {
    const formControl = input.parentElement;
    formControl.className = 'form-control error'; 
    const small = formControl.querySelector('small');
    small.innerText = message;
    small.style.visibility = 'block';
}

// Función para mostrar éxito (sin errores)
function showSuccess(input) {
    const formControl = input.parentElement;
    formControl.className = 'form-control success';
    const small = formControl.querySelector('small');
    small.innerText = '';
    small.style.visibility = 'none';
}

// Validar que el documento contenga solo números
function checkDocumento(documento) {
    const documentoRegex = /^\d+$/; 
    return documentoRegex.test(documento);
}

// Validar que el rol esté seleccionado
function checkRole(role) {
    return role.value !== ""; 
}

function clearFields() {
    documento.value = '';
    password.value = '';
    role.value = '';
}

//Evento al enviar el formulario
form.addEventListener('submit', function (e) {
    e.preventDefault(); 
    
    // Aplicar la clase que oculta los errores mientras se carga el spinner
    form.classList.add('spinner-active'); 

    // Mostrar el spinner de carga
    loadingSpinner.style.display = "block";

    let hasErrors = false; 
    // Validar campos requeridos
    if (documento.value.trim() === '') {
        showError(documento, "*El documento es obligatorio.");
        hasErrors = true;
    } else if (!checkDocumento(documento.value)) {
        showError(documento, "*Documento no válido, solo números.");
        hasErrors = true;
    } else {
        showSuccess(documento);
    }

    // Validar contraseña
    if (password.value.trim() === '') {
        showError(password, "*La contraseña es obligatoria.");
        hasErrors = true;
    } else if (password.value.length < 8) {
        showError(password, "*La contraseña debe tener al menos 8 caracteres.");
        hasErrors = true;
    } else if (password.value.length > 20) {
        showError(password, "*La contraseña no puede exceder los 20 caracteres.");
        hasErrors = true;
    } else {
        showSuccess(password);
    }

    // Validar rol
    if (!checkRole(role)) {
        showError(role, "*El rol es obligatorio.");
        hasErrors = true;
    } else {
        showSuccess(role);
    }

    // Simulación del proceso de validación y consulta
    setTimeout(() => {
        loadingSpinner.style.display = "none";
        form.classList.remove('spinner-active'); 

        if (!hasErrors) {
            console.log= "Validación exitosa"; 
            clearFields();
        } else {
            // Mostrar modal de error si hay errores
            errorModal.style.display = "block";
            const closeErrorButton = document.querySelector("#errorModal .close");
            closeErrorButton.onclick = function() {
                errorModal.style.display = "none";
            };
        }
    }, 500); 
});

document.addEventListener('DOMContentLoaded', () => {
    // Selección de elementos
    const lgForm = document.querySelector('.form-lg');
    const lgEmail = document.querySelector('.email');
    const lgPassword = document.querySelector('.password-2');

    // Mostrar mensaje de error
    function showError(input, message) {
        const small = input.nextElementSibling;
        small.textContent = message;
        input.classList.add('error');
        input.classList.remove('success');
    }

    // Mostrar mensaje de éxito
    function showSuccess(input) {
        const small = input.nextElementSibling;
        small.textContent = '';
        input.classList.add('success');
        input.classList.remove('error');
    }

    // Validar correo electrónico
    function isValidEmail(email) {
        const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return re.test(email);
    }

    // Evento de envío del formulario
    lgForm.addEventListener('submit', (e) => {
        e.preventDefault();
        let hasErrorsLg = false; 

        // Validación del correo electrónico
        if (lgEmail.value.trim() === '') {
            showError(lgEmail, "*El correo electrónico es obligatorio.");
            hasErrorsLg = true;
        } else if (!isValidEmail(lgEmail.value)) {
            showError(lgEmail, "*El formato del correo es inválido.");
            hasErrorsLg = true;
        } else {
            showSuccess(lgEmail);
        }

        // Validación de la contraseña
        if (lgPassword.value.trim() === '') {
            showError(lgPassword, "*La contraseña es obligatoria.");
            hasErrorsLg = true;
        } else if (lgPassword.value.length < 8) {
            showError(lgPassword, "*La contraseña debe tener al menos 8 caracteres.");
            hasErrorsLg = true;
        } else if (lgPassword.value.length > 20) {
            showError(lgPassword, "*La contraseña no puede exceder los 20 caracteres.");
            hasErrorsLg = true;
        } else {
            showSuccess(lgPassword);
        }
        
        // Si hay errores
        if (hasErrorsLg) {
            return;
        }

        // Si no hay errores
        lgForm.reset();
        console.log("Validación exitosa");
    });
});
