<?php

/**
 * Archivo 'plantillaControlador'
 * 
 * En este archivo de define la clase 'plantillaControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:37:50 PM
 */

/**
 * Clase 'plantillaControlador'
 * 
 * Esta clase nos permitira manejar los plantillas de la aplicación
 * 
 * @package Controlador
 */
class plantillaControlador extends Controlador {

    /** @var plantillaModelo */
    private $_plantilla;

    public function __construct() {
        parent::__construct();
        $this->_plantilla = $this->getModelo('plantilla');
    }

    public function index() {
        $menu = $this->getModelo('menu');
        $this->_vista->submenu = $menu->getSubmenu($this->_vista->peticion->getControlador(), $this->_vista->peticion->getMetodo());
        $this->_vista->titulo = 'Gestor de plantillas';
        $this->_vista->renderizar('index');
    }

    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            $this->getLibreria('Subir');
            $subir = new Subir();
            if (!$subir->setFile('archivo')) {
                $this->_vista->error[] = 'Archivo: ' . $subir->getMsj();
            } else {
                $rplantilla = RAIZ . 'vistas' . SD . '_plantillas' . SD;
                if (!$subir->guardar($rplantilla)) {
                    $this->_vista->error[] = 'Archivo: ' . $subir->getMsj();
                } elseif (file_exists($rplantilla . $subir->getAntNombre())) {
                    $this->_vista->error[] = 'Plantilla: Esta plantilla ya existe';
                    unlink($rplantilla . $subir->getNombre());
                }
            }
            if (!isset($this->_vista->error)) {
                $zip = new ZipArchive();
                $zip->open($rplantilla . $subir->getNombre());
                $zip->extractTo($rplantilla);
                $zip->close();
                unlink($rplantilla . $subir->getNombre());
                $plantilla = simplexml_load_file($rplantilla . $subir->getAntNombre() . SD . 'detalles.xml');
                $this->_plantilla->insertarPlantilla($plantilla->nombre, $plantilla->para, $plantilla->creado, $plantilla->autor, $plantilla->emailAutor, $plantilla->urlAutor, $plantilla->detalle, $plantilla->vistaPrevia);
                $posiciones = $plantilla->posiciones->children();
                $id = $this->_plantilla->existeNombre($plantilla->nombre);
                foreach ($posiciones as $posicion) {
                    $this->_plantilla->insertarPosicion($id, $posicion);
                }
                Sesion::set('msj', 'Se ha instalado su plantilla.');
                $this->redireccionar('plantilla/listar');
            }
        }
        $this->_vista->titulo = 'Instalar Plantilla';
        $this->_vista->renderizar('nuevo');
    }

    public function defecto($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('plantilla/listar');
        }
        $id = $this->aInt($id);
        $datos = $this->_plantilla->getPlantilla($id);
        if (!$datos) {
            $this->redireccionar('plantilla/listar');
        }
        $this->_plantilla->deshabilitar($datos['para']);
        $this->_plantilla->habilitar($id);
        $this->redireccionar('plantilla/listar');
    }

    public function asignar($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('plantilla/listar');
        }
        $id = $this->aInt($id);
        $datos = $this->_plantilla->getPlantilla($id);
        if (!$datos) {
            $this->redireccionar('plantilla/listar');
        }  
        $asi = $this->_plantilla->getWidgetAs($id);
        $noa = $this->_plantilla->getWidgetsNoAs($id);
        if (is_array($asi) && is_array($noa)) {
            $todo = array_merge($asi, $noa);
        } elseif (!is_array($asi)) {
            $todo = $noa;
        } else {
            $todo = $asi;
        }
        if ($this->getInt('guardar') == 1) {
            for ($i = 0; $i < count($todo); $i++) {
                if (!is_null($this->getAlt($todo[$i]['nombre']))) {
                    $this->_plantilla->asignar($todo[$i]['pos'] , $todo[$i]['id'], $this->getAlt($todo[$i]['nombre']));
                }                
            }
            Sesion::set('msj', 'Se ha guardado la asignación.');
            $this->redireccionar('plantilla/asignar/' . $id);
        }
        $this->_vista->widgets = $todo;
        $this->_vista->posiciones = $this->_plantilla->getPosiciones($id);
        $this->_vista->titulo = 'Asignar widgets a la plantilla: ' . $datos['nombre'];
        $this->_vista->renderizar('asignar');
    }

    public function eliminar($id = 0) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if (!$this->aInt($id)) {
            $this->redireccionar('plantilla/listar');
        }
        $id = $this->aInt($id);
        $this->_vista->datos = $this->_plantilla->getPlantilla($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('plantilla/listar');
        }
        if ($this->getInt('guardar') == 1) {
            if ($this->_vista->datos['defecto'] == 1) {
                $this->_plantilla->eliminarPlantilla($id);
                $this->rmdir(RAIZ . 'vistas' . SD . '_plantillas' . SD . $this->_vista->datos['nombre']);
                Sesion::set('msj', 'Se ha eliminado una plantilla.');
                $this->redireccionar('plantilla/listar');
            }
        }
        $this->_vista->titulo = '¿Desea eliminar la plantilla &lt;' . $this->_vista->datos['nombre'] . '&gt;?';
        $this->_vista->renderizar('eliminar');
    }

    public function listar($pagina = 1) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar('');
        }
        $npag = intval(($this->_plantilla->contarPlantilla() - 1) / REG_PAG) + 1;
        $pagina = $this->aInt($pagina);
        if ($pagina <= 0 || $pagina > $npag) {
            $pagina = 1;
        }
        $this->_vista->datos = $this->_plantilla->getPlantillas($pagina);
        $this->_vista->getPaginacion($pagina, $npag, 'plantilla/listar/');
        $this->_vista->titulo = 'Lista de Plantillas';
        $this->_vista->renderizar('listar');
    }

    public function rmdir($carpeta) {
        if (is_dir($carpeta) && !is_link($carpeta)) {
            if ($dh = opendir($carpeta)) {
                while (($sf = readdir($dh)) !== false) {
                    if ($sf == '.' || $sf == '..') {
                        continue;
                    }
                    if (!$this->rmdir($carpeta . SD . $sf)) {
                        throw new Exception($carpeta . '/' . $sf . ' no se pudo eliminar');
                    }
                }
                closedir($dh);
            }
            return rmdir($carpeta);
        }
        return unlink($carpeta);
    }

}
