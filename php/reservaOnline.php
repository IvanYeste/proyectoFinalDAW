<?php
$mysqli = new mysqli("localhost", "root", "", "parking");

    include("notificaciones.php");

            if (isset($_POST['boton_reserva'])) {
                // Obtener los datos del formulario
                $fecha_llegada = $_POST['fecha_llegada'];
                $hora_llegada = $_POST['hora_llegada'];
                $fecha_salida = $_POST['fecha_salida'];
                $hora_salida = $_POST['hora_salida'];
                $matricula = $_POST['matricula'];
                $id_cliente = $_COOKIE['id'];
            
                // Insertar los datos en la tabla de reservas
                $sql_insert = "INSERT INTO reservas (ID_cliente, Fecha_inicio, hora_inicio,hora_fin, Fecha_fin, Matricula) VALUES (?, ?, ?, ?, ?,?)";
                if (validarMatricula($matricula)) {
                    if ($stmt = $mysqli->prepare($sql_insert)) {
                        $stmt->bind_param("isssss", $id_cliente, $fecha_llegada, $hora_llegada, $hora_salida, $fecha_salida,$matricula);
                        if ($stmt->execute()) {
                            echo "Reserva realizada correctamente.";
                        } else {
                            echo "Error al realizar la reserva: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        echo "La matrícula ingresada no es válida. Por favor, ingrese una matrícula válida.";
                    }
                } else {
                    echo "Error en la preparación de la consulta: " . $mysqli->error;
                }
                header("Location:reservaOnline.php");
            }
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/reservaOnline.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <div class="imageContainer">
    <div class="content">
    <header>
    <div class="logo-container">
        <img src="src/logo.png" width="150px" height="150px" alt="Logo" id="logo">
    </div>
    <nav>
        <ul>
            <li><a id="inicio" href="php/index.php">Inicio</a></li>
            <li><a href="php/contacto.php">Contacto</a></li>
            <li><a id="reservaOnline" href="php/reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2)  ? '' : 'oculto'; ?>"><a id="reservas" href="php/reservas.php">Plazas Reservadas</a></li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>"><a id="menu_trabajador" href="php/menu_trabajador.php">Horario</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>"><a id="menu_admin" href="php/menu_admin.php">Gestión</a></li>
        </ul>
    </nav>
    <div class="sesion-container">
        <?php
            include("php/notificaciones.php");
        ?>
        <div class="sesion-iniciada">
            <p>Bienvenido <?php echo $_COOKIE["nombre"]; ?></p>
            <i class='bx bxs-user-circle'></i>
            <form action='index.php' method='post'>
                <input type='submit' id='cerrar_sesion' name='cerrar_sesion' value='Cerrar Sesión'>
            </form>
           
        </div>

    </div>
    </header>
    <div class="containerBloques">
        
    

    <div class="bloqueCentral">
    <?php
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
    <script src="js/index.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>
    <?php
    $mysqli->close(); // Cerrar la conexión a la base de datos
    ?>
</body>
</html>
<?php
        // Función para cerrar sesión
        function cerrarSesion(){
            setcookie("nombre", "", time() - 3600);
            setcookie("id", "", time() - 3600);
            setcookie("admin", "", time() - 3600);
            header("Location: index.php");
            // Redireccionar a la página actual
        }

function validarMatricula($matricula) {
    // Expresión regular para una matrícula típica en España (formato XXNNNNXX)
    $patron = '/^[0-9]{4}[A-Z]{3}$/';

    // Verificar si la matrícula coincide con el patrón
    if (preg_match($patron, $matricula)) {
        return true; // La matrícula es válida
    } else {
        return false; // La matrícula no es válida
    }
}
?>
