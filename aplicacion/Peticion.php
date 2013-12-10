<?php
/**
 * Archivo que maneja las peticiones
 * 
 * En este archivo se administra las urls de la forma "base_url/controlador/meto
 * do/argumentos".
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 04/01/2013 10:20:01 PM
 */

/**
 * Clase que maneja las peticiones
 * 
 * En esta clase se administra las urls o peticiones de la forma
 * "base_url/controlador/metodo/argumentos", extrayendo el controlador, 
 * el metodo y los argumentos.
 */
class Peticion {
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    public $url;


    public function __construct() {
        if (isset($_GET['url'])) {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $this->url = htmlentities($url);
            $url = explode('/', $url);
            $url = array_filter($url);
            
            $this->_controlador = strtolower(array_shift($url));
            $this->_metodo = strtolower(array_shift($url));
            $this->_argumentos = $url;
        }
        
        if (!$this->_controlador) {
            $this->_controlador = 'index';
        }
        
        if (!$this->_metodo) {
            $this->_metodo = 'index';
        }
        
        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
    }
    
    /**
     * Metodo que retorna el controlador de la url
     * 
     * @return string controlador
     */
    public function getControlador() {
        return $this->_controlador;
    }
    
    /**
     * Metodo que retorna el metodo de la url
     * 
     * @return string metodo
     */
    public function getMetodo() {
        return $this->_metodo;
    }
    
    /**
     * Metodo que retorna los argumentos de la url
     * 
     * @return array argumentos
     */
    public function getArgumentos() {
        return $this->_argumentos;
    }
}