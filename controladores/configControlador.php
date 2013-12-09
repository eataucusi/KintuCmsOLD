<?php

/**
 * Archivo 'configControlador'
 * 
 * En este archivo de define la clase 'configControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:37:50 PM
 */

/**
 * Clase 'configControlador'
 * 
 * Esta clase nos permitira manejar la coniguracion de la config de la aplicación
 * 
 * @package Controlador
 */
class configControlador extends Controlador {
    /** @var configModelo */

    private $_config;

    public function __construct() {
        parent::__construct();
        $this->_config = $this->getModelo('config');
    }

    public function index() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->titulo = 'Configuración de Sitio';
        $this->_vista->renderizar('index');
    }

    public function principal() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->datos = $this->_config->getInicio();
        if ($this->getInt('guardar') == 1) {
            if (!$this->getTexto('url')) {
                $this->_vista->error[] = 'La página de inicio no puede ser vacío.';
                $this->_vista->datos = '';
            } else {
                $this->_config->setInicio($this->getTexto('url'));
                Sesion::set('msj', 'Se ha guardado su modificación');
                $this->redireccionar('config/principal');
            }
        }
        $this->_vista->setJs('ajax/menu');
        $this->_vista->titulo = 'Configuración de página de inicio';
        $this->_vista->renderizar('principal');
    }

    public function ver() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->datos = $this->_config->getConfig();
        $this->_vista->titulo = 'Parámetros de Sitio';
        $this->_vista->renderizar('ver');
    }

    public function editar() {
        if (!Sesion::accesoVista('Administrador')) {
            $this->redireccionar();
        }
        $this->_vista->datos = $this->_config->getConfig();
        if ($this->getInt('guardar') == 1) {
            $this->_vista->post = $_POST;
            for ($i = 0; $i < count($this->_vista->datos); $i++) {
                if (!$this->getTexto($this->_vista->datos[$i]['parametro'])) {
                    $this->_vista->error[] = 'El campo ' . $this->_vista->datos[$i]['parametro'] . ', no puede ser vacío';
                }
            }
            if (!isset($this->_vista->error)) {
                for ($i = 0; $i < count($this->_vista->datos); $i++) {
                    $this->_config->setParametro($this->_vista->datos[$i]['parametro'], $this->getTexto($this->_vista->datos[$i]['parametro']));
                }
                Sesion::set('msj', 'Se ha guardado su configuración.');
                $this->redireccionar('config/ver');
            }
        }
        $this->_vista->titulo = 'Configuración parámetros de Sitio';
        $this->_vista->renderizar('editar');
    }

}
