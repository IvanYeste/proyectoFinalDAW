<?php
require_once 'config.php';
require_once 'functions.php';

$result_trabajadores = obtenerListaTrabajadores();
?>
<!-- Resto del código HTML -->



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
    <?php require_once '../php/header.php'; ?>
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
            <form action="../php/menu_admin.php" method="post">
                <div>
                <p>Insertar Horario</p>
                </div>
                    <label for="id_trabajador">ID del Trabajador :</label><br>
                    <select id="id_trabajador" name="id_trabajador">
                    <?php foreach (obtenerTrabajadoresNormales() as $trabajador): ?>
                        <option value="<?= $trabajador["ID_usuario"]; ?>"><?= $trabajador["nombre"]; ?></option>
                    <?php endforeach; ?>
                </select>
                    
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
        <!-- Formulario para seleccionar trabajador y ver sus horarios -->
<form action="menu_admin.php" method="post">
    <p>Eliminar un Horario</p>

    <label>ID del Trabajador:</label>
    <select id="id_trabajador_modificar_horario" name="id_trabajador_modificar_horario">
        <?php foreach (obtenerTrabajadoresNormales() as $trabajador): ?>
            <option value="<?= $trabajador["ID_usuario"]; ?>"><?= $trabajador["nombre"]; ?></option>
        <?php endforeach; ?>
    </select>
    
    <input type="submit" name="Seleccionar_trabajador_horario" value="Seleccionar">
</form>

<?php if (isset($_POST["Seleccionar_trabajador_horario"])): ?>
    <?php $horarios = obtenerHorariosTrabajador($_POST['id_trabajador_modificar_horario']); ?>
    
    <!-- Mostrar los horarios del trabajador seleccionado -->
    <form action="menu_admin.php" method="post">
        <label>Selecciona el Horario a Eliminar:</label>
        <select id="id_horario_modificar" name="id_horario_modificar">
            <?php foreach ($horarios as $horario): ?>
                <option value="<?= $horario["ID_horario"]; ?>">
                    <?= $horario["Fecha"] . " " . $horario["Hora_inicio"]; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="Eliminar_horario" value="Eliminar Horario">
    </form>
<?php endif; ?>

<?php
// Acción para eliminar el horario seleccionado
if (isset($_POST['Eliminar_horario'])) {
    $id_horario = $_POST['id_horario_modificar'];
    eliminarHorario($id_horario);
}
?>

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
