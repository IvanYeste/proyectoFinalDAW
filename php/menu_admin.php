<?php
require_once 'config.php';
require_once 'functions.php';

$result_trabajadores = obtenerListaTrabajadores();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/menu_admin.css">
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
            <li><a id="reservaOnline" href="php/reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2)  ? '' : 'oculto'; ?>"><a id="reservas" href="../php/reservas.php">Plazas Reservadas</a></li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>"><a id="menu_trabajador" href="../php/menu_trabajador.php">Horario</a></li>
            <li  class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>"><a id="menu_admin" href="../php/menu_admin.php">Gestión</a></li>
        </ul>
    </nav>
        <div class="sesion-container">
        <?php
           if(isset($_COOKIE["nombre"])) {
            include("notificaciones.php");
           }
        ?>
        <div class="sesion-iniciada">
            <p>Bienvenido <?php echo $_COOKIE["nombre"]; ?></p>
            <i class='bx bxs-user-circle'></i>
            <form action='../index.php' method='post'>
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
            <form action="php/menu_admin.php" method="post">
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
                </form>
            </div>

            <div>
        <form action="php/menu_admin.php" method="post">
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
