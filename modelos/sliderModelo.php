<?php

/**
 * Archivo 'sliderModelo'
 * 
 * En este archivo se define la clase 'sliderModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 02/04/2013 10:44:19 PM
 */
/**
 * Clase 'sliderModelo'
 * 
 * Esta clase nos permite interactuar con la table slider de la base de datos.
 * 
 * @package Modelo
 */
class sliderModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function insertar($descripcion, $ruta) {
        $this->_bd->ejecutar('INSERT INTO slider VALUES(NULL, ?, ?)', array($descripcion, $ruta));
    }
    
    public function listar() {
        return $this->_bd->getArray('SELECT * FROM slider ORDER BY id DESC');
    }
    
    public function getSlider($id) {
        return $this->_bd->getFila('SELECT * FROM slider WHERE id = ?', array($id));
    }
    
    public function eliminar($id) {
        $this->_bd->ejecutar('DELETE FROM slider WHERE id = ?', array($id));
    }
}
