<?php
    require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/reservas.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

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
            <li><a href="../php/contacto.php">Contacto</a></li>
            <li><a id="reservaOnline" href="../php/reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2)  ? '' : 'oculto'; ?>"><a id="reservas" href="../php/reservas.php">Plazas Reservadas</a></li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>"><a id="menu_trabajador" href="../php/menu_trabajador.php">Horario</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>"><a id="menu_admin" href="../php/menu_admin.php">Gestión</a></li>
        </ul>
    </nav>
    <?php
           if(isset($_COOKIE["nombre"])) {
            include("notificaciones.php");
        }
    ?>
    <div class="sesion-container">
        
        <div class="sesion-iniciada">
            <p>Bienvenido <?php echo $_COOKIE["nombre"]; ?></p>
            <i class='bx bxs-user-circle'></i>
            <form action='../index.php' method='post'>
                <input type='submit' id='cerrar_sesion' name='cerrar_sesion' value='Cerrar Sesión'>
            </form>
           
        </div>

    </div>
    </header>
    <div class="containerBloques">
    <div class="bloqueIzquierdo">
        <h2>Seleccionar fecha</h2>
        <form action="" method="post">
            <input type="date" name="fecha" required>
            <input type="submit" name="buscar_reservas" value="Buscar">
        </form>
    </div>
    
   

    <div class="bloqueCentral">

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
                echo "<td style='border: 1px solid black;' class='" . (($hour >= 10 && $hour < 18) ? "pago" : "no-pago") . "'></td>";
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
    <script src="../js/index.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>

</body>
</html>