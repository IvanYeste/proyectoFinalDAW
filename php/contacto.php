<?php
    require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina de Turismo</title>
    <link rel="stylesheet" href="../css/contacto.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        function verificarRegistro(event) {
            var nombre = "<?php echo isset($_COOKIE['nombre']) ? $_COOKIE['nombre'] : '' ?>";
            var admin = "<?php echo isset($_COOKIE['admin']) ? $_COOKIE['admin'] : '' ?>";

            if (!nombre || admin == '') {
                alert("Debes Iniciar sesion para acceder a esta sección.");
                event.preventDefault();
            }
        }
        function iniciarMap(){
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: { lat: 40.619955, lng: -0.101904 },
                mapTypeId: "terrain",
            });
            const marker=[
                {lat: 40.621384887575175, lng: -0.09908491418811713}
                ]
            
            const newMarker = new google.maps.Marker({
                position: marker[0],
                map: map,
            });
            newMarker.setMap(map);
        }
    </script>
</head>
<body>
    <div class="imageContainer">
        <div class="content">
            <header>
                <div class="logo-container">
                    <img src="../src/logo.png" width="150px" height="150px" alt="Logo" id="logo">
                </div>
                <nav>
                    <ul>
                        <li><a id="inicio" href="../index.php">Inicio</a></li>
                        <li><a id="contacto" href="../php/contacto.php">Contacto</a></li>
                        <li><a id="reservaOnline" href="../php/reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
                        <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2) ? '' : 'oculto'; ?>"><a id="reservas" href="../php/reservas.php">Plazas Reservadas</a></li>
                        <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>"><a id="menu_trabajador" href="../php/menu_trabajador.php">Horario</a></li>
                        <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>"><a id="menu_admin" href="../php/menu_admin.php">Gestión</a></li>
                    </ul>
                </nav>
            </header>
            <div class="containerBloques">
                    <div class="text-content">
                        <h1>OFICINA DE TURISMO</h1>
                        <p>La oficina de turismo es un espacio para el visitante que ofrece  información turística completa para organizar un viaje, disfrutar de la estancia y conocer los atractivos y posibilidades de nuestra ciudad, y también de la comarca de Els Ports y alrededores. Eventos, guías, rutas y todas las actividades de Morella y la zona las encontrarás en nuestra oficina.</p>
                        <h2>Horario de Atención</h2>
                        <ul>
                            <li>Lunes de 10:00 a 14:00</li>
                            <li>De Martes a Sabado  de 10:00 a 14:00 / 16:00 a 18:00</li>
                            <li>Domingo de 10:00 a 14:00</li>
                        </ul>
                        <h3 class="contact-info"><i class="bx bx-phone"></i> 964 17 30 32</h3>
                        <h3 class="contact-info"><img class="whatsapp" src="../src/whatsapp.webp" alt="WhatsApp">+34 661 42 52 94</>
                        <h3 class="contact-info"><i class="bx bx-envelope"></i> turisme@morella.net</h3>
                        <p>La oficina de turismo de Morella atiende peticiones de información turística acerca del municipio, de la comarca de Els Ports, de la provincia de Castellón, de la Comunitat Valenciana y de las zonas de alrededor como son las comarcas del Maestrazgo y el Matarraña. Además también actúa como oficina municipal que ofrece servicio de venta de entradas, inscripción a diferentes actividades y asesoramiento turístico al empresario y la población local, tiene venta de merchandaising y registro de objetos perdidos. Pertenece a la red de oficinas de turismo de la Comunitat Valenciana , Red Tourist Info, y cuenta desde 2008 con la Q de calidad turística española, así como también con la certificación de sello de calidad SICTED. Además tanto la oficina, como la población de Morella en general, esta adherida al Código Ético de Turismo Valenciano. La oficina de turismo también tiene el distintivo de Turismo Accesible que otorga la plataforma Predif, junto con Turisme Comunitat Valenciana, por trabajar en la mejora de la accesibilidad de sus instalaciones y servicios. </p>
                    </div>
                    <div class="image-content">
                        <img src="../src/oficinaTurismo.jpeg" alt="Oficina de Turismo">
                        <img src="../src/oficinaTurismo2.jpeg" alt="Oficina de Turismo">
                    </div>
                    <div id="map"></div>
                
            </div>
            <footer>
                <p>&copy; 2024 Parking. Todos los derechos reservados.</p>
            </footer>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>
</body>
</html>