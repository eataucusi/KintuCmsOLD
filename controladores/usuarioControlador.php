<?php
/**
 * Archivo 'usuarioControlador.php'
 * 
 * Esta archivo define la clase 'usuarioControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 28/02/2013 04:28:40 PM
 */
/**
 * Clase 'usarioControlador'
 * 
 * Esta clase extiende de la clase 'Controlador'
 * 
 * @package Controlador
 */
class usuarioControlador extends Controlador {
    /**@var usuarioModelo*/
    private $_usuario;
    public function __construct() {         
        parent::__construct();
        $this->_usuario = $this->getModelo('usuario');
    }

    public function index() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de usuarios';
        $this->_vista->renderizar('index');
    }
    
    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
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
            if (!$this->getInt('rol')) {
                $this->_vista->error[] = 'Rol: seleccione un rol.';
            }
            if (!isset($this->_vista->error)) {
                $this->_usuario->insertarUsuario($this->getInt('rol'), $this->getAlphaNum('login'), $this->getTexto('nombre'), $this->getEmail('email'), $this->getPass('pass'), rand(1000000000, 9999999999));                               
                
                $usuario = $this->_usuario->getUsuarioLogin($this->getAlphaNum('login'));
                
                $this->getLibreria('mail' . SD . 'class.phpmailer');
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = TRUE;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465;
                $mail->Username = MAIL_USER;
                $mail->Password = MAIL_PASS; 
                $mail->FromName = DOMINIO;
                $mail->Subject = 'Activacion de cuenta de usuario';
                $mail->Body = 'Hola <strong>' . $usuario['login'] . '</strong>,' . 
                        '<p>Se ha registrado en <strong>' . DOMINIO . '</strong> para activar su cuenta ' . 
                        'haga clic sobre el siguiente enlace <br>' .
                        '<a href="' . URL_BASE . 'registro/activar/' .
                        $usuario['id']. '/' . $usuario['codigo'] . '">' .
                        URL_BASE . 'registro/activar/' .
                        $usuario['id']. '/' . $usuario['codigo'] . '</a></p>';
                $mail->AltBody = 'Su servidor de correo no soporta html';
                $mail->AddAddress($usuario['email'], $usuario['nombre']);
                $mail->Send();
                
                Sesion::set('msj', 'Se ha guardado el usuario, éste debe revisar su correo para activar su cuenta.');
                $this->redireccionar('usuario/listar');
            }            
        }        
        $rol = $this->getModelo('rol');
        $this->_vista->rol = $rol->getSelect();
        $this->_vista->titulo = 'Nuevo usuario';
        $this->_vista->renderizar('nuevo');
    }
    
    public function listar($pagina = 0) {
        Sesion::acceso('Administrador');
        $npaginas = (intval(($this->_usuario->contarUsuarios() - 1) / REG_PAG)) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0) {
            $pagina = $npaginas;
        }
        if (!$this->_usuario->getUsuarios($pagina)) {
            $this->redireccionar('traspie/access/404');
        }    
        $this->_vista->paginar = $this->_vista->getPaginacion($pagina, $npaginas, 'usuario/listar/');       
        $this->_vista->datos = $this->_usuario->getUsuarios($pagina);
        $this->_vista->titulo = 'Lista de usuarios';
        $this->_vista->renderizar('listar');
    }
    
    public function editar($id = 0) {
        Sesion::acceso('Administrador');
        if (!$this->aInt($id)) {
            $this->redireccionar('usuario/listar');
        }
        $id = $this->aInt($id);
        if (!$this->_usuario->getUsuario($id)) {
            $this->redireccionar('usuario/listar');
        }
        $this->_vista->datos = $this->_usuario->getUsuario($id);
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
                $this->_vista->error[] = 'Login: utiliza sólo letras (a-z) y números, 6 caracteres como mínimo.';
            } else {
                if ($this->_usuario->existeLogin($this->getAlphaNum('login'))) {
                    if ($id != $this->_usuario->existeLogin($this->getAlphaNum('login'))) {
                        $this->_vista->error[] = 'Login: este usuario ya existe.';
                    }
                }
            }            
            if (!$this->getEmail('email')) {
                $this->_vista->error[] = 'Email: utiliza un email válido.';
            } else {
                if ($this->_usuario->existeEmail($this->getEmail('email'))) {
                    if ($id != $this->_usuario->existeEmail($this->getEmail('email'))) {
                        $this->_vista->error[] = 'Email: este email ya existe.';
                    }
                }
            }
            $pass = FALSE;
            if ($this->getTexto('new-pass')) {
                $pass = TRUE;
                if (!$this->getPass('new-pass')) {
                    $this->_vista->error[] = 'Contraseña: utiliza cualquier caracter, 6 como mínimo.';
                }
                if ($this->getPass('new-repass') != $this->getPass('new-pass')) {
                    $this->_vista->error[] = 'Contraseña: las contraseñas no coinciden.';
                }
            }            
            if (!$this->getTexto('acerca')) {
                $this->_vista->error[] = 'Acerca de mí: este campo no puede ser nulo.';
            }
            if (!$this->getInt('rol')) {
                $this->_vista->error[] = 'Rol: seleccione un rol.';
            }
            if (!$this->getInt('estado')) {
                $this->_vista->error[] = 'Estado: seleccione un estado.';
            }
            $this->getLibreria('Imagen');
            $img = new Imagen();
            $img->setImagen('new-foto');
            $is_img = TRUE;
            if (!$img->subir(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD)) {
                $is_img = FALSE;
            }
            if (!isset($this->_vista->error)) {
                if ($is_img) {
                    $img->getImagen($img->getDireccion());
                    $img->ajustar(64, 64);
                    $img->guardar(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD);
                    if ($this->_vista->datos['foto'] != 'usuario.jpg') {
                        unlink(RAIZ . 'publico' . SD . 'img' . SD . 'usuarios' . SD . $this->_vista->datos['foto']);
                    }                    
                } else {
                    $img->_nuevoNombre = $this->_vista->datos['foto'];
                }
                if ($pass) {
                    $pass = $this->getPass('new-pass');
                }
                $this->_usuario->editarUsuario($id, $this->getInt('rol'), $this->getAlphaNum('login'), $this->getTexto('nombre'), $this->getEmail('email'), $pass, $this->getInt('estado'), $img->_nuevoNombre, $this->getTexto('acerca'));
                Sesion::set('msj', 'Se ha guardado su modificación.');
                $this->redireccionar('usuario/listar');
            }            
        }
        $rol = $this->getModelo('rol');
        $this->_vista->rol = $rol->getSelect();
        $this->_vista->titulo = 'Editar usuario &lt;' . $this->_vista->datos['login'] . '&gt;';
        $this->_vista->renderizar('editar');
    }
    
    public function eliminar($id) {
        Sesion::acceso('Administrador');
        if (!$this->aInt($id)) {
            $this->redireccionar('usuario/listar');
        }
        $id = $this->aInt($id);
        if (!$this->_usuario->getUsuario($id)) {
            $this->redireccionar('usuario/listar');
        }
        $this->_vista->datos = $this->_usuario->getUsuario($id);
        if ($this->getInt('guardar') == 1) {
            if ($this->_vista->datos['estado'] == 2) {
                $this->_usuario->deshabilitarUsuario($id);
            } else {
                $this->_usuario->habilitarUsuario($id);
            }
            Sesion::set('msj', 'Se ha guardado su modificación.');
            $this->redireccionar('usuario/listar');
        }
        $accion = ($this->_vista->datos['estado'] == 1)? 'habilitar': 'deshabilitar'; 
        $this->_vista->titulo = '¿Desea ' . $accion . ' el usuario &lt;' . $this->_vista->datos['login'] . '&gt;?';
        $this->_vista->renderizar('eliminar');
    }
}