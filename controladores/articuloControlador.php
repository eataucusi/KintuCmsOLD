<?php
/**
 * Archivo 'articuloControlador.php'
 * 
 * Esta archivo define la clase 'articuloControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 02/03/2013 04:28:40 PM
 */
/**
 * Clase 'articuloControlador'
 * 
 * Esta clase extiende de la clase 'Controlador'
 * 
 * @package Controlador
 */
class articuloControlador extends Controlador {
    /**@var articuloModelo*/
    private $_articulo;
    public function __construct() {         
        parent::__construct();
        $this->_articulo = $this->getModelo('articulo');        
        $categoria = $this->getModelo('categoria');
        $this->_vista->categorias = $categoria->getCategorias();
    }

    public function index() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar('articulo/categorias');
        }
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de articulos';
        $this->_vista->renderizar('index');
    }
    
    public function nuevo() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar('articulo');
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('titulo')) {
                $this->_vista->error[] = 'Título: este campo no puede ser vacío.';
            }
            if (!$this->getAlphaNum('nombre')) {
                $this->_vista->error[] = 'Nombre: utiliza sólo letras (a-z), guíon, subguión y números, de 6 a 150 caracteres.';
            } else {
                if ($this->_articulo->existeNombre($this->getAlphaNum('nombre'))) {
                    $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                }
            }
            if (!$this->getHtml('resumen')) {
                $this->_vista->error[] = 'Resumen: este campo no puede ser vacío.';
            }
            if (!$this->getHtml('cuerpo')) {
                $this->_vista->error[] = 'Cuerpo: este campo no puede ser vacío.';
            }            
            if (!$this->getTexto('meta')) {
                $this->_vista->error[] = 'Metadatos: este campo no puede ser vacío.';
            }
            if (!$this->getInt('categoria')) {
                $this->_vista->error[] = 'Categoria: seleccione una categoría.';
            }
            if (!$this->getInt('estado')) {
                $this->_vista->error[] = 'Estado: seleccione un estado.';
            }
            if (!isset($this->_vista->error)) {
                $this->_articulo->insertarArticulo(Sesion::get('id'), $this->getInt('categoria'), $this->getAlphaNum('nombre'), $this->getTexto('titulo'), $this->getHtml('resumen'), $this->getHtml('cuerpo'), $this->getTexto('meta'));                             
                Sesion::set('msj', 'Se ha guardado el articulo.');
                $this->redireccionar('articulo/listar/' . $this->_vista->datos['categoria']);
            }            
        }
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/jquery.tinymce');
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/tiny_mce');
        $this->_vista->setJs('iniciarTinymce');
        $categoria = $this->getModelo('categoria');
        $this->_vista->categoria = $categoria->getSelect($this->_vista->categorias);
        $this->_vista->titulo = 'Nuevo articulo';
        $this->_vista->renderizar('nuevo');
    }
    
    public function listar($id_cate = FALSE, $pagina = 1) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar('articulo');
        }
        $catem = $this->getModelo('categoria');
        $id_cate = $this->aInt($id_cate);
        if (!$id_cate) {
            $this->redireccionar('articulo/categorias');
        }        
        $_cate = $catem->getCategoria($id_cate);
        if (!$_cate) {
            $this->redireccionar('articulo/categorias');
        }
        $npag = intval(($this->_articulo->contarArticulos($id_cate) - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_articulo->getArticulos($id_cate, $pagina);
        $this->_vista->getPaginacion($pagina, $npag, 'articulo/listar/' . $id_cate . '/');    
        $this->_vista->titulo = 'Lista de articulos de la categoría &lt;' . $_cate['nombre'] . '&gt;';
        $this->_vista->renderizar('listar_categoria');
    }
    
    public function categorias($pagina = 1, $parcial = 1) {
        $parcial = $this->aInt($parcial);
        $parcial = $this->aInt($parcial);
        $parcial = $parcial == 777? TRUE: FALSE;
        $cate = new categoriaModelo();
        $this->_vista->cate = $cate->getCategorias();
        $this->_vista->titulo = 'Lista de articulos por categoría';
        $this->_vista->meta = 'Lista de articulos por categoría';
        $this->_vista->renderizar('categorias', $parcial);
    }
    

    public function editar($id) {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar('articulo');
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('articulo/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_articulo->getArticulo($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('articulo/listar');
        }
        if (Sesion::get('nivel') != 4 && Sesion::get('id') != $this->_vista->datos['usua_id']) {
            $this->redireccionar('traspie/acceso/401');
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getAlphaNum('nombre')) {
                $this->_vista->error[] = 'Nombre: utiliza sólo letras (a-z), guíon, subguión y números, de 6 a 150 caracteres.';
            } else {
                if ($this->_articulo->existeNombre($this->getAlphaNum('nombre'))) {
                    if ($id != $this->_articulo->existeNombre($this->getAlphaNum('nombre'))) {
                        $this->_vista->error[] = 'Nombre: este nombre ya existe.';
                    }
                }
            }
            if (!$this->getTexto('titulo')) {
                $this->_vista->error[] = 'Título: este campo no puede ser vacío.';
            }             
            if (!$this->getHtml('cuerpo')) {
                $this->_vista->error[] = 'Cuerpo: este campo no puede ser vacío.';
            }            
            if (!$this->getTexto('meta')) {
                $this->_vista->error[] = 'Metadatos: este campo no puede ser vacío.';
            }
            if (!$this->getInt('usua_id')) {
                $this->_vista->error[] = 'Usuario: seleccione una usuario.';
            }
            if (!$this->getInt('categoria')) {
                $this->_vista->error[] = 'Categoria: seleccione una categoría.';
            }
            if (!$this->getInt('estado')) {
                $this->_vista->error[] = 'Estado: seleccione un estado.';
            }
            if (!isset($this->_vista->error)) {
                $this->_articulo->editarArticulo($id, $this->getInt('usua_id'), $this->getInt('categoria'), $this->getAlphaNum('nombre'), $this->getTexto('titulo'), $this->getHtml('resumen'), $this->getHtml('cuerpo'), $this->getTexto('meta'), $this->getInt('estado'));                             
                                         
                Sesion::set('msj', 'Se ha guardado el articulo.');
                $this->redireccionar('articulo/listar/' . $this->_vista->datos['categoria']);
            } 
        }
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/jquery.tinymce');
        $this->_vista->setJs('tinymce/jscripts/tiny_mce/tiny_mce');
        $this->_vista->setJs('iniciarTinymce');
        $categoria = $this->getModelo('categoria');
        $this->_vista->categoria = $categoria->getSelect($this->_vista->categorias);
        $usuario = $this->getModelo('usuario');
        $this->_vista->usuario = $usuario->getSelect();
        $this->_vista->titulo = 'Editar articulo &lt;' . $this->_vista->datos['nombre'] . '&gt;';
        $this->_vista->renderizar('editar');
    }
    
    
    public function eliminar($id) {
        Sesion::acceso('Publicador');
        if (!$this->aInt($id)) {
            $this->redireccionar('articulo/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_articulo->getArticulo($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('articulo/listar');
        }
        if (Sesion::get('nivel') != 4 && Sesion::get('id') != $this->_vista->datos['usua_id']) {
            $this->redireccionar('traspie/acceso/401');
        }
        if ($this->getInt('guardar') == 1) {
            if ($this->_vista->datos['estado'] == 2) {
                $this->_articulo->deshabilitarArticulo($id);
            } else {
                $this->_articulo->habilitarArticulo($id);
            }
            Sesion::set('msj', 'Se ha guardado su modificación.');
            $this->redireccionar('articulo/listar/'. $this->_vista->datos['cate_id']);
        }
        $accion = ($this->_vista->datos['estado'] == 1)? 'habilitar': 'deshabilitar'; 
        $this->_vista->titulo = '¿Desea ' . $accion . ' el articulo &lt;' . $this->_vista->datos['nombre'] . '&gt;?';
        $this->_vista->renderizar('eliminar');
    }
    
    public function ver($nombre, $pagina = 1, $parcial = 1) {        
        $id = $this->_articulo->getArticuloNombre($nombre);
        if (!$id) {
            $this->redireccionar('articulo/listar');
        }        
        $coment = $this->getModelo('comentario');  
        $this->_vista->ncoment = $coment->contarComentarios($id);  
        if (!$this->_vista->ncoment) {
            $this->_vista->comentarios = 0;
        } else {
            $npaginas = (intval(($this->_vista->ncoment - 1) / REG_PAG)) + 1;
            $pagina = $this->aInt($pagina);
            if ($pagina <= 0 || $pagina > $npaginas) {
                $pagina = 1;
            }
            $this->_vista->comentarios = $coment->getComentarios($id, $pagina);
            for ($i = 0; $i < count($this->_vista->comentarios); $i++) {
                $this->_vista->comentarios[$i]['fecha'] = $this->_fechaTexto($this->_vista->comentarios[$i]['creado']);
            }
            $this->_vista->getPaginacion($pagina, $npaginas, 'articulo/ver/' . $nombre .'/', '#comentarios'); 
        }
        $art = $this->_articulo->verArticulo($id);
        if ($art['estado'] == 1 && !Sesion::accesoVista('Publicador')) {
            $this->redireccionar('articulo/listar');
        }
        $parcial = $this->aInt($parcial);
        $parcial = $parcial == 777? TRUE: FALSE;
        $this->_vista->setJs('iniciarPretty');      
        $this->_vista->articulo_id = $id;     
        $this->_vista->datos = $art;
        $this->_vista->datos['fecha'] = $this->_fechaTexto($this->_vista->datos['creado']);            
        $this->_vista->titulo = $this->_vista->datos['titulo'];  
        $this->_vista->meta = $this->_vista->datos['meta']; 
        $this->_vista->renderizar('ver', $parcial);
        
    }     

    public function categoria($nombre = FALSE, $pagina = 1, $parcial = 1) {
        if (!$nombre) {
            $this->redireccionar('articulo/categorias');
        }
        $cate = new categoriaModelo();
        $id = $cate->existeNombre($nombre);
        if (!$id) {
            $this->redireccionar('articulo/categorias');
        }  
        $npaginas = (intval(($this->_articulo->contarArticulos($id) - 1) / REG_PAG)) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0 || $pagina > $npaginas) {
                $pagina = 1;
        }
        $this->_vista->datos = $this->_articulo->getCategoria($id,$pagina);
        $tam = count($this->_vista->datos);
        $coment = $this->getModelo('comentario');
        if ($tam > 0 && isset($this->_vista->datos[0]['id'])) {
            for ($i = 0; $i < $tam; $i++) {
                $this->_vista->datos[$i]['ncom'] = $coment->contarComentarios($this->_vista->datos[$i]['id']);
                $this->_vista->datos[$i]['fecha'] = $this->_fechaTexto($this->_vista->datos[$i]['creado']);
            }            
        } else {
            $this->_vista->datos = array();
        } 
        $parcial = $this->aInt($parcial);
        $parcial = $parcial == 777? TRUE: FALSE;
        $this->_vista->titulo = 'Artículos de la categoría ' . $nombre;
        $this->_vista->meta = $this->_vista->datos[0]['detalle'];
        $this->_vista->getPaginacion($pagina, $npaginas, 'articulo/categoria/' . $nombre . '/');
        $this->_vista->renderizar('categoria', $parcial);
    }
    
    public function reciente($pagina = 1, $parcial = 1) {
        $npaginas = (intval(($this->_articulo->contarArticulos() - 1) / REG_PAG)) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0 || $pagina > $npaginas) {
                $pagina = 1;
        }
        $this->_vista->datos = $this->_articulo->getUltimos($pagina);
        $tam = count($this->_vista->datos);
        $coment = $this->getModelo('comentario');
        for ($i = 0; $i < $tam; $i++) {
            $this->_vista->datos[$i]['ncom'] = $coment->contarComentarios($this->_vista->datos[$i]['id']);
            $this->_vista->datos[$i]['fecha'] = $this->_fechaTexto($this->_vista->datos[$i]['creado']);
        }
        $parcial = $this->aInt($parcial);
        $parcial = $parcial == 777? TRUE: FALSE;
        $this->_vista->titulo = 'Últimos articulos';
        $this->_vista->meta = 'Últimos articulos';
        $this->_vista->getPaginacion($pagina, $npaginas, 'articulo/reciente/');
        $this->_vista->renderizar('reciente', $parcial);
    }
}