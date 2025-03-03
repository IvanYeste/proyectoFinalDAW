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
        
    <?php include("../php/notificaciones.php");?>
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
    <div class="sesion-container">
        <?php
            include("../php/notificaciones.php");
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


    <?php
    // Verifica si el formulario ha sido enviado
    if (isset($_POST['submit_fecha'])) {
        // Recoge los datos del formulario
        $id_trabajador_envia = $_COOKIE['id'];
        $id_trabajador_recibe = $_POST['id_trabajador_recibe'];
        $fecha_actual = $_POST['fecha_actual'];
        $hora_actual = $_POST['hora_actual'];
        $fecha_cambio = $_POST['fecha_cambio'];
        $hora_cambio = $_POST['hora_cambio'];

        $sql = "SELECT ID_horario FROM Horarios WHERE fecha = '$fecha_actual' AND hora_inicio = '$hora_actual'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fecha_actual_id = $row['ID_horario'];
        } else {
            echo "Error: No se encontró el horario actual en la base de datos.";
            exit();
        }

        $sql = "SELECT ID_horario FROM Horarios WHERE fecha = '$fecha_cambio' AND hora_inicio = '$hora_cambio'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fecha_cambio_id = $row['ID_horario'];
        } else {
            echo "Error: No se encontró el horario de cambio en la base de datos.";
            exit();
        }
        $sql = "INSERT INTO Solicitudes_Cambio_Horario (id_trabajador_envia, id_trabajador_recibe, estado, horario_cambio, horario_actual) VALUES ('$id_trabajador_envia', '$id_trabajador_recibe','Pendiente', '$fecha_cambio_id', '$fecha_actual_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Solicitud insertada correctamente en la base de datos";
        } else {
            echo "Error al insertar la solicitud: " . $conn->error;
        }

        $conn->close();
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

    <script src="js/menu_trabajador.js"></script>
</body>
</html>
<?php
function obtenerTrabajadores($fecha) {
    // Conexión a la base de datos 
    $conn = new mysqli("localhost", "root", "", "parking");
    if ($conn->connect_errno) {
        echo "Fallo al conectar a MySQL: " . $mysqli->connect_error;
        exit();
    }

    $sql_select = "SELECT * FROM solicitudes_cambio_horario WHERE Estado = 'Aceptada'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        // Si hay solicitudes pendientes, procesarlas
        while ($row = $result->fetch_assoc()) {
            $id_solicitud = $row['ID_solicitud'];
            $id_trabajador_envia = $row['ID_trabajador_envia'];
            $id_trabajador_recibe = $row['ID_trabajador_recibe'];
            $horario_actual = $row['Horario_actual'];
            $horario_cambio = $row['Horario_cambio'];

            // Actualizar los horarios de los trabajadores
            $sql_update_envia = "UPDATE horarios SET ID_trabajador = $id_trabajador_recibe WHERE ID_horario = $horario_actual";
            $sql_update_recibe = "UPDATE horarios SET ID_trabajador = $id_trabajador_envia WHERE ID_horario = $horario_cambio";
            $sql_update_solicitud = "UPDATE solicitudes_cambio_horario SET Estado = 'Registrado' WHERE ID_solicitud = $id_solicitud";

            $conn->query($sql_update_envia); 
            $conn->query($sql_update_recibe);
            $conn->query($sql_update_solicitud);

           
        }
    }
    //Poner nombres de trabajadores en el calendarioE
    $trabajadores = array();
    
    $consulta = "SELECT DISTINCT  usuarios.Nombre FROM `horarios`INNER JOIN usuarios ON horarios.ID_trabajador = usuarios.ID_usuario where horarios.Fecha=? ORDER BY horarios.Hora_inicio asc";
    if ($stmt = $conn->prepare($consulta)) {
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $stmt->bind_result($id_trabajador);
        while ($stmt->fetch()) {
            $trabajadores[] = $id_trabajador;
        }
        $stmt->close();
    }
    return $trabajadores;
}

function generarCalendario( $mesActual, $añoActual ) {

    $meses = [
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    ];

    $nombreMes = $meses[$mesActual];
    $primerDiaMes = new DateTime("$añoActual-$mesActual-01");
    $numeroDiasMes = $primerDiaMes->format('t');
    $primerDiaSemana = $primerDiaMes->format('N');
    
    echo "
    <div style='display: flex;'>
        <i class='bx bx-chevron-left' id='prevMonth'></i>
        <h2>" . $añoActual." ". $nombreMes . "</h2>
        <i class='bx bx-chevron-right' id='nextMonth'></i>
    </div>";
    echo '<table><thead><tr><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th><th>Domingo</th></tr></thead><tbody>';

    $contadorDias = 1;

    for ($i = 0; $i < 6; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 7; $j++) {
            if ($i === 0 && $j < $primerDiaSemana - 1) {
                echo '<td></td>'; // Espacios en blanco hasta el primer día del mes
            } elseif ($contadorDias > $numeroDiasMes) {
                echo '<td></td>'; // Rellenar con espacios en blanco después del último día del mes
            } else {
                $fecha = "$añoActual-$mesActual-" . str_pad($contadorDias, 2, "0", STR_PAD_LEFT);
                $trabajadores = obtenerTrabajadores($fecha);
                echo '<td>';
                echo '<strong>' . $contadorDias . '</strong><br><br>';
                foreach ($trabajadores as $trabajador) {
                    echo $trabajador . '<br>';
                }
                echo '</td>';
                $contadorDias++;
            }
        }
        echo '</tr>';
    }

    echo '</tbody></table>';
}
?>