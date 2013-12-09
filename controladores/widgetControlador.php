<?php

/**
 * Archivo 'widgetControlador'
 * 
 * En este archivo de define la clase 'widgetControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:37:50 PM
 */

/**
 * Clase 'widgetControlador'
 * 
 * Esta clase nos permitira manejar los widgets de la aplicación
 * 
 * @package Controlador
 */
class widgetControlador extends Controlador {
    /** @var widgetModelo */

    private $_widget;

    public function __construct() {
        parent::__construct();
        $this->_widget = $this->getModelo('widget');
    }

    public function index() {
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de widgets';
        $this->_vista->renderizar('index');
    }

    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;

            if (!$this->getAlphaNum('nombre', 4)) {
                $this->_vista->error[] = 'Nombre: utiliza sólo letras (a-z), guíon, subguión y números, de a 50 cacteres.';
            } else {
                if ($this->_widget->existeNombre($this->getAlphaNum('nombre', 4))) {
                    $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                }
            }
            if ($this->getInt('peso') == 0) {
                $this->_vista->error[] = 'Peso: Ponga un peso.';
            }
            if (!isset($this->_vista->error)) {
                $this->_widget->insertarWidget($this->getAlphaNum('nombre', 4), $this->getAlt('titulo'), $this->getHtml('cuerpo', TRUE), $this->getInt('visible'), $this->getInt('peso'));
                Sesion::set('msj', 'Se ha guardado el widget.');
                $this->redireccionar('widget/listar');
            }
        }
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/jquery.tinymce');
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/tiny_mce');
        $this->_vista->setJs('iniciarTinymce');
        $this->_vista->titulo = 'Nuevo Widget';
        $this->_vista->renderizar('nuevo');
    }

    public function editar($id) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('widget/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_widget->getWidget($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('widget/listar');
        }
        if ($this->getInt('guardar') == 1) {

            if (!$this->getAlphaNum('nombre', 4)) {
                $this->_vista->error[] = 'Nombre: utiliza sólo letras (a-z), guíon, subguión y números, de a 50 cacteres.';
            } else {
                if ($this->_widget->existeNombre($this->getAlphaNum('nombre', 4)) && $id != $this->_vista->datos['id']) {
                    $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                }
            }
            if ($this->getInt('peso') == 0) {
                $this->_vista->error[] = 'Peso: Ponga un peso.';
            }
            if (!isset($this->_vista->error)) {
                $this->_widget->editarWidget($this->getAlphaNum('nombre', 4), $this->getAlt('titulo'), $this->getHtml('cuerpo', TRUE), $this->getInt('visible'), $this->getInt('peso'), $id);
                Sesion::set('msj', 'Se ha guardado el widget.');
                $this->redireccionar('widget/listar');
            }
            $this->_vista->datos = $_POST;
        }
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/jquery.tinymce');
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/tiny_mce');
        $this->_vista->setJs('iniciarTinymce');
        $this->_vista->titulo = 'Editar Widget';
        $this->_vista->renderizar('nuevo');
    }

    public function eliminar($id) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('widget/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_widget->getWidget($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('widget/listar');
        }
        if ($this->getInt('guardar') == 1) {
            if ($this->_vista->datos['visible'] == 2) {
                $this->_widget->deshabilitar($id);
            } else {
                $this->_widget->habilitar($id);
            }
            Sesion::set('msj', 'Se ha guardado su modificación.');
            $this->redireccionar('widget/listar');
        }
        $accion = ($this->_vista->datos['visible'] == 1) ? 'habilitar' : 'deshabilitar';
        $this->_vista->titulo = '¿Desea ' . $accion . ' el articulo &lt;' . $this->_vista->datos['nombre'] . '&gt;?';
        $this->_vista->renderizar('eliminar');
    }

    public function listar($pagina = 1) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        $npag = intval(($this->_widget->contarWidget() - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_widget->getWidgets($pagina);
        $this->_vista->getPaginacion($pagina, $npag, 'widget/listar/');
        $this->_vista->titulo = 'Lista de Widgets';
        $this->_vista->renderizar('listar');
    }
}
