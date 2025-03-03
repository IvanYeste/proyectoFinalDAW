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
  
  let map;



  function iniciarMap(){
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 16,
      center: { lat: 40.619955, lng: -0.101904 },
      mapTypeId: "terrain",
    });
    /*const marker = new AdvancedMarkerElement({
      map,
      position: { lat: 40.621570, lng: -0.098823 },
    });*/
  
    const marker=[
      {lat: 40.621384887575175, lng: -0.09908491418811713}
      ]
  
    
    const Square1Coordinates = [
      { lat: 40.623084, lng: -0.100918},
      { lat: 40.622423, lng: -0.101135 },
      { lat: 40.622139, lng: -0.103426 },
      { lat: 40.622492, lng: -0.103535 },
    ];
    const Square2Coordinates = [
      
      { lat: 40.622492, lng: -0.103535 },
      { lat: 40.622139, lng: -0.103426 },
      { lat: 40.619542, lng: -0.104254 },

      
      { lat: 40.619577, lng: -0.104656 },
      { lat: 40.622449, lng: -0.104132 },
    ];
  
    
    const square1 = new google.maps.Polygon({
      paths: Square1Coordinates,
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
    });
  
    const square2 = new google.maps.Polygon({
      paths: Square2Coordinates,
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
    });
    square1.setMap(map);
    square2.setMap(map);
  }
  // Obtener elementos del DOM
var modal = document.getElementById("modal");
var btnRegistro = document.getElementById("btn-registro");
var spanCerrar = document.getElementsByClassName("close")[0];

// Función para mostrar el modal
btnRegistro.onclick = function() {
  modal.style.display = "block";
}

// Función para cerrar el modal
spanCerrar.onclick = function() {
  modal.style.display = "none";
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
let currentIndex = 0;
const totalItems = document.querySelectorAll('.carousel-item').length;
const itemWidth = document.querySelector('.carousel-item').clientWidth;
const carousel = document.querySelector('.carousel');

function nextSlide() {
  currentIndex = (currentIndex + 1) % totalItems;
  updateCarousel();
}

function prevSlide() {
  currentIndex = (currentIndex - 1 + totalItems) % totalItems;
  updateCarousel();
}

function updateCarousel() {
  carousel.style.transition = 'transform 0.5s ease-in-out';
  carousel.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
}

// Al terminar la transición, eliminar la transición para evitar que se desplace bruscamente
carousel.addEventListener('transitionend', () => {
  carousel.style.transition = '';
});

setInterval(nextSlide, 3000); // Mover automáticamente el carrusel cada 3 segundos