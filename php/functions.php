<?php
require_once 'config.php';

function cerrarSesion(){
        setcookie("nombre", "", time() - 3600);
        setcookie("id", "", time() - 3600);
        setcookie("admin", "", time() - 3600);
        header("Location:index.php");
        exit(); // Añadido para asegurar que se detenga la ejecución después de la redirección
    }

    // 🔹 FUNCION PARA LOGIN
function loginUsuario($nombre, $password) {
    global $conexion;

    $sql = "SELECT id_usuario, nombre, pwd, tipo FROM usuarios WHERE nombre = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) { 
            $stmt->bind_result($id, $userName, $hashedPassword, $tipo);
            $stmt->fetch();

            
        // Guardar en cookies
        setcookie("id", $id, time() + 3600, "/");
        setcookie("nombre", $nombre, time() + 3600, "/");
        setcookie("admin", $tipo, time() + 3600, "/");
        return true;
            
        }
    }
    return false;
}

// 🔹 FUNCION PARA REGISTRAR USUARIO
function registrarUsuario($nombre, $apellidos, $email, $password) {
    global $conexion;

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, apellidos, pwd, `e-mail`, tipo) VALUES (?, ?, ?, ?, 2)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssss", $nombre, $apellidos, $passwordHash, $email);
        return $stmt->execute();
    }
    return false;
}

// 🔹 FUNCION PARA ELIMINAR TRABAJADOR
function eliminarTrabajador($id_trabajador) {
    global $conexion;

    $sql1 = "DELETE FROM horarios WHERE ID_trabajador = ?";
    $sql2 = "DELETE FROM solicitudes_cambio_horario WHERE ID_trabajador_envia = ? OR ID_trabajador_recibe = ?";
    $sql3 = "DELETE FROM usuarios WHERE ID_usuario = ?";

    $conexion->begin_transaction();
    try {
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param("i", $id_trabajador);
        $stmt1->execute();

        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param("ii", $id_trabajador, $id_trabajador);
        $stmt2->execute();

        $stmt3 = $conexion->prepare($sql3);
        $stmt3->bind_param("i", $id_trabajador);
        $stmt3->execute();

        $conexion->commit();
        return true;
    } catch (Exception $e) {
        $conexion->rollback();
        return false;
    }
}
// 🔹 FUNCION PARA REGISTRAR UN NUEVO TRABAJADOR
function registrarTrabajador($nombre, $apellido, $contrasena, $correo, $admin = 0) {
    global $conexion;

    $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (Nombre, Apellidos, pwd, `e-mail`, tipo) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssi", $nombre, $apellido, $passwordHash, $correo, $admin);
        return $stmt->execute();
    }
    return false;
}


// 🔹 FUNCION PARA INSERTAR HORARIOS
function insertarHorario($id_trabajador, $fecha, $hora) {
    global $conexion;

    $sql = "INSERT INTO horarios (id_trabajador, fecha, hora_inicio) VALUES (?, ?, ?)";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("iss", $id_trabajador, $fecha, $hora);
        return $stmt->execute();
    }
    return false;
}

function obtenerIdHorario($fecha, $hora) {
    global $conexion;
    $sql = "SELECT ID_horario FROM Horarios WHERE fecha = ? AND hora_inicio = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ss", $fecha, $hora);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['ID_horario'];
        }
    }
    return false;
}

function insertarSolicitudCambioHorario($id_trabajador_envia, $id_trabajador_recibe, $fecha_actual_id, $fecha_cambio_id) {
    global $conexion;
    $sql = "INSERT INTO Solicitudes_Cambio_Horario (id_trabajador_envia, id_trabajador_recibe, estado, horario_cambio, horario_actual) VALUES (?, ?, 'Pendiente', ?, ?)";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("iiii", $id_trabajador_envia, $id_trabajador_recibe, $fecha_cambio_id, $fecha_actual_id);
        return $stmt->execute();
    }
    return false;
}

function insertarReserva($id_cliente, $fecha_llegada, $hora_llegada, $fecha_salida, $hora_salida, $matricula) {
    global $conexion;
    $sql_insert = "INSERT INTO reservas (ID_cliente, Fecha_inicio, hora_inicio, hora_fin, Fecha_fin, Matricula) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conexion->prepare($sql_insert)) {
        $stmt->bind_param("isssss", $id_cliente, $fecha_llegada, $hora_llegada, $hora_salida, $fecha_salida, $matricula);
        return $stmt->execute();
    }
    return false;
}

function buscarReservas($fecha_seleccionada) {
    global $conexion;
    $consulta = "SELECT * FROM reservas WHERE Fecha_inicio <= ? AND Fecha_fin >= ?";
    if ($stmt = $conexion->prepare($consulta)) {
        $stmt->bind_param("ss", $fecha_seleccionada, $fecha_seleccionada);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// Verificar sesión y cookies
function verificarSesion() {
    return isset($_COOKIE['nombre']);
}

// Obtener lista de trabajadores
function obtenerListaTrabajadores() {
    global $conexion;
    $sql_trabajadores = "SELECT id_usuario, nombre, tipo FROM usuarios";
    return $conexion->query($sql_trabajadores);
}

if (isset($_POST['cerrar_sesion'])) {
    cerrarSesion();
}

function obtenerTrabajadores($fecha) {
    $sql_select = "SELECT * FROM solicitudes_cambio_horario WHERE Estado = 'Aceptada'";
    $result = $conexion->query($sql_select);

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

            $conexion->query($sql_update_envia); 
            $conexion->query($sql_update_recibe);
            $conexion->query($sql_update_solicitud);
        }
    }
    //Poner nombres de trabajadores en el calendarioE
    $trabajadores = array();
    
    $consulta = "SELECT DISTINCT  usuarios.Nombre FROM `horarios`INNER JOIN usuarios ON horarios.ID_trabajador = usuarios.ID_usuario where horarios.Fecha=? ORDER BY horarios.Hora_inicio asc";
    if ($stmt = $conexion->prepare($consulta)) {
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


// Procesamiento de registro
if (isset($_POST["boton_registro"])) {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $contraseña = $_POST["password"];

    registrarUsuario($nombre, $apellidos, $email, $conrtaseña);
}

if (isset($_POST["Eliminar_trabajador"])) {

    $id_trabajador_eliminar = $_POST['id_trabajador_eliminar'];

    eliminarTrabajador( $id_trabajador_eliminar);
    header("Location: ../php/menu_admin.php");
}

if (isset($_POST["Registrar_trabajador"])) {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];
    $administrador = isset($_POST['administrador']) ? 1 : 0;;
    
    registrarTrabajador($nombre, $apellido, $contrasena, $administrador);
    header("Location: menu_admin.php");
    $conexion->close();

}

if (isset($_POST["boton_insert_hora"])) {
    $id_trabajador = $_POST['id_trabajador'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    
    insertarHorario($id_trabajador, $fecha, $hora);
}

// Verifica si el formulario ha sido enviado
if (isset($_POST['submit_fecha'])) {
    $id_trabajador_envia = $_COOKIE['id'];
    $id_trabajador_recibe = $_POST['id_trabajador_recibe'];
    $fecha_actual = $_POST['fecha_actual'];
    $hora_actual = $_POST['hora_actual'];
    $fecha_cambio = $_POST['fecha_cambio'];
    $hora_cambio = $_POST['hora_cambio'];

    $fecha_actual_id = obtenerIdHorario($fecha_actual, $hora_actual);
    $fecha_cambio_id = obtenerIdHorario($fecha_cambio, $hora_cambio);

    if (!$fecha_actual_id || !$fecha_cambio_id) {
        echo "Error: No se encontró el horario en la base de datos.";
        exit();
    }

    if (insertarSolicitudCambioHorario($id_trabajador_envia, $id_trabajador_recibe, $fecha_actual_id, $fecha_cambio_id)) {
        echo "Solicitud insertada correctamente en la base de datos";
    } else {
        echo "Error al insertar la solicitud: " . $conexion->error;
    }
}
if (isset($_POST['boton_reserva'])) {
    $fecha_llegada = $_POST['fecha_llegada'];
    $hora_llegada = $_POST['hora_llegada'];
    $fecha_salida = $_POST['fecha_salida'];
    $hora_salida = $_POST['hora_salida'];
    $matricula = $_POST['matricula'];
    $id_cliente = $_COOKIE['id'];
    
    if (validarMatricula($matricula)) {
        if (insertarReserva($id_cliente, $fecha_llegada, $hora_llegada, $fecha_salida, $hora_salida, $matricula)) {
            echo "Reserva realizada correctamente.";
        } else {
            echo "Error al realizar la reserva.";
        }
    } else {
        echo "La matrícula ingresada no es válida.";
    }
    header("Location: reservaOnline.php");
}

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