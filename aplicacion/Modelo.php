<?php
/**
 * Archivo 'Modelo'
 * 
 * Este archivo define la calse 'Modelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:30:21 AM
 */
/**
 * Clase 'Modelo'
 * 
 * Modelo principal del cual extenderan los demas modelos, tiene un
 * atributo protegido que hace refencia a la clase 'Bd'
 */
class Modelo {
    /**
     *
     * @var Bd
     */
    protected $_bd;
    
    public function __construct() {
        $this->_bd = Bd::getIntancia();
    }
    
    protected function getTabla() {
        $tabla = get_class($this);
        if (strpos($tabla, 'ModeloWidget')) {
            $tabla = substr($tabla, 0, strpos($tabla, 'ModeloWidget'));
        } else {
            $tabla = substr($tabla, 0, strpos($tabla, 'Modelo'));
        }
        return $tabla;
    }
}