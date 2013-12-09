<?php

/**
 * Archivo 'menuControlador'
 * 
 * En este archivo de define la clase 'menuControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 08/02/2013 12:37:50 PM
 */

/**
 * Clase 'menuControlador'
 * 
 * Esta clase nos permitira manejar los menus de la aplicacion
 * 
 * @package Controlador
 */
class menuControlador extends Controlador {

    /**
     *
     * @var menuModelo
     */
    private $_menu;

    public function __construct() {
        parent::__construct();
        $this->_menu = $this->getModelo('menu');
    }

    public function index() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->submenu = $this->_menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de menús';
        $this->_vista->renderizar('index');
    }

    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('texto')) {
                $this->_vista->error[] = 'Texto: este campo no pude ser vacío.';
            }
            if (!$this->getTexto('url')) {
                $this->_vista->error[] = 'Url: este campo no pude ser vacío.';
            }
            if (!$this->getTexto('detalle')) {
                $this->_vista->error[] = 'Detalle: este campo no pude ser vacío.';
            }
            if (!$this->getInt('role_id')) {
                $this->_vista->error[] = 'Para: este campo no pude ser vacío.';
            }
            if (!isset($this->_vista->error)) {
                $this->_menu->insertarMenu($this->getInt('role_id'), $this->getTexto('texto'), $this->getTexto('url'), $this->getTexto('detalle'), $this->getAlt('class'), $this->getAlt('padre'), $this->getAlt('ventana'));
                Sesion::set('msj', 'Se ha guardado el menú.');
                $this->redireccionar('menu/listar/' . $this->getInt('role_id'));
            }
        }
        $this->_vista->setJs('ajax/menu');
        $rol = $this->getModelo('rol');
        $this->_vista->rol = $rol->getSelect();
        $this->_vista->titulo = 'Nuevo menú';
        $this->_vista->renderizar('nuevo');
    }

    public function editar($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('menu/listar');
        }
        $this->_vista->datos = $this->_menu->getMenu($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('menu/listar');
        }
        $ant = $this->_vista->datos['role_id'];
        if ($this->getInt('guardar') == 1) {
            if (!$this->getTexto('texto')) {
                $this->_vista->error[] = 'Texto: este campo no pude ser vacío.';
            }
            if (!$this->getTexto('url')) {
                $this->_vista->error[] = 'Url: este campo no pude ser vacío.';
            }
            if (!$this->getTexto('detalle')) {
                $this->_vista->error[] = 'Detalle: este campo no pude ser vacío.';
            }
            if (!$this->getInt('role_id')) {
                $this->_vista->error[] = 'Para: este campo no pude ser vacío.';
            }
            if (!isset($this->_vista->error)) {
                $this->_menu->editarMenu($id, $this->getInt('role_id'), $this->getTexto('texto'), $this->getTexto('url'), $this->getTexto('detalle'), $this->getAlt('class'), $this->getAlt('padre'), $this->getAlt('ventana'));
                Sesion::set('msj', 'Su modificación ha sido guardado.');
                $this->redireccionar('menu/listar/' . $ant);
            }
            $this->_vista->datos = $_POST;
        }
        $this->_vista->padre = $this->_getSelect($this->_menu->getMenus($this->_vista->datos['role_id']));

        $this->_vista->setJs('ajax/menu');
        $rol = $this->getModelo('rol');
        $this->_vista->rol = $rol->getSelect();
        $this->_vista->titulo = 'Editar menú &lt;' . $this->_vista->datos['texto'] . '&gt;';
        $this->_vista->renderizar('editar');
    }

    public function listar($rol = FALSE) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $rol = $this->aInt($rol);
        if (!$rol) {
            $this->_vista->submenu = $this->_menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
            $this->_vista->titulo = 'Listar menús';
            $this->_vista->renderizar('index1');
            exit(0);
        } else {
            $_rol = $this->getModelo('rol');
            $role = $_rol->getRol($rol);
            if (!$role) {
                $this->_vista->titulo = 'Listar menús';
                $this->_vista->renderizar('index1');
                exit(0);
            } else {
                $this->_vista->menus = $this->_menu->getMenus($rol);
                $role = $_rol->getRol($rol);
            }
        }
        $this->_vista->titulo = 'Lista de menús para el rol &lt;' . $role . '&gt;';
        $this->_vista->renderizar('listar');
    }

    public function eliminar($id = 0) {
        Sesion::acceso('Administrador');
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('menu/listar');
        }
        $this->_vista->datos = $this->_menu->getMenu($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('menu/listar');
        }
        if ($this->getInt('guardar') == 1) {
            if ($this->_menu->getHijos($this->_vista->datos['id'])) {
                $this->_vista->error[] = 'No se puede eliminar este menú porque tiene hijos, primero elimine sus hijos.';
            } else {
                $this->_menu->eliminarMenu($id);
                Sesion::set('msj', 'Se ha eliminado un menú.');
                $this->redireccionar('menu/listar/' . $this->_vista->datos['role_id']);
            }
        }
        $this->_vista->titulo = '¿Desea eliminar el menú &lt;' . $this->_vista->datos['texto'] . '&gt;?';
        $this->_vista->renderizar('eliminar');
    }

    public function ajaxSelect($rol = FALSE) {
        Sesion::acceso('Administrador');
        $rol = $this->aInt($rol);
        if ($rol) {
            $this->_vista->datos = $this->_getSelect($this->_menu->getMenus($rol));
            $this->_vista->renderizar('ajax' . SD . 'seleccionar', TRUE);
        }
    }

    public function enlaces() {
        if (Sesion::accesoVista('Administrador')) {
            $this->_vista->renderizar('ajax' . SD . 'opciones', TRUE);
        }
    }

    public function categorias() {
        if (Sesion::accesoVista('Administrador')) {
            $this->_vista->datos = $this->_menu->getCategorias();
            $this->_vista->renderizar('ajax' . SD . 'categorias', TRUE);
        }
    }

    public function articulos($pagina = 1) {
        if (Sesion::accesoVista('Administrador')) {
            $this->_vista->datos = $this->_menu->getCategorias();

            $npag = intval(($this->_menu->nArticulos() - 1) / REG_PAG) + 1;
            $pagina = $this->aInt($pagina);
            if ($pagina <= 0 || $pagina > $npag) {
                $pagina = 1;
            }
            $this->_vista->datos = $this->_menu->getArticulos($pagina);
            $this->_vista->getPaginacion($pagina, $npag, 'menu/articulos/');
            $this->_vista->renderizar('ajax' . SD . 'articulos', TRUE);
        }
    }

    /**
     * Metodo que retorna los datos del del select es hijo de
     * @param array $_menu
     * @return string
     */
    private function _getSelect(array $_menu) {
        $menu = array();
        for ($i = 0; $i < count($_menu); $i++) {
            $menu[] = array('id' => $_menu[$i]['menu']['id'], 'texto' => '-- ' . $_menu[$i]['menu']['texto']);
            if ($_menu[$i]['submenu']) {
                $_smenu = $_menu[$i]['submenu'];

                for ($j = 0; $j < count($_smenu); $j++) {
                    $menu[] = array('id' => $_smenu[$j]['menu']['id'], 'texto' => '---- ' . $_smenu[$j]['menu']['texto']);

                    if ($_smenu[$j]['submenu']) {
                        $_ssmenu = $_smenu[$j]['submenu'];

                        for ($k = 0; $k < count($_ssmenu); $k++) {
                            $menu[] = array('id' => $_ssmenu[$k]['id'], 'texto' => '------ ' . $_ssmenu[$k]['texto']);
                        }
                    }
                }
            }
        }
        return $menu;
    }

}
