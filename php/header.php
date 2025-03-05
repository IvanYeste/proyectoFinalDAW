<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/PROYECTOFINALDAW/php/config.php'; ?>

<head>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/header.css">
</head>

<header>
    <div class="logo-container">
        <img src="<?php echo BASE_URL; ?>src/logo.png" width="100px" height="100px" alt="Logo" id="logo">
    </div>
    <nav>
        <ul>
            <li><a id="inicio" href="<?php echo BASE_URL; ?>index.php">Inicio</a></li>
            <li><a href="<?php echo BASE_URL; ?>php/contacto.php">Contacto</a></li>
            <li><a id="reservaOnline" href="<?php echo BASE_URL; ?>php/reservaOnline.php" onclick="verificarRegistro(event)">Reserva Online</a></li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] != 2) ? '' : 'oculto'; ?>">
                <a id="reservas" href="<?php echo BASE_URL; ?>php/reservas.php">Plazas Reservadas</a>
            </li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 0) ? '' : 'oculto'; ?>">
                <a id="menu_trabajador" href="<?php echo BASE_URL; ?>php/menu_trabajador.php">Horario</a>
            </li>
            <li class="<?php echo (isset($_COOKIE['nombre']) && $_COOKIE['admin'] == 1) ? '' : 'oculto'; ?>">
                <a id="menu_admin" href="<?php echo BASE_URL; ?>php/menu_admin.php">Gestión</a>
            </li>
        </ul>
    </nav>
    <div class="sesion-container">
    <?php
        // Verificar si el usuario ha iniciado sesión
        if(isset($_COOKIE["nombre"])) {
        ?>
        <div class="sesion-iniciada">
            <p>Bienvenido <?php echo $_COOKIE["nombre"]; ?></p>
            <i class='bx bxs-user-circle'></i>
            <form action='<?php echo BASE_URL; ?>index.php' method='post'>
                <input type='submit' id='cerrar_sesion' name='cerrar_sesion' value='Cerrar Sesión'>
            </form>
           
        </div>
        <?php
        // Si se hace clic en el botón de cerrar sesión, llamar a la función cerrarSesion()
        if(isset($_POST["cerrar_sesion"])) {
            cerrarSesion();
        }
        } else {
        // Mostrar el formulario de inicio de sesión
        ?>
            <form   method='post'>  
                    <div>
                    <i class="bx bx-user"></i>
                    <input type="text" id='nombre' name='nombre' placeholder="Usuario">
                </div>
                    <div>
                    <i class="bx bx-lock"></i>
                    <input type="password" id='contraseña' name='contraseña' placeholder="Contraseña">
                </div>
                <div class = "sesion-botones">
                    <input type="submit"id='boton_index' name='boton_index' value="Iniciar sesión">
                    <button type="button" id="btn-registro">Registrarse</button>
                </div>
            </form>

            <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Registro</h2>
                <form id="registro-form" action='index.php' method='post'>
                    <div class="form-group">
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
                        <label for="nombre"><i class="bx bx-user"></i></label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" required>
                        <label for="apellidos"><i class="bx bx-user"></i></label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="email" name="email" placeholder="Email" required>
                        <label for="email"><i class="bx bx-envelope"></i></label>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                        <label for="password"><i class="bx bx-lock"></i></label>
                    </div>
                    <button type="submit" id="boton_registro" name="boton_registro">Registrar</button>
                </form>
            </div>

        </div>
        <?php
        }
   
   

?> 
    </div>
    </header>