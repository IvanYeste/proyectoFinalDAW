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
    <?php require_once '../php/header.php'; ?>
    <div class="containerBloques">
    <div class="bloqueIzquierdo">
        <h2>Seleccionar fecha</h2>
        <form action="" method="post">
            <input type="date" name="fecha" required>
            <input type="submit" name="buscar_reservas" value="Buscar">
        </form>
    </div>
    
   

    <div class="bloqueCentral">
        <?php
        if (isset($_POST['buscar_reservas'])) {
            $fecha_seleccionada = $_POST['fecha'];
            $reservas = buscarReservas($fecha_seleccionada);
            if (!empty($reservas)) {
                echo "<h2>Reservas para el $fecha_seleccionada:</h2>";
                echo "<table>";
                echo "<tr><th>ID Reserva</th><th>ID Cliente</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Matrícula</th></tr>";
                foreach ($reservas as $reserva) {
                    echo "<tr>";
                    echo "<td>{$reserva['ID_reserva']}</td>";
                    echo "<td>{$reserva['ID_cliente']}</td>";
                    echo "<td>{$reserva['Fecha_inicio']} / {$reserva['hora_inicio']}</td>";
                    echo "<td>{$reserva['Fecha_fin']} / {$reserva['hora_fin']}</td>";
                    echo "<td>{$reserva['Matricula']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay reservas para el $fecha_seleccionada</p>";
            }
        }
        ?>
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