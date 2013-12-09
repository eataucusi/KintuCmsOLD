<?php
/**
 * Archivo 'archivoControlador.php'
 * 
 * En este archivo de define la clase 'archivoControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 10/03/2013 04:37:50 AM
 */
/**
 * Clase 'archivoControlador'
 * 
 * Esta clase nos permitira gestionar los archivos
 * 
 * @package Controlador
 */
class archivoControlador extends Controlador {
    /**@var archivoModelo*/
    private $_archivo;
    public function __construct() {
        parent::__construct();
        $this->_archivo = $this->getModelo('archivo');
    }
    
    public function index() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de archivos';
        $this->_vista->renderizar('index');
    }  
    
    public function nuevo() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('descripcion')) {
                $this->_vista->error[] = 'Descripción: este campo no puede ser vacío.';
            }
            $this->getLibreria('Subir');
            $subir = new Subir();
            if (!$subir->setFile('archivo')) {
                $this->_vista->error[] = 'Archivo: ' . $subir->getMsj();
            } else {
                if (!$subir->guardar(RAIZ . 'publico' . SD . 'archivos' . SD)) {
                    $this->_vista->error[] = 'Archivo: ' . $subir->getMsj();
                }
            }
            if (!isset($this->_vista->error)) {
                $this->_archivo->insertarArchivo(Sesion::get('id'), $subir->getNombre(), $this->getTexto('descripcion'), 'descargas');
                Sesion::set('msj', 'Se ha guardado el archivo.');
                $this->redireccionar('archivo/listar');
            }
        }
        $this->_vista->titulo = 'Subir archivo';
        $this->_vista->renderizar('nuevo');
    }
    
    public function listar($pagina = 1) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $npag = intval(($this->_archivo->contarArchivos('descargas') - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina < 1 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_archivo->getArchivos('descargas', $pagina);
        $this->_vista->paginar = $this->_vista->getPaginacion($pagina, $npag, 'archivo/listar/');
        $this->_vista->titulo = 'Lista de archivos';
        $this->_vista->renderizar('listar');
    }
    
    public function editar($id) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('archivo/listar');
        }
        $this->_vista->datos = $this->_archivo->getArchivo($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('archivo/listar');
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('descripcion')) {
                $this->_vista->error[] = 'Descripción: este campo no puede ser vacío';
            }
            if (!isset($this->_vista->error)) {
                $this->_archivo->editarArchivo($id, $this->getTexto('descripcion'));
                Sesion::set('msj', 'Se ha guardado su modificación.');
                $this->redireccionar('archivo/listar');
            }
        }
        $this->_vista->titulo = 'Editar archivo &lt;' . $this->_vista->datos['nombre'] . '&gt;';
        $this->_vista->renderizar('editar');
    }
    
    public function eliminar($id) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('archivo/listar');
        }
        $this->_vista->datos = $this->_archivo->getArchivo($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('archivo/listar');
        }
        if ($this->getInt('guardar') == 1) {
            unlink(RAIZ . 'publico' . SD . 'archivos' . SD . $this->_vista->datos['nombre']);
            $this->_archivo->eliminarArchivo($id);
            Sesion::set('msj', 'Se ha eliminado el archivo.');
            $this->redireccionar('archivo/listar');
        }
        $this->_vista->titulo = 'Eliminar archivo &lt;' . $this->_vista->datos['nombre'] . '&gt;';
        $this->_vista->renderizar('eliminar');
    }
    
    public function ver($pagina = 1) {
        $npag = intval(($this->_archivo->contarArchivos('descargas') - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina < 1 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_archivo->getArchivos('descargas', $pagina);
        $this->_vista->paginar = $this->_vista->getPaginacion($pagina, $npag, 'archivo/ver/');
        $this->_vista->titulo = 'Lista de archivos';
        $this->_vista->renderizar('ver');
    }
    
    public function descargar($nombre = FALSE) {
        if (!$nombre) {
            $this->redireccionar('archivo/ver');
        }
        $id = $this->_archivo->existeNombre($nombre);
        if (!$id) {
            $this->redireccionar('archivo/error/' . $nombre);
        }
        $url = RAIZ . 'publico' . SD . 'archivos' . SD . $nombre;
        if (!is_file($url)) {
            $this->redireccionar('archivo/error/' . $nombre);
        }
        $tamanio = filesize($url);
        $tipo = '';
        if (function_exists('mime_content_type')) {
            $tipo = mime_content_type($url);
        } elseif (function_exists('finfo_file')) {
            $info = finfo_open(FILEINFO_MIME);
            $tipo = finfo_file($info, $url);
            finfo_close($info);
        }
        if ($tipo == '') {
            $tipo = 'application/force-download';
        }
        $this->_archivo->descaragarArchivo($id);
        
        header('Content-Type:' . $tipo);
        header('Content-Disposition: attachment; filename=' . $nombre);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $tamanio);        
        readfile($url);
    }
    
    public function error($nombre = FALSE) {
        if (!$nombre) {
            $this->redireccionar('archivo/ver');
        }
        $this->_vista->titulo = 'Error archivo no encontrado';
        $this->_vista->nombre = $nombre;
        $this->_vista->renderizar('error');
    }
    
    public function img_listar($pagina = 1) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $npag = intval(($this->_archivo->contarImg() - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina < 1 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_archivo->getImgs($pagina);
        $this->_vista->getPaginacion($pagina, $npag, 'archivo/img_listar/');
        $this->_vista->renderizar('imagen' . SD . 'listar', TRUE);
    }
    
    public function img_ver($nombre) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $this->_vista->datos = $nombre;
        $this->_vista->renderizar('imagen' . SD . 'ver', TRUE);
    }
    
    public function img_nuevo() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('descripcion')) {
                $this->_vista->error[] = 'Descripción: este campo no puede ser vacío.';
            }
            $this->getLibreria('Imagen');
            $img = new Imagen();
            $img->setImagen('archivo');
            if (!$img->subir(RAIZ . 'publico' . SD . 'img' . SD . 'articulos' . SD)) {
                $this->_vista->error[] = 'Archivo: ' . $img->getMensaje();
            }
            if (!isset($this->_vista->error)) {
                $this->_archivo->insertarArchivo(Sesion::get('id'), $img->_nuevoNombre, $this->getTexto('descripcion'), 'articulos');
                Sesion::set('msj', 'Se ha guardado el archivo.');
                $this->redireccionar('archivo/img_listar');
            }
        }
        $this->_vista->renderizar('imagen' . SD . 'nuevo', TRUE);
    }
    
    public function img_eliminar($id) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('archivo/img_listar');
        }
        $this->_vista->datos = $this->_archivo->getArchivo($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('archivo/img_listar');
        }
        if ($this->getInt('guardar') == 1) {
            unlink(RAIZ . 'publico' . SD . 'img' . SD . 'articulos' . SD . $this->_vista->datos['nombre']);
            $this->_archivo->eliminarArchivo($id);
            Sesion::set('msj', 'Se ha eliminado la imagen.');
            $this->redireccionar('archivo/img_listar');
        }
        $this->_vista->titulo = 'Eliminar imagen &lt;' . $this->_vista->datos['nombre'] . '&gt;';
        $this->_vista->renderizar('imagen' . SD . 'eliminar', TRUE);
    }
    
     public function precode() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $this->_vista->renderizar('imagen' . SD . 'precode', TRUE);
    }
}