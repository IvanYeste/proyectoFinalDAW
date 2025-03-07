<?php
    require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/reservaOnline.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <div class="imageContainer">
    <div class="content">
    <?php require_once '../php/header.php'; ?>
    <div class="containerBloques">
        
    

    <div class="bloqueCentral">
    <?php
        $mysqli = new mysqli("localhost", "root", "", "parking");

        $sql_reservas = "SELECT Fecha_inicio, Fecha_fin, hora_inicio, hora_fin FROM reservas WHERE ID_cliente = $_COOKIE[id]";
        if ($stmt = $mysqli->prepare($sql_reservas)) {
            $stmt->execute();
            $stmt->bind_result($fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin);

            // Verificar si el usuario tiene reservas
            if ($stmt->fetch()) {
                // El usuario tiene reservas, mostrarlas
                echo "<h2>Tus Reservas </h2>";
                do {
                    echo "<div class='reserva-item'>";
                    echo "<p>Fecha de entrada: $fecha_inicio a las $hora_inicio  </p>";
                    echo "<p>Fecha de salida: $fecha_fin a las $hora_fin  </p>";
                    echo "</div>";
                } while ($stmt->fetch());
            } else {
                // El usuario no tiene reservas
                echo "<p class='no-reservas'>No tienes plazas reservadas.</p>";
            }

            // Cerrar la consulta
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $mysqli->error;
        }

        ?>
    
    </div>
    <div class="bloqueDerecho">
    <form action='reservaOnline.php' method='post'>
    <h2>Reserva Online</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 10px;">
        <div>
            <label for="fecha_llegada">Fecha Llegada:</label>
            <input type="date" id="fecha_llegada" name="fecha_llegada" required>
        </div>
        <div>
            <label for="hora_llegada">Hora Llegada:</label>
            <input type="time" id="hora_llegada" name="hora_llegada" required>
        </div>
        <div>
            <label for="fecha_salida">Fecha Salida:</label>
            <input type="date" id="fecha_salida" name="fecha_salida" required>
        </div>
        <div>
            <label for="hora_salida">Hora Salida:</label>
            <input type="time" id="hora_salida" name="hora_salida" required>
        </div>
    </div>
    <label for="matricula">Matricula:</label>
    <input type="text" id="matricula" name="matricula" required>
    <input type="submit" id="boton_reserva" name="boton_reserva" value="Reservar">
</form>
           
       
    </div>
    </div>

    <footer>
     <p>&copy; 2024 Parking. Todos los derechos reservados.</p>
    </footer>
    </div>
    </div>
    <script src="../js/index.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>
    <?php
    $mysqli->close(); // Cerrar la conexión a la base de datos
    ?>
</body>
</html>