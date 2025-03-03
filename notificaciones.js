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