<?php
/**
 * Archivo 'comentarioControlador.php'
 * 
 * Esta archivo define la clase 'comentarioControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 05/03/2013 04:28:40 PM
 */
/**
 * Clase 'comentarioControlador'
 * 
 * Esta clase extiende de la clase 'Controlador'
 * 
 * @package Controlador
 */
class comentarioControlador extends Controlador {
    /**@var comentarioModelo*/
    private $_comen;
    public function __construct() {         
        parent::__construct();
        $this->_comen = $this->getModelo('comentario');
    }

    public function index() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de comentarios';
        $this->_vista->renderizar('index');
    }
    
    public function comentar($articulo) {
        Sesion::acceso('Registrado');
        if ($this->getInt('guardar') != 1) {
            $this->redireccionar('comentario');
        }
        if (!$this->aInt($articulo)) {
            $this->redireccionar('comentario');
        }
        $articulo = $this->aInt($articulo);
        $articulom = $this->getModelo('articulo');
        $nombre = $articulom->getNombreArticulo($articulo);
        if (!$this->getTexto('coment')) {            
            Sesion::set('msj', 'El comentario no puede ser vacio.');
            $this->redireccionar('articulo/ver/' . $nombre['nombre'] . '#add-comentario');
        }
        $this->_comen->addComentario($articulo, Sesion::get('id'), $this->getTexto('coment'));
        $this->redireccionar('articulo/ver/' . $nombre['nombre'] , '#comentarios');
    }
    
    public function eliminar($id) {
        Sesion::acceso('Registrado');
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('comentario');
        }                
        $comen = $this->_comen->getComentario($id);
        if (!$comen) {
            $this->redireccionar('comentario');
        }
        $articulom = $this->getModelo('articulo');
        $nombre = $articulom->getNombreArticulo($comen['arti_id']);
        if (Sesion::get('nivel') == 4 || Sesion::get('id') == $comen['usua_id'] || Sesion::get('id') == $nombre['usua_id']) {
            if ($this->getInt('guardar') == 1) {
                $this->_comen->eliminarComentario($id);
                $this->redireccionar('articulo/ver/' . $nombre['nombre'], '#comentarios');
            }
            $this->_vista->titulo = '¿Desea eliminar este comentario?';
            $this->_vista->datos = $comen;
            $this->_vista->renderizar('eliminar');
        } else {
            $this->redireccionar('traspie/acceso/401');
        }        
    }

}