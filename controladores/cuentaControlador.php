<?php

/**
 * Archivo 'cuentaControlador'
 * 
 * En este archivo de define la clase 'cuentaControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 13/03/2013 01:37:50 PM
 */

/**
 * Clase 'cuentaControlador'
 * 
 * Esta clase manejar las cuantas de usuario.
 * 
 * @package Controlador
 */
class cuentaControlador extends Controlador {
    /*     * @var usuarioModelo */

    private $_usuario;

    public function __construct() {
        parent::__construct();

        $this->_usuario = $this->getModelo('usuario');
    }

    public function index() {
        if (!Sesion::accesoVista('Registrado')) {
            $this->redireccionar('cuenta/login');
        }
        $this->_vista->datos = $this->_usuario->getUsuario(Sesion::get('id'));
        $this->_vista->titulo = 'Mi cuenta';
        $this->_vista->renderizar('index');
    }

    public function nuevo() {
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('nombre')) {
                $this->_vista->error[] = 'Nombre completo: utiliza solo letras, 15 caracteres como mínimo.';
            } else {
                if (strlen($this->getTexto('nombre')) < 15) {
                    $this->_vista->error[] = 'Nombre completo: utiliza solo letras, 15 caracteres como mínimo.';
                }
            }
            if (!$this->getAlphaNum('login')) {
                $this->_vista->error[] = 'Login: utiliza sólo letras (a-z), guion, subguión y números, 6 caracteres como mínimo.';
            } else {
                if ($this->_usuario->existeLogin($this->getAlphaNum('login'))) {
                    $this->_vista->error[] = 'Login: este usuario ya existe.';
                }
            }
            if (!$this->getEmail('email')) {
                $this->_vista->error[] = 'Email: utiliza un email válido.';
            } else {
                if ($this->_usuario->existeEmail($this->getEmail('email'))) {
                    $this->_vista->error[] = 'Email: este email ya existe.';
                }
            }

            if (!$this->getPass('pass')) {
                $this->_vista->error[] = 'Contraseña: utiliza cualquier caracter, 6 como mínimo.';
            }
            if ($this->getPass('pass') != $this->getPass('repass')) {
                $this->_vista->error[] = 'Contraseña: las contraseñas no coinciden.';
            }
            if (!isset($this->_vista->error)) {
                $this->_usuario->insertarUsuario(2, $this->getAlphaNum('login'), $this->getTexto('nombre'), $this->getEmail('email'), $this->getPass('pass'), rand(1000000000, 9999999999));

                $usuario = $this->_usuario->getUsuarioLogin($this->getAlphaNum('login'));
                $this->getLibreria('mail' . SD . 'class.phpmailer');
                $mail = new PHPMailer();
                $mail->From = MAIL_USER;
                $mail->FromName = DOMINIO;
                $mail->Subject = 'Activacion de cuenta de usuario';
                $mail->Body = 'Hola <strong>' . $usuario['login'] . '</strong>,' .
                        '<p>Se ha registrado en <strong>' . DOMINIO . '</strong> para activar su cuenta ' .
                        'haga clic sobre el siguiente enlace <br>' .
                        '<a href="' . URL_BASE . 'cuenta/activar/' .
                        $usuario['codigo'] . '/' . $usuario['id'] . '">' .
                        URL_BASE . 'cuenta/activar/' .
                        $usuario['codigo'] . '/' . $usuario['id'] . '</a></p>';
                $mail->AltBody = 'Su servidor de correo no soporta html';
                $mail->AddAddress($usuario['email'], $usuario['nombre']);
                if (!$mail->Send()) {
                    $mail->IsSMTP();
                    $mail->SMTPAuth = TRUE;
                    $mail->SMTPSecure = 'ssl';
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 465;
                    $mail->Username = MAIL_USER;
                    $mail->Password = MAIL_PASS;
                    if (!$mail->Send()) {
                        $this->redireccionar('traspie/acceso/405');
                    }
                }
                Sesion::set('msj', 'Registro iniciado.');
                Sesion::set('email', $usuario['email']);
                $this->redireccionar('cuenta/nuevo');
            }
        }
        $this->_vista->titulo = 'Crear cuenta en ' . DOMINIO;
        $this->_vista->renderizar('nuevo');
    }

    public function activar($codigo, $id) {
        $id = $this->aInt($id);
        $codigo = $this->aInt($codigo);
        if (!$id || !$codigo) {
            $this->redireccionar('traspie/acceso/404');
        }
        $usuario = $this->_usuario->getUsuario($id);
        if (!$usuario) {
            $this->_vista->error = 'Esta cuenta no existe.';
        } elseif ($usuario['codigo'] != $codigo) {
            $this->_vista->error = 'Esta cuenta no existe.';
        } elseif ($usuario['estado'] == 2) {
            $this->_vista->msj = 'Esta cuenta ya ha sido activada.';
        } else {
            $this->_usuario->activarUsuario($id);
            $this->_vista->msj = 'Su cuenta fue activada.';
        }
        $this->_vista->titulo = 'Activar cuenta';
        $this->_vista->renderizar('activar');
    }

    public function editar_datos() {
        Sesion::acceso('Registrado');
        $this->_vista->datos = $this->_usuario->getUsuario(Sesion::get('id'));
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('nombre')) {
                $this->_vista->error[] = 'Nombre completo: utiliza solo letras, 15 caracteres como mínimo.';
            } else {
                if (strlen($this->getTexto('nombre')) < 15) {
                    $this->_vista->error[] = 'Nombre completo: utiliza solo letras, 15 caracteres como mínimo.';
                }
            }
            if (!$this->getAlphaNum('login')) {
                $this->_vista->error[] = 'Login: utiliza sólo letras (a-z), guion, subguión y números, 6 caracteres como mínimo.';
            } else {
                $id = $this->_usuario->existeLogin($this->getAlphaNum('login'));
                if ($id) {
                    if ($id != Sesion::get('id')) {
                        $this->_vista->error[] = 'Login: este usuario ya existe.';
                    }
                }
            }
            if (!$this->getEmail('email')) {
                $this->_vista->error[] = 'Email: utiliza un email válido.';
            } else {
                $id = $this->_usuario->existeEmail($this->getEmail('email'));
                if ($id) {
                    if ($id != Sesion::get('id')) {
                        $this->_vista->error[] = 'Email: este email ya existe.';
                    }
                }
            }
            if (!$this->getTexto('acerca')) {
                $this->_vista->error[] = 'Acerca de mí: este campo no puede ser vacío.';
            }
            if (!isset($this->_vista->error)) {
                $this->_usuario->editarDatos(Sesion::get('id'), $this->getTexto('nombre'), $this->getAlphaNum('login'), $this->getEmail('email'), $this->getTexto('acerca'));
                Sesion::set('msj', 'Se ha guardado su modificación');
                $this->redireccionar('cuenta');
            }
        }
        $this->_vista->titulo = 'Editar datos';
        $this->_vista->renderizar('editar_datos');
    }

    public function cambiar_imagen() {
        Sesion::acceso('Registrado');
        if ($this->getInt('guardar') == 1) {
            $this->getLibreria('Imagen');
            $img = new Imagen();
            $img->setImagen('foto');
            if ($img->subir(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD)) {
                $img->getImagen($img->getDireccion());
                $img->ajustar(64, 64);
                $img->guardar(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD);
                $usuario = $this->_usuario->getUsuario(Sesion::get('id'));
                if ($usuario['foto'] != 'usuario.jpg') {
                    unlink(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD . $usuario['foto']);
                }
                $this->_usuario->cambiarFoto(Sesion::get('id'), $img->_nuevoNombre);
                Sesion::set('foto', $img->_nuevoNombre);
                Sesion::set('msj', 'Se ha guardado su modificación.');
                $this->redireccionar('cuenta');
            }
            $this->_vista->error = $img->getMensaje();
        }
        $this->_vista->titulo = 'Cambiar imagen';
        $this->_vista->renderizar('cambiar_imagen');
    }

    public function editar_pass() {
        Sesion::acceso('Registrado');
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getPass('pass')) {
                $this->_vista->error[] = 'Contraseña: utiliza cualquier caracter, 6 como mínimo.';
            }
            if (!$this->getPass('new-pass')) {
                $this->_vista->error[] = 'Nueva contraseña: utiliza cualquier caracter, 6 como mínimo.';
            }
            if ($this->getPass('new-pass') != $this->getPass('re-pass')) {
                $this->_vista->error[] = 'Las contraseñas nuevas: no coinciden.';
            }
            $usuario = $this->_usuario->getUsuario(Sesion::get('id'));
            if ($usuario['pass'] != $this->getPass('pass')) {
                $this->_vista->error[] = 'Contraseña actual: incorrecto.';
            }
            if (!isset($this->_vista->error)) {
                $this->_usuario->cambiarPass(Sesion::get('id'), $this->getPass('new-pass'));
                Sesion::set('msj', 'Se ha guardado su modificación.');
                $this->redireccionar('cuenta');
            }
        }
        $this->_vista->titulo = 'Editar contraseña';
        $this->_vista->renderizar('editar_pass');
    }

    public function login() {
        if (Sesion::accesoVista('Registrado')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            $id = $this->_usuario->loginUsuario($this->getAlphaNum('login'), $this->getPass('pass'));
            if ($id) {
                $usuario = $this->_usuario->getUsuario($id);
                if ($usuario['estado'] == 2) {
                    Sesion::regenerar();
                    Sesion::set('id', $usuario['id']);
                    Sesion::set('login', $usuario['login']);
                    Sesion::set('nivel', $usuario['role_id']);
                    Sesion::set('foto', $usuario['foto']);
                    Sesion::set('autenticado', TRUE);
                    Sesion::set('tiempo', time());
                    Sesion::set('ip', $_SERVER['REMOTE_ADDR']);
                    $this->redireccionar();
                } else {
                    $this->_vista->error[] = 'Usuario deshabilitado: aún no has activado cuenta o el admisnitrador descativó su cuenta, contáctate con el administrador.';
                }
            } else {
                $this->_vista->error[] = 'Login y contraseña no coinciden.';
            }
        }
        $this->_vista->titulo = 'Iniciar sesión';
        $this->_vista->renderizar('login');
    }

    public function recuperar() {
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getEmail('email')) {
                $this->_vista->error[] = 'Email: utiliza un email válido.';
            } else {
                $id = $this->_usuario->existeEmail($this->getEmail('email'));
                if (!$id) {
                    $this->_vista->error[] = 'Email: no existe en nuestra base de datos.';
                } else {
                    $codigo = time() + 600;
                    $this->_usuario->recuperarUsuario($id, $codigo);
                    $usuario = $this->_usuario->getUsuario($id);
                    $this->getLibreria('mail' . SD . 'class.phpmailer');
                    $mail = new PHPMailer();
                    $mail->From = MAIL_USER;
                    $mail->FromName = DOMINIO;
                    $mail->Subject = 'Recuperar cuenta de usuario';
                    $mail->Body = 'Hola <strong>' . $usuario['login'] . '</strong>,' .
                            '<p>Ha pedido recupear su cuenta en <strong>' . DOMINIO . '</strong> para recuperar su cuenta ' .
                            'haga clic sobre el siguiente enlace antes que transcurra 10 minutos: <br>' .
                            '<a href="' . URL_BASE . 'cuenta/confirmar/' .
                            $usuario['codigo'] . '/' . $usuario['id'] . '">' .
                            URL_BASE . 'cuenta/confirmar/' .
                            $usuario['codigo'] . '/' . $usuario['id'] . '</a></p>';
                    $mail->AltBody = 'Su servidor de correo no soporta html';
                    $mail->AddAddress($usuario['email'], $usuario['nombre']);
                    if (!$mail->Send()) {
                        $mail->IsSMTP();
                        $mail->SMTPAuth = TRUE;
                        $mail->SMTPSecure = 'ssl';
                        $mail->Host = "smtp.gmail.com";
                        $mail->Port = 465;
                        $mail->Username = MAIL_USER;
                        $mail->Password = MAIL_PASS;
                        if (!$mail->Send()) {
                            $this->redireccionar('traspie/acceso/405');
                        }
                    }
                    Sesion::set('msj', 'Recuperación iniciada.');
                    $this->redireccionar('cuenta/recuperar');
                }
            }
        }
        $this->_vista->titulo = 'Recuperar cuenta';
        $this->_vista->renderizar('recuperar');
    }

    public function confirmar($codigo, $id) {
        $id = $this->aInt($id);
        $codigo = $this->aInt($codigo);
        if (!$id || !$codigo) {
            $this->redireccionar('traspie/acceso/404');
        }
        $usuario = $this->_usuario->getUsuario($id);
        if (!$usuario) {
            $this->_vista->error = 'Esta cuenta no existe.';
        } elseif ($usuario['codigo'] != $codigo) {
            $this->_vista->error = 'Esta cuenta no existe, o la página ha expirado.';
        } elseif ($usuario['codigo'] < time()) {
            $this->_vista->error = 'Esta página ha expirado, intente recuperar su cuenta otra vez.';
        } elseif ($this->getInt('guardar') == 1) {
            $contra = $this->_newPass();
            $pass = $this->getHash($contra);
            $this->_usuario->cambiarPass($usuario['id'], $pass);
            $this->getLibreria('mail' . SD . 'class.phpmailer');
            $mail = new PHPMailer();
            $mail->From = MAIL_USER;
            $mail->FromName = DOMINIO;
            $mail->Subject = 'Recuperar cuenta de usuario';
            $mail->Body = 'Hola <strong>' . $usuario['login'] . '</strong>,' .
                    '<p>Ha pedido recupear su cuenta en <strong>' . DOMINIO . '</strong> los datos de su cuenta son:<br>' .
                    'Login: ' . $usuario['login'] . '<br>' .
                    'Contraseña: ' . $contra . '<br>Puede cambiar sus datos iniciando sesión en <a href="' . URL_BASE . 'cuenta/login">' . URL_BASE . '</a>.</p>';
            $mail->AltBody = 'Su servidor de correo no soporta html';
            $mail->AddAddress($usuario['email'], $usuario['nombre']);
            if (!$mail->Send()) {
                $mail->IsSMTP();
                $mail->SMTPAuth = TRUE;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465;
                $mail->Username = MAIL_USER;
                $mail->Password = MAIL_PASS;
                if (!$mail->Send()) {
                    $this->redireccionar('traspie/acceso/405');
                }
            }
            $this->_vista->msj = 'Recuperación finalizada, se ha enviado sus datos a su correo.';
            $this->_vista->titulo = 'Recuperar cuenta';
            $this->_vista->renderizar('confirmar');
            exit(0);
        }
        $this->_vista->titulo = 'Recuperar cuenta';
        $this->_vista->renderizar('confirmar');
    }

    public function salir() {
        Sesion::acceso('Registrado');
        Sesion::matar();
        $this->redireccionar();
    }

    private function _newPass() {
        $dato = 'ABstuCDxyIJMNRSTUVWXYZabcdE4567FGHefghKLijklmOPQnop9qrvwz12380';
        $pass = '';
        while (strlen($pass) < 10) {
            $pos = rand(0, strlen($dato) - 1);
            $pass = $pass . $dato[$pos];
        }
        return $pass;
    }

}
