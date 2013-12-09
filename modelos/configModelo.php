<?php
/**
 * Archivo 'configModelo.php'
 * 
 * Este archivo define la clase 'configModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:22:31 PM
 */
/**
 * Clase 'configModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla control de la bd
 * 
 * @package Modelo
 */
class configModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }

    public function getInicio() {
        return $this->_bd->getScalar('SELECT valor FROM control WHERE parametro = "inicio"');
    }
    
    public function setInicio($inicio) {
        $this->_bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = "inicio"', array($inicio));
    }
    
    public function getConfig() {
        return $this->_bd->getArray('SELECT * FROM control WHERE id < 8');
    }
    
    public function setParametro($parametro, $valor) {
        $this->_bd->ejecutar('UPDATE control SET valor = ? WHERE parametro = ?', array($valor, $parametro));
    }
}