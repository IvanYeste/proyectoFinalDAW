<?php
                if (isset($_POST["Eliminar_trabajador"])) {
                    $conn = new mysqli("localhost", "root", "", "parking");

                    $id_trabajador_eliminar = $_POST['id_trabajador_eliminar'];
                
                    // Eliminar registros de la tabla horarios
                    $sql_delete_horarios = "DELETE FROM horarios WHERE ID_trabajador = ?";
                    $stmt_delete_horarios = $conn->prepare($sql_delete_horarios);
                    $stmt_delete_horarios->bind_param("i", $id_trabajador_eliminar);
                
                    if ($stmt_delete_horarios->execute()) {
                        // Eliminar registros de la tabla solicitudes_cambio_horario
                        $sql_delete_solicitudes = "DELETE FROM solicitudes_cambio_horario WHERE ID_trabajador_envia = ? OR ID_trabajador_recibe = ?";
                        $stmt_delete_solicitudes = $conn->prepare($sql_delete_solicitudes);
                        $stmt_delete_solicitudes->bind_param("ii", $id_trabajador_eliminar, $id_trabajador_eliminar);
                
                        if ($stmt_delete_solicitudes->execute()) {
                            // Eliminar el registro de la tabla usuarios
                            $sql_delete_usuario = "DELETE FROM usuarios WHERE ID_usuario = ?";
                            $stmt_delete_usuario = $conn->prepare($sql_delete_usuario);
                            $stmt_delete_usuario->bind_param("i", $id_trabajador_eliminar);
                            
                
                            if ($stmt_delete_usuario->execute()) {
                                echo "Trabajador eliminado correctamente.";
                            } else {
                                echo "Error al eliminar el trabajador de la tabla usuarios: " . $conn->error;
                            }
                        } else {
                            echo "Error al eliminar los registros de solicitudes de cambio de horario relacionados con el trabajador: " . $conn->error;
                        }
                    } else {
                        echo "Error al eliminar los registros de horarios relacionados con el trabajador: " . $conn->error;
                    }
                
                    // Cerrar las declaraciones preparadas
                    $stmt_delete_horarios->close();
                    $stmt_delete_solicitudes->close();
                    $stmt_delete_usuario->close();
                    header("Location: menu_admin.php");
                    $conn->close();
                }
               
                if (isset($_POST["Registrar_trabajador"])) {
                    $conn = new mysqli("localhost", "root", "", "parking");

                    $nombre = $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $contrasena = $_POST['contrasena'];
                    $correo = $_POST['correo'];
                    $administrador = isset($_POST['administrador']) ? 1 : 0;;
                    
                    $sql = "INSERT INTO `usuarios`(`Nombre`, `Apellidos`, `pwd`, `e-mail`, `tipo`) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssss", $nombre, $apellido, $contrasena, $correo, $administrador);
                
                    if ($stmt->execute()) {
                        
                        echo "Datos insertados correctamente.";
                    } else {
                        echo "Error al insertar datos: " . $conn->error;
                    }
                    
                    $stmt->close();
                    header("Location: menu_admin.php");
                    $conn->close();

                }

            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="menu_admin.css">
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
            <li><a id="inicio" href="index.php">Inicio</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a id="reservaOnline" href="reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2)  ? '' : 'oculto'; ?>"><a id="reservas" href="reservas.php">Plazas Reservadas</a></li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>"><a id="menu_trabajador" href="menu_trabajador.php">Horario</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>"><a id="menu_admin" href="menu_admin.php">Gestión</a></li>
        </ul>
    </nav>
        <div class="sesion-container">
        <?php
            include("notificaciones.php");
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
    <?php
        $conn = new mysqli("localhost", "root", "", "parking");

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql_trabajadores = "SELECT id_usuario, nombre, tipo FROM usuarios";
        $result_trabajadores = $conn->query($sql_trabajadores);

    ?>
    
    <div class="form-container">
        <div>
            <form action="menu_admin.php" method="post">
                <div>
                <p>Insertar Horario</p>
                </div>
                    <label for="id_trabajador">ID del Trabajador :</label><br>
                    <select id="id_trabajador" name="id_trabajador">
                        <?php
                            // Iterar sobre los resultados de la consulta para crear las opciones del select
                            if ($result_trabajadores->num_rows > 0) {
                                while($row = $result_trabajadores->fetch_assoc()) {
                                    if($row["tipo"] == 0){
                                        echo "<option value='" . $row["id_usuario"] . "'>" . $row["nombre"] . "</option>";
                                    }
                                }
                            }
                        ?>
                    </select><br>
                    
                    <label for="fecha">Fecha :</label><br>
                    <input type="date" id="fecha" name="fecha"><br>
                    
                    <label for="hora">Hora :</label><br>
                    <select id="hora" name="hora">
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="14:00">14:00</option>
                    </select><br>

                    <input type="submit" name="boton_insert_hora" value="Enviar Solicitud">

                    <?php
                        // Verificar si se envió el formulario
                        if (isset($_POST["boton_insert_hora"])) {
                            $id_trabajador = $_POST['id_trabajador'];
                            $fecha = $_POST['fecha'];
                            $hora = $_POST['hora'];
                            
                            $sql = "INSERT INTO horarios (id_trabajador, fecha, hora_inicio) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("iss", $id_trabajador, $fecha, $hora);
                        
                            if ($stmt->execute()) {
                                echo "Datos insertados correctamente.";
                            } else {
                                echo "Error al insertar datos: " . $conn->error;
                            }
                        
                            $stmt->close();
                        }

                    
                        
                    ?>
                </form>
            </div>

            <div>
        <form action="menu_admin.php" method="post">
            <div>
                <p>Eliminar un Horario</p>
            </div>
            <label for="id_trabajador_modificar_horario">ID del Trabajador:
            <select id="id_trabajador_modificar_horario" name="id_trabajador_modificar_horario">
                <?php
                // Consulta para obtener los IDs de los trabajadores
                $sql_trabajadores = "SELECT ID_usuario, nombre FROM usuarios where tipo = 0";
                $result_trabajadores = $conn->query($sql_trabajadores);

                // Iterar sobre los resultados y mostrar opciones en el select
                if ($result_trabajadores->num_rows > 0) {
                    while($row = $result_trabajadores->fetch_assoc()) {
                        echo "<option value='" . $row["ID_usuario"] . "'>"  . $row["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
            </label><br>

            <input type="submit" name="Seleccionar_trabajador_horario" value="Seleccionar">
        </form>

        <?php
        if (isset($_POST["Seleccionar_trabajador_horario"])) {
            $id_trabajador = $_POST['id_trabajador_modificar_horario'];
            // Consulta para obtener las horas asociadas al trabajador seleccionado
            $sql_horarios = "SELECT ID_horario, Fecha, Hora_inicio FROM horarios WHERE ID_trabajador = $id_trabajador";
            $result_horarios = $conn->query($sql_horarios);
        ?>
        <form action="menu_admin.php" method="post"><br>
            <label for="id_horario_modificar">Selecciona el Horario: </label><br>
            <select id="id_horario_modificar" name="id_horario_modificar">
                <?php
                // Iterar sobre los resultados y mostrar opciones en el select
                if ($result_horarios->num_rows > 0) {
                    while($row = $result_horarios->fetch_assoc()) {
                        echo "<option value='" . $row["ID_horario"] . "'>" . $row["Fecha"] . " " . $row["Hora_inicio"] . "</option>";
                    }
                }
                ?>
            </select><br>
            <input type="submit" name="Seleccionar_horario" value="Seleccionar">
        
            <?php
            }
            if(isset($_POST['Seleccionar_horario'])) {
                // Obtener el ID del horario seleccionado
                $id_horario = $_POST['id_horario_modificar'];
                
                // Verificar la conexión
                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }
                
                // Verificar si hay solicitudes de cambio horario asociadas al horario actual o al horario de cambio
                $sql_count = "SELECT COUNT(*) AS total FROM solicitudes_cambio_horario WHERE Horario_actual = $id_horario OR Horario_cambio = $id_horario";
                $result = $conn->query($sql_count);
                
                if ($result && $row = $result->fetch_assoc()) {
                    // Si hay solicitudes de cambio horario asociadas, eliminarlas primero
                    if ($row['total'] > 0) {
                        $sql_delete_solicitudes = "DELETE FROM solicitudes_cambio_horario WHERE Horario_actual = $id_horario OR Horario_cambio = $id_horario";
                        $conn->query($sql_delete_solicitudes);
                    }
                    
                    // Ahora puedes eliminar el horario seleccionado
                    $sql_delete_horario = "DELETE FROM horarios WHERE ID_horario = $id_horario";
                    if ($conn->query($sql_delete_horario) === TRUE) {
                        echo "Horario eliminado correctamente.";
                    } else {
                        echo "Error al eliminar el horario: " . $conn->error;
                    }
                } else {
                    echo "Error al verificar las solicitudes de cambio horario.";
                }
            }
            ?>
        </form>
        </div>

            <div>
                <form action="menu_admin.php" method="post">
                    <div>
                    <p>Registrar Nuevo Trabajador</p>
                    </div>
                    <label for="nombre">Nombre: <input type="text" id="nombre" name="nombre" required></label><br>
                    
                    <label for="apellido">Apellidos: <input type="text" id="apellido" name="apellido" required></label><br>
                    
                    <label for="contrasena">Contraseña:  <input type="password" id="contrasena" name="contrasena" required></label><br>
                
                    <label for="correo">Correo Electrónico:<input type="email" id="correo" name="correo" required></label><br>
                    
                    <label for="administrador"> <input type="checkbox" id="administrador" name="administrador" value="1"> ¿Es administrador?    </label>

                    <input type="submit" name="Registrar_trabajador" value="Registrar Trabajador">


                </form>
            </div>
            
            <div>
            <form action="menu_admin.php" method="post">
            <div>
                <p>Eliminar Trabajador</p>
            </div>
            <label for="id_trabajador_eliminar">ID del Trabajador a Eliminar:</label>
        <select id="id_trabajador_eliminar" name="id_trabajador_eliminar" required>
            <?php
            // Consulta para obtener los IDs y nombres de los trabajadores
            $sql_trabajadores_eliminar = "SELECT ID_usuario, nombre FROM usuarios where tipo = 0";
            $result_trabajadores_eliminar = $conn->query($sql_trabajadores_eliminar);

            // Iterar sobre los resultados y mostrar opciones en el select
            if ($result_trabajadores_eliminar->num_rows > 0) {
                while ($row = $result_trabajadores_eliminar->fetch_assoc()) {
                    echo "<option value='" . $row["ID_usuario"] . "'>" . $row["ID_usuario"] . " - " . $row["nombre"] . "</option>";
                }
            }
            ?>
        </select><br>
            <input type="submit" name="Eliminar_trabajador" value="Eliminar Trabajador">
            
            </form>

        </div>
        
    </div>
    <div class="containerBloques">
        <div class="bloqueCentral">
        <div id="calendario">
            <?php
            $mesActual = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
            $añoActual = isset($_GET['año']) ? (int)$_GET['año'] : date('Y');
            generarCalendario($mesActual, $añoActual);
            ?>
        </div>

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
    <?php
                $conn->close();
    ?>
    <footer>
     <p>&copy; 2024 Parking. Todos los derechos reservados.</p>
    </footer>
    </div>
    </div>
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