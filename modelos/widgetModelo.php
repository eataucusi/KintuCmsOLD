<?php
/**
 * Archivo 'widgetModelo.php'
 * 
 * Este archivo define la clase 'rolModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:22:31 PM
 */
/**
 * Clase 'widgetModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla widget de la bd
 * 
 * @package Modelo
 */
class widgetModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function existeNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM widget WHERE nombre = ?', array($nombre));
    }
    
    public function insertarWidget($nombre, $titulo, $cuerpo, $visible, $peso) {
         $this->_bd->ejecutar('INSERT INTO widget VALUES(NULL, ?, ?, ?, ?, ?)', array($nombre, $titulo, $cuerpo, $visible, $peso));
    }
    
    public function editarWidget($nombre, $titulo, $cuerpo, $visible, $peso, $id) {
         $this->_bd->ejecutar('UPDATE widget SET nombre = ?, titulo = ?, cuerpo = ?, visible = ?, peso = ? WHERE id = ?', array($nombre, $titulo, $cuerpo, $visible, $peso, $id));
    }
    
    public function contarWidget() {
         return $this->_bd->getScalar('SELECT COUNT(id) FROM widget');
    }
    
    public function getWidgets($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT p.id, p.nombre, p.titulo, p.visible, p.peso FROM widget AS p ORDER BY p.id DESC LIMIT ?, ?', array($pagina, REG_PAG));
    }
    
    public function getWidget($id) {
        return $this->_bd->getFila('SELECT * FROM widget WHERE id = ?', array($id));
    }
    
    public function habilitar($id) {
        $this->_bd->ejecutar('UPDATE widget SET visible = 2 WHERE id = ?', array($id));
    }
    
    public function deshabilitar($id) {
        $this->_bd->ejecutar('UPDATE widget SET visible = 1 WHERE id = ?', array($id));
    }
}