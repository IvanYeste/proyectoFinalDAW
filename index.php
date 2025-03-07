<?php
    require_once 'php/functions.php';

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css\index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        // Esta función verifica si el usuario está registrado y muestra la alerta si no lo está
        function verificarRegistro(event) {
            var nombre = "<?php echo isset($_COOKIE['nombre']) ? $_COOKIE['nombre'] : '' ?>";
            var admin = "<?php echo isset($_COOKIE['admin']) ? $_COOKIE['admin'] : '' ?>";

            // Verificar si el usuario no está registrado o no es administrador
            if (!nombre || admin == '') {
                alert("Debes Iniciar sesion para acceder a esta sección.");
                event.preventDefault(); // Evita seguir el enlace si el usuario no está registrado
            }
        }
    </script>
</head>
<body>
    <div class="imageContainer">
    <div class="content">
    <?php require_once 'php/header.php'; ?>
    <div class="containerBloques">
    <div class="bloqueIzquierdo">
    <h2> Tarifas</h2>
    <table style="border: 1px solid black;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">Periodo de Estacionamiento</th>
                    <th style="border: 1px solid black;">Precio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1 hora (importe mínimo)</td>
                    <td>1,00 €</td>
                </tr>
                <tr>
                    <td>2 horas</td>
                    <td>1,50 €</td>
                </tr>
                <tr>
                    <td>3 horas</td>
                    <td>2,00 €</td>
                </tr>
                <tr>
                    <td>4 horas</td>
                    <td>2,50 €</td>
                </tr>
                <tr>
                    <td>8 horas</td>
                    <td>3,00 €</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bloqueCentral">
        <h1>Bienvenido al Parking Público de Morella</h1>
        
        <p>En el corazón de la encantadora ciudad de <span class="keyword">Morella</span>, nuestro parking público ofrece la solución perfecta para tus necesidades de estacionamiento. Con una <span class="keyword">ubicación privilegiada </span>cerca de las principales atracciones turísticas y comerciales, nuestro estacionamiento es el <span class="keyword">punto de partida ideal</span> para explorar todo lo que <span class="keyword">Morella</span> tiene para ofrecer.</p>
        <p>Nuestro compromiso es brindarte la mejor experiencia de estacionamiento posible. Con un <span class="keyword">amplio espacio disponible </span>, te garantizamos <span class="keyword">comodidad y conveniencia</span> en cada visita. Además, nuestra ubicación estratégica significa que siempre estarás a pocos pasos de tus destinos favoritos en <span class="keyword">Morella</span>.</p>
        <p>En el Parking Público de Morella,<span class="keyword"> tu seguridad es nuestra prioridad</span>. Contamos con un sistema de vigilancia las 24 horas del día, los 7 días de la semana, así como con espacios de estacionamiento cubiertos para proteger tu vehículo de los elementos. También ofrecemos acceso para discapacitados y servicios adicionales para garantizar una experiencia sin preocupaciones para todos nuestros clientes.</p>
        <p>¿Te preocupa el costo del estacionamiento? ¡No te preocupes! En nuestro estacionamiento, <span class="keyword"> ofrecemos tarifas competitivas y opciones flexibles</span> para adaptarnos a tus necesidades. Ya sea que necesites estacionamiento por unas horas o por varios días, tenemos opciones que se ajustan a tu presupuesto.</p>
        <div class="carousel-container">
        <div class="carousel">
            <div class="carousel-item"><img src="src/fotoParking1.jpg" alt="Imagen 1"></div>
            <div class="carousel-item"><img src="src/fotoParking2.jpg" alt="Imagen 2"></div>
            <div class="carousel-item"><img src="src/fotoParking5.jpg" alt="Imagen 3"></div>
            <div class="carousel-item"><img src="src/fotoParking4.jpg" alt="Imagen 4"></div>
            <div class="carousel-item"><img src="src/fotoParking3.jpg" alt="Imagen 5"></div>
        </div>
        <div class="carousel-controls">
            <div class="carousel-control" onclick="prevSlide()">&#10094;</div>
            <div class="carousel-control" onclick="nextSlide()">&#10095;</div>
        </div>
        </div>
        <p><span class="keyword">¿Quieres asegurarte de tener un lugar de estacionamiento reservado?</span> ¡Hazlo fácilmente a través de nuestra plataforma de reservas en línea! Reserva tu espacio con anticipación y despreocúpate de encontrar estacionamiento cuando llegues.</p>
        <p>Pero no solo confíes en nuestra palabra. Nuestros clientes satisfechos hablan por sí mismos. Lee sus testimonios y descubre por qué el Parking Público de <span class="keyword">Morella</span> es la opción número uno para estacionar en la ciudad.</p>
        <p>¿Listo para disfrutar de una experiencia de estacionamiento sin complicaciones en Morella? ¡Contáctanos hoy mismo para obtener más información o para hacer tu reserva! Estamos aquí para ayudarte a hacer que tu visita a <span class="keyword">Morella</span> sea aún más memorable.</p>
        <p><span class="keyword">¡Esperamos darte la bienvenida pronto al Parking Público de Morella</span>!</p>
        <h3>En este mapa puedes ver la zona concreta de estacionamiento.</h3>
    <div id="map"></div>
    </div>
    <div class="bloqueDerecho">
    

    <h2>Horario de Pago </h2>

    <table style="border: 1px solid black;">
            <tr>
                <th  style="border: 1px solid black;">Hora</th>
                <th  style="border: 1px solid black;">Dias Festivos y fines de semana</th>
            </tr>
            <!-- Horario de 00:00 a 24:00 -->
            <?php
            for ($hour = 0; $hour < 24; $hour++) {
                $hourDisplay = sprintf("%02d:00", $hour); // Formato de hora con dos dígitos
                echo "<tr>";
                echo "<td style='border: 1px solid black;' >$hourDisplay</td>";
                echo "<td style='border: 1px solid black;' class='" . (($hour >= 10 && $hour <= 18) ? "pago" : "no-pago") . "'></td>";
                echo "</tr>";
            }
            ?>
        </table><br>
        <div class="leyenda">
            <div class="pago"></div>Pago
            <div class="no-pago"></div>Gratis
        </div>
            <b>Los dias no festivos el parking es gratuito</b>
    </div>
    </div>

    <footer>
     <p>&copy; 2024 Parking. Todos los derechos reservados.</p>
    </footer>
    </div>
    </div>
    <script src="js\index.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>

</body>
</html>
