<?php
    require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/menu_trabajador.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>

    <div class="imageContainer">
    <div class="content">

    <header>
    <div class="logo-container">
        <img src="../src/logo.png" width="100px" height="100px" alt="Logo" id="logo">
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
        <?php

    echo "Bienvenido/a   ". $_COOKIE['nombre']. ". Selecciona una opción: <br>";

    
    $conn = new mysqli("localhost", "root", "", "parking");
    $sql_trabajadores = "SELECT id_usuario, nombre, tipo FROM usuarios";
    $result_trabajadores = $conn->query($sql_trabajadores);

    $options = array();
    if ($result_trabajadores->num_rows > 0) {
        while($row = $result_trabajadores->fetch_assoc()) {
            if($row["tipo"] == 0){
                $options[] = array("value" => $row["id_usuario"], "nombre" => $row["nombre"]);
            }
        }
    }

?>



<form action="php/menu_trabajador.php" method="post">
    <label for="id_trabajador_recibe">ID del Trabajador que Recibirá la Solicitud:</label><br>
        <select id="id_trabajador_recibe" name="id_trabajador_recibe">
            <option value ="none">Selecciona un trabajador</option>
            <?php
                foreach($options as $option){
                    echo "<option value='" . $option["value"] . "'>" . $option["nombre"] . "</option>";

                }
            ?>
        </select><br>

    <input type="submit" name="submit_id" value="Siguiente">
</form>
<?php
if(isset($_POST["submit_id"])){
    if($_POST["id_trabajador_recibe"] == "none"){
        echo "Por favor, selecciona un trabajador.";
    } else {
        
    
?>
<form action="php/menu_trabajador.php" method="post">
    <input type="hidden" name="id_trabajador_recibe" value="<?php echo $_POST['id_trabajador_recibe']; ?>">
    <h2>Solicitud de Cambio de Horario - Paso 2</h2>

    <!-- Aquí se mostrarán los horarios del trabajador que envía la solicitud -->
    <label for="fecha_actual">Tu fecha:</label>
    <?php
    $sql = "SELECT  Fecha FROM horarios WHERE ID_trabajador = $_COOKIE[id] ";
    $result = $conn->query($sql);

    // Comprobar si se encontraron resultados
    if ($result->num_rows > 0) {
        echo "<select id='fecha_actual' name='fecha_actual'>";
        echo "<option>Selecciona una fecha</option>";
        // Mostrar las opciones en el desplegable
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['Fecha'] . "'>" . $row['Fecha'] .  "</option>";
        }
        echo "</select>";
    } else {
        echo "<option>No hay fechas disponibles para el trabajador.</option>";
    }
    ?>
    <label for="hora_actual">Tu hora:</label>
    <select id="hora_actual" name = "hora_actual">
    <option>Selecciona una hora</option>
    <option value="10:00">10:00</option>
    <option value="11:00">11:00</option>
    <option value="14:00">14:00</option>
    </select>


    <!-- Aquí se mostrarán los horarios del trabajador que recibe la solicitud -->
    <label for="fecha_cambio">Fecha del otro trabajador:</label><br>
    <?php
    $sql = "SELECT  Fecha FROM horarios WHERE ID_trabajador = $_POST[id_trabajador_recibe]";
    $result = $conn->query($sql);

    // Comprobar si se encontraron resultados
    if ($result->num_rows > 0) {
        echo "<select id='fecha_cambio' name='fecha_cambio'>";
        echo "<option>Selecciona una fecha</option>";
        // Mostrar las opciones en el desplegable
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['Fecha'] . "'>" . $row['Fecha'] .  "</option>";
        }
        echo "</select>";
    } else {
        echo $num_rows;
    }
?>
    <label for="hora_cambio">Hora del otro trabajador:</label>
    <select id="hora_cambio" name = "hora_cambio">
    <option>Selecciona una hora</option>
    <option value="10:00">10:00</option>
    <option value="11:00">11:00</option>
    <option value="14:00">14:00</option>
    </select>


    <input type="submit" name="submit_fecha" value="Enviar Solicitud">
</form>

<?php
    }
}
?>
</form>
        </div>
        
        <div class="bloqueCentral">
        <div id="calendario">
            <?php
            $mesActual = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
            $añoActual = isset($_GET['año']) ? (int)$_GET['año'] : date('Y');
            generarCalendario($mesActual, $añoActual);
            ?>
            <script>
                document.getElementById('prevMonth').addEventListener('click', function() {
                    var mes = <?php echo $mesActual; ?>;
                    var año = <?php echo $añoActual; ?>;
                    mes--;
                    if (mes < 1) {
                        mes = 12;
                        año--;
                    }
                    window.location.href = `?mes=${mes}&año=${año}`;
                });

                document.getElementById('nextMonth').addEventListener('click', function() {
                    var mes = <?php echo $mesActual; ?>;
                    var año = <?php echo $añoActual; ?>;
                    mes++;
                    if (mes > 12) {
                        mes = 1;
                        año++;
                    }
                    window.location.href = `?mes=${mes}&año=${año}`;
                });
            </script>
        </div>
        </div>

        <div class="bloqueDerecho">
            <h2>Horario de Trabajo</h2>
            <table>
            <tr>
                <th>Hora</th>
                <th>Turno 1 </th>
                <th>Turno 2 </th>
                <th>Turno 3 </th>
            </tr>
            <!-- Horario de 10:00 a 18:00 -->
            <?php
            for ($hour = 10; $hour <= 18; $hour++) {
                $hourDisplay = sprintf("%02d:00", $hour); // Formato de hora con dos dígitos
                echo "<tr>";
                echo "<td>$hourDisplay</td>";
                echo "<td class='" . (($hour >= 10 && $hour < 15) ? "turno-1" : "") . "'></td>";
                echo "<td class='" . (($hour >= 11 && $hour < 16) ? "turno-2" : "") . "'></td>";
                echo "<td class='" . (($hour >= 14 && $hour < 19) ? "turno-3" : "") . "'></td>";
                echo "</tr>";
            }
            ?>
        </table>
        
        </div>
    </div>

    <footer>
     <p>&copy; 2024 Parking. Todos los derechos reservados.</p>
    </footer>
    </div>
    </div>

    <script src="../js/menu_trabajador.js"></script>
</body>
</html>