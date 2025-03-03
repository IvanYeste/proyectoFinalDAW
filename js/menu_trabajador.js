// Función para mostrar u ocultar elementos del menú según el tipo de usuario
function actualizarMenu() {
    var menuTrabajador = document.getElementById("menu_trabajador");
    var menuAdmin = document.getElementById("menu_admin");
    
    // Si el usuario es un trabajador, mostrar el menú de trabajador y ocultar el de admin
    if (getCookie("admin") === "0") {
        menuTrabajador.style.display = "inline";
        menuAdmin.style.display = "none";
    } 
    // Si el usuario es un admin, mostrar el menú de admin y ocultar el de trabajador
    else if (getCookie("admin") === "1") {
        menuTrabajador.style.display = "none";
        menuAdmin.style.display = "inline";
    }
}

// Función para obtener el valor de una cookie por su nombre
function getCookie(nombre) {
    var valor = "; " + document.cookie;
    var partes = valor.split("; " + nombre + "=");
    if (partes.length == 2) return partes.pop().split(";").shift();
}

// Llamar a la función actualizarMenu al cargar la página
window.onload = actualizarMenu;

function toggleAnimation() {
    var div = document.getElementById("notificaciones");
    div.style.display = "block";   
    div.classList.remove("animacionOcultar"); 
    div.classList.add("animacionMostrar");

    document.addEventListener("click", function(event) {
        if (div.contains(event.target)) { // Verifica si el clic no ocurrió dentro del div
          div.classList.add("animacionOcultar"); // Agrega la nueva animación
          setTimeout(() => {
           div.style.display = "none";
          }, 700);
        }
      });
  }
  