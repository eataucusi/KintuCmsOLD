<?php
define('URL_BASE', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/');
define('RAIZ', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

if (is_readable(RAIZ . '..' . DIRECTORY_SEPARATOR . 'aplicacion' . DIRECTORY_SEPARATOR . 'Configuracion.php')) {
    $msj = 'ok';
}

if (isset($_POST['guardar'])) {
    foreach ($_POST as $k => $v) {
        $_POST[$k] = trim($v);
    }
    if (isset($_POST['dominio']) && !empty($_POST['dominio'])) {
        $dominio = $_POST['dominio'];
    } else {
        $error[] = 'Dominio: No puede ser nulo';
    }
    if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {
        $titulo = $_POST['titulo'];
    } else {
        $error[] = 'Titulo: No puede ser nulo';
    }
    if (isset($_POST['descripcion']) && !empty($_POST['descripcion'])) {
        $descripcion = $_POST['descripcion'];
    } else {
        $error[] = 'Descripción: No puede ser nulo';
    }
    if (isset($_POST['servidor_bd']) && !empty($_POST['servidor_bd'])) {
        $servidor_bd = $_POST['servidor_bd'];
    } else {
        $error[] = 'Servidor Bd: No puede ser nulo';
    }
    if (isset($_POST['usuario_bd']) && !empty($_POST['usuario_bd'])) {
        $usuario_bd = $_POST['usuario_bd'];
    } else {
        $error[] = 'Usuario BD: No puede ser nulo';
    }
    if (isset($_POST['clave_bd']) && !empty($_POST['clave_bd'])) {
        $clave_bd = $_POST['clave_bd'];
    } else {
        $error[] = 'Contraseña bd: No puede ser nulo';
    }
    if (isset($_POST['bd']) && !empty($_POST['bd'])) {
        $bd = $_POST['bd'];
    } else {
        $error[] = 'Base de datos: No puede ser nulo';
    }
    if (isset($_POST['correoMail']) && !empty($_POST['correoMail'])) {
        $correoMail = $_POST['correoMail'];
    } else {
        $error[] = 'Correo en Gnail: No puede ser nulo';
    }
    if (isset($_POST['claveMail']) && !empty($_POST['claveMail'])) {
        $claveMail = $_POST['claveMail'];
    } else {
        $error[] = 'Contrasena de Correo en gmail: No puede ser nulo';
    }
    if (isset($_POST['usuario']) && !empty($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
    } else {
        $error[] = 'Usuario: No puede ser nulo';
    }
    if (isset($_POST['clave']) && !empty($_POST['clave'])) {
        if (isset($_POST['reclave']) && !empty($_POST['reclave'])) {
            $clave = $_POST['clave'];
            if ($clave != $_POST['reclave']) {
                $error[] = 'Las Contraseñas no coinciden';
            }
        } else {
            $error[] = 'Repite Contraseña: No puede ser nulo';
        }
    } else {
        $error[] = 'Contraseña: No puede ser nulo';
    }
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $error[] = 'Correo: No puede ser nulo';
    }
    if (!isset($error)) {
        require_once RAIZ . 'class' . DIRECTORY_SEPARATOR . 'Bd.php';
        define('BD_HOST', $servidor_bd);
        define('BD_USER', $usuario_bd);
        define('BD_PASS', $clave_bd);
        define('BD_NAME', $bd);

        $fc = curl_init();
        curl_setopt($fc, CURLOPT_URL, URL_BASE . 'sql.sql');
        curl_setopt($fc, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($fc, CURLOPT_HEADER, 0);
        curl_setopt($fc, CURLOPT_VERBOSE, 0);
        curl_setopt($fc, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($fc, CURLOPT_TIMEOUT, 30);
        $res = htmlentities(curl_exec($fc));
        curl_close($fc);
        $res = html_entity_decode($res);
        $todo = explode(' ;---', $res);
        $todo = array_filter($todo);
        $bd = Bd::getIntancia();

        set_time_limit(0);
        try {
            foreach ($todo as $v) {
                $bd->ejecutar($v);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $msj = 'ok';
        $f = fopen(RAIZ . '..' . DIRECTORY_SEPARATOR . 'aplicacion' . DIRECTORY_SEPARATOR . 'Configuracion.php', 'x');
        fwrite($f, "<?php\r\n\r\n");
        fwrite($f, "/**Servidor de la base de datos*/\r\n");
        fwrite($f, "define('BD_HOST', '" . BD_HOST . "');\r\n\r\n");
        fwrite($f, "/**Usuario de la base de datos*/\r\n");
        fwrite($f, "define('BD_USER', '" . BD_USER . "');\r\n\r\n");
        fwrite($f, "/**Password de la base de datos*/\r\n");
        fwrite($f, "define('BD_PASS', '" . BD_PASS . "');\r\n\r\n");
        fwrite($f, "/**Nombre de la base de datos*/\r\n");
        fwrite($f, "define('BD_NAME', '" . BD_NAME . "');\r\n\r\n");
        fclose($f);

        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($dominio, 'dominio'));
        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($titulo, 'titulo'));
        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($descripcion, 'meta'));
        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($correoMail, 'correoMail'));
        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($claveMail, 'claveMail'));
        $hash = uniqid();
        $bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($hash, 'hash'));

        $hash = hash_init('md5', HASH_HMAC, $hash);
        hash_update($hash, $clave);
        $clave = hash_final($hash);
        $bd->ejecutar('UPDATE usuarios SET login = ?, email = ?, pass = ? WHERE id = ?', array($usuario, $email, $clave, '2'));
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta lang="es" charset="utf-8">
        <base href="<?php echo URL_BASE ?>">
        <title>Instalacion de KintuCms - Un simple Cms</title>
        <link href="css/estilos.css" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico">
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/funciones.js"></script>
    </head>
    <body>
        <div id="contenedor">
            <h1>Instalacion de KintuCms - Un simple Cms</h1>
            <?php if (!isset($msj)) : ?>
                <?php if (isset($error)) : ?>
                    <div class="error">
                        <?php for ($i = 0; $i < count($error); $i++) : ?>
                            <p><?php echo '* ', $i + 1, ' ', $error[$i] ?></p>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="">
                    <input type="hidden" name="guardar" value="1">
                    <div class="field">
                        <h2>Información de Sitio</h2>
                        <p>
                            <label for="dominio">Dominio:</label>
                            <input type="text" name="dominio" id="dominio" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($dominio) ? $dominio : '' ?>">
                        </p>
                        <p>
                            <label for="titulo">Título del Sitio:</label>
                            <input type="text" name="titulo" id="titulo" required placeholder="Requerido" class="in largo"
                                   value="<?php echo isset($titulo) ? $titulo : '' ?>">
                        </p>
                        <p>
                            <label for="descripcion">Descripción del Sitio (Descripción general del sitio web para los motores de búsqueda ):</label>
                            <input type="text" name="descripcion" id="descripcion" required placeholder="Requerido" class="in largo"
                                   value="<?php echo isset($descripcion) ? $descripcion : '' ?>">
                        </p>
                    </div>
                    <div class="field">
                        <h2>Información de Base de Datos en MySQL</h2>
                        <p>
                            <label for="servidor_bd">Servidor de la base de datos (Por lo general localhost):</label>
                            <input type="text" name="servidor_bd" id="servidor_bd" required placeholder="Requerido" class="in" 
                                   value="<?php echo isset($servidor_bd) ? $servidor_bd : 'localhost' ?>">
                        </p>
                        <p>
                            <label for="usuario_bd">Usuario de la base de datos (Un nombre de usuario dado por el host):</label>
                            <input type="text" name="usuario_bd" id="usuario_bd" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($usuario_bd) ? $usuario_bd : '' ?>">
                        </p>
                        <p>
                            <label for="clave_bd">Contraseña de la base de datos: (Contraseña para el nombre de usuario dado por el host)</label>
                            <input type="password" name="clave_bd" id="clave_bd" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($clave_bd) ? $clave_bd : '' ?>">
                        </p>
                        <p>
                            <label for="bd">Nombre de la base de datos (Nombre de la base de datos dado por el host):</label>
                            <input type="text" name="bd" id="bd" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($bd) ? $bd : '' ?>">
                        </p>
                        <p><input type="button" value="Probar Conección" class="submit" id="testbd"></p>
                        <p id="statusbd">Porfavor compruebe la conección</p>
                    </div>
                    <div class="field">
                        <h2>Información de Envío de Correo</h2>
                        <p>
                            <label for="correoMail">Correo en Gmail (Establesca un Correo para el envío masivo de correos):</label>
                            <input type="text" name="correoMail" id="correoMail" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($correoMail) ? $correoMail : '' ?>">
                        </p>
                        <p>
                            <label for="claveMail">Contraseña de Correo en Gmail:</label>
                            <input type="text" name="claveMail" id="claveMail" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($claveMail) ? $claveMail : '' ?>">
                        </p>
                    </div>
                    <div class="field">
                        <h2>Información de Super Administrador</h2>
                        <p>
                            <label for="usuario">Usuario (Usted puede cambiar el nombre de usuario predeterminado):</label>
                            <input type="text" name="usuario" id="usuario" required placeholder="Requerido" class="in" 
                                   value="<?php echo isset($usuario) ? $usuario : 'administrador' ?>">
                        </p>
                        <p>
                            <label for="clave">Contraseña (Establezca la contraseña para la cuenta de Super Administrador):</label>
                            <input type="password" name="clave" id="clave" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($clave) ? $clave : '' ?>">
                        </p>
                        <p>
                            <label for="reclave">Repite Contraseña (Confirme la contraseña para la cuenta de Super Administrador):</label>
                            <input type="password" name="reclave" id="reclave" required placeholder="Requerido" class="in">
                        </p>
                        <p>
                            <label for="email">Correo (Esta será la dirección de correo electrónico del sitio Web vinculada al Super Administrador) :</label>
                            <input type="text" name="email" id="email" required placeholder="Requerido" class="in"
                                   value="<?php echo isset($email) ? $email : '' ?>">
                        </p>
                    </div>
                    <p><input type="submit" value="Instalar" class="submit"></p>
                </form>
            <?php else : ?>
                <div class="correcto">
                    <p>KintuCms se ha instalado correctamente, <a id="delete" href="<?php echo URL_BASE ?>eliminar.php">Eliminar la carpeta Instalación</a></p>
                    <p id="deletestatus"></p>
                </div>            
            <?php endif; ?>
        </div>
    </body>
</html>
