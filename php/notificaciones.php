<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/notificaciones.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    
</head>
<body>
            <div id="ContenedorNotificaciones">
            <button type="button" class="icon-button" onclick=toggleAnimation()>
                <span class="material-icons">notifications</span>
                <span id = "iconButtonBadge" class="icon-button__badge"></span>
            </button>
        <div id="notificaciones">
        <?php
                $conn = new mysqli("localhost", "root", "", "parking");

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }
        

                $sql = "SELECT ID_solicitud, t1.Nombre AS nombre_trabajador, t2.Nombre AS nombre_trabajador_envia, h1.Fecha AS dia_normal, h2.Fecha AS dia_a_cambiar, h1.hora_inicio AS hora_normal, h2.hora_inicio AS hora_cambio
                FROM solicitudes_cambio_horario AS sch
                INNER JOIN usuarios AS t1 ON sch.ID_trabajador_recibe = t1.ID_usuario
                INNER JOIN usuarios AS t2 ON sch.ID_trabajador_envia = t2.ID_usuario
                INNER JOIN horarios AS h1 ON sch.Horario_actual = h1.ID_horario
                INNER JOIN horarios AS h2 ON sch.Horario_cambio = h2.ID_horario
                WHERE sch.ID_trabajador_recibe = $_COOKIE[id] AND sch.Estado = 'Pendiente'";
        
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                ?>
                <script>
                    document.getElementById("iconButtonBadge").style.display = "flex";
                    document.getElementById("iconButtonBadge").textContent = <?php echo $result->num_rows; ?>
                </script>
                <?php
            while ($row = $result->fetch_assoc()) {
                
                ?>
                <form method="post" >
                    <?php                     echo '<p>' . $row["nombre_trabajador"] . ', tienes una solicitud de ' . $row["nombre_trabajador_envia"] . ', quiere cambiar ' . $row["dia_normal"] .'a las '.$row["hora_normal"] .' por ' . $row["dia_a_cambiar"] .'a las '.$row["hora_cambio"] . ', ¿estás de acuerdo?</p>';?>
                    <input type="hidden" name="id_solicitud" value="<?php echo $row['ID_solicitud']; ?>">
                    <button type="submit" name="aceptarSolicitud" value="aceptar">Aceptar</button>
                    <button type="submit" name="rechazarSolicitud" value="rechazar">Rechazar</button>
                </form>
                        <?php
                    }
                } else {
                    echo "No hay solicitudes de cambio para este trabajador.";
                }

                if(isset($_POST["aceptarSolicitud"])){
                    $id_solicitud = $_POST["id_solicitud"];
                    $sql = "UPDATE solicitudes_cambio_horario SET Estado = 'Aceptada' WHERE ID_solicitud = $id_solicitud";
                    $conn->query($sql) ;
                }

                if(isset($_POST["rechazarSolicitud"])){
                    $id_solicitud = $_POST["id_solicitud"];
                    $sql = "UPDATE solicitudes_cambio_horario SET Estado = 'Rechazada' WHERE ID_solicitud = $id_solicitud";
                    if ($conn->query($sql) === TRUE) {
                        echo "Solicitud rechazada correctamente";
                    } else {
                        echo "Error al rechazar la solicitud: " . $conn->error;
                    }
                }   
            ?>
        </div>
    </div>
</body>
</html>