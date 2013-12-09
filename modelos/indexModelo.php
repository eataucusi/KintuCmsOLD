<?php
/**
 * Archivo 'indexModelo.php'
 * 
 * Este archivo define la clase 'indexModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:22:31 PM
 */
/**
 * Clase 'indexModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla roles de la bd
 * 
 * @package Modelo
 */
class indexModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Metodo que lista los roles
     * @return type
     */
    public function listarRoles() {
        return $this->_bd->getArray('SELECT * FROM roles');
    }
    
    public function getSelect() {
        return $this->_bd->getArray('SELECT id, rol FROM roles ORDER BY id');
    }
    public function getRol($rol) {
        return $this->_bd->getScalar('SELECT rol FROM roles WHERE id = ?', array($rol));
    }
    
    public function getId($rol) {
        return $this->_bd->getScalar('SELECT id FROM roles WHERE rol = ?', array($rol));
    }
}