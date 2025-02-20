// Java Sc = Registro
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