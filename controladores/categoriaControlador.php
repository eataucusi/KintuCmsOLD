<?php
/**
 * Archivo 'categoriaControlador'
 * 
 * En este archivo de define la clase 'categoriaControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 05:17:50 PM
 */
/**
 * Clase 'categoriaControlador'
 * 
 * Esta clase nos permitira manejar las categorías
 * 
 * @package Controlador
 */
class categoriaControlador extends Controlador {
    /**@var categoriaModelo*/
    private $_categoria;
    public function __construct() {
        parent::__construct();
        $this->_categoria = $this->getModelo('categoria');
    }
    
    public function index() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de categorías';
        $this->_vista->renderizar('index');
    }
    
    public function listar() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->categorias = $this->_categoria->getCategorias();
        $this->_vista->titulo = 'Lista de categorías';
        $this->_vista->renderizar('listar');
    }
    
    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;            
            if (!$this->getTexto('categoria')) {
                $this->_vista->error[] = 'Categoría: este campo no pude ser vacío.';
            }
            if (!$this->getAlphaNum('nombre')) {
                $this->_vista->error[] = 'Nombre: Utiliza sólo letras (a-z), guíon, subguión y números, de 6 a 50 caracteres.';
            } else {
                if ($this->_categoria->existeNombre($this->getAlphaNum('nombre'))) {
                    $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                }
            }
            if (!$this->getTexto('detalle')) {
                $this->_vista->error[] = 'Detalle: este campo no pude ser vacío.';
            }
            $this->getLibreria('Imagen');
            $img = new Imagen();
            $img->setImagen('img');
            if (!$img->subir(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD)) {
                $this->_vista->error[] = $img->getMensaje();
            }
            if (!isset($this->_vista->error)) {
                $img->getImagen($img->getDireccion());
                $img->ajustar(100, 100);
                $img->guardar(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD);
                $this->_categoria->insertarCategoria($this->getAlphaNum('nombre'), $this->getTexto('categoria'), $this->getTexto('detalle'), $img->_nuevoNombre, $this->getAlt('padre'));
                Sesion::set('msj', 'Se ha guardado la categoría.');
                $this->redireccionar('categoria/listar');
            }             
        }
        $this->_vista->titulo = 'Nueva categoría';
        $this->_vista->padre = $this->_categoria->getSelect($this->_categoria->getCategorias());
        $this->_vista->renderizar('nuevo');
    }
    
    public function editar($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('categoria/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_categoria->getCategoria($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('categoria/listar');
        }        
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('categoria')) {
                $this->_vista->error[] = 'Categoría: este campo no pude ser vacío.';
            }
            if (!$this->getAlphaNum('nombre')) {
                $this->_vista->error[] = 'Nombre: Utiliza sólo letras (a-z), guíon, subguión y números, de 6 a 50 caracteres.';
            } else {
                $id1 = $this->_categoria->existeNombre($this->getAlphaNum('nombre'));
                if ($id1 ) {
                    if ($id != $id1) {
                        $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                    }                    
                }
            }
            if (!$this->getTexto('detalle')) {
                $this->_vista->error[] = 'Detalle: este campo no pude ser vacío.';
            }            
            $this->getLibreria('Imagen');
            $img = new Imagen();
            $img->setImagen('new-img');
            $flag = TRUE;
            if (!$img->subir(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD)) {
                $flag = FALSE;
            }
            if (!isset($this->_vista->error)) {
                if ($flag) {
                    $img->getImagen($img->getDireccion());
                    $img->ajustar(100, 100);
                    $img->guardar(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD);
                    unlink(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD . $this->_vista->datos['img']);
                } else {
                    $img->_nuevoNombre = $this->_vista->datos['img'];
                }                
                $this->_categoria->editarCategoria($id, $this->getAlphaNum('nombre'), $this->getTexto('categoria'), $this->getTexto('detalle'), $img->_nuevoNombre, $this->getAlt('padre'));
                Sesion::set('msj', 'Se ha guardado la categoría.');
                $this->redireccionar('categoria/listar');
            }           
        }        
        $this->_vista->padre = $this->_categoria->getSelect($this->_categoria->getCategorias());
        $this->_vista->titulo = 'Editar categoría &lt;' . $this->_vista->datos['categoria'] . '&gt;';             
        $this->_vista->renderizar('editar');  
    }
    
    public function eliminar($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('categoria/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_categoria->getCategoria($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('categoria/listar');
        }        
        if ($this->getInt('guardar') == 1) {
            if ($this->_categoria->getHijos($this->_vista->datos['id'])) {
                $this->_vista->error[] = 'No se puede eliminar esta categoría porque tiene hijos, primero elimine sus hijos.';
            } elseif ($id == 1) {
                $this->_vista->error[] = 'No se puede eliminar esta categoría porque es llave foranea en otras tablas.';
            } else {   
                if ($this->_categoria->contarArticulo($id)) {
                    $this->_categoria->sinCategoria($id);
                }
                $this->_categoria->eliminarCategoria($id);
                unlink(RAIZ . 'publico' . SD . 'img' . SD . 'categorias' . SD . $this->_vista->datos['img']);
                Sesion::set('msj', 'Se ha eliminado una categoría.');
                $this->redireccionar('categoria/listar');
            }  
        }        
        $this->_vista->titulo = '¿Desea eliminar la categoría &lt;' . $this->_vista->datos['categoria'] . '&gt;?';                
        $this->_vista->renderizar('eliminar');
    }     
}