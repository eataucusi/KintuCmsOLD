<?php

/**
 * Archivo 'sliderControlador'
 * 
 * Archivo que define la clase 'sliderControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 02/04/2013 07:56:00 PM
 */
/**
 * Clase 'sliderControlador'
 * 
 * Esta clase es el controlador del slider
 * 
 * @package Controlador
 */
class sliderControlador extends Controlador {
    /**@var SliderModelo*/
    private $_slider;
    public function __construct() {
        parent::__construct();
        $this->_slider = $this->getModelo('slider');
    }

    public function index() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->titulo = 'Módulo Slider';
        $this->_vista->renderizar('index');
    }
    
    public function nuevo() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if (!$this->getTexto('descripcion')) {
                $this->_vista->error[] = 'Descripción: este campo no puede ser nulo.';
            } else {
                $this->getLibreria('Imagen');
                $img = new Imagen();
                $img->setImagen('img');
                $dir = RAIZ . 'publico' . SD . 'img' . SD . 'slides' . SD;
                if (!$img->subir($dir)) {
                    $this->_vista->error[] = 'Imagen: ' . $img->getMensaje();
                }
            }
            if (!isset($this->_vista->error)) {
                $img->getImagen($img->getDireccion());
                $img->ajustar(900, 200);
                $img->guardar($dir);
                $this->_slider->insertar( $this->getTexto('descripcion'), $img->_nuevoNombre);
                $this->redireccionar('slider/listar');
            }
        }
        $this->_vista->titulo = 'Agregar imagen a Slider';
        $this->_vista->renderizar('nuevo');
    }
    
    public function listar() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->titulo = 'Lista de imágenes del Slider';
        $this->_vista->slider = $this->_slider->listar();
        $this->_vista->renderizar('listar');
    }
    
    public function eliminar($id) {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $id = $this->aInt($id);
        if (!$id) {
            $this->redireccionar('slider/listar');
        }
        $this->_vista->datos = $this->_slider->getSlider($id);
        if (!$this->_vista->datos) {
            $this->redireccionar('slider/listar');
        }
        if ($this->getInt('guardar') == 1) {
            unlink(RAIZ . 'publico' . SD . 'img' . SD . 'slides' . SD . $this->_vista->datos['ruta']);
            $this->_slider->eliminar($id);
            $this->redireccionar('slider/listar');
        }
        $this->_vista->titulo = 'Eliminar imagen de Slider';
        $this->_vista->renderizar('eliminar');
    }
}