<?php
/**
 * Archivo 'archivoModelo.php'
 * 
 * Este archivo define la clase 'archivoModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 10/03/2013 08:22:31 AM
 */
/**
 * Clase 'archivoModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla archivos de la bd
 * 
 * @package Modelo
 */
class archivoModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }

    public function insertarArchivo($usuario, $nombre, $desc, $para) {
        $this->_bd->ejecutar('INSERT INTO archivos VALUES(NULL, ?, ?, NOW(), 0, ?, ?)', array($usuario, $nombre, $desc, $para));
    }
    
    public function contarArchivos($para) {
        return $this->_bd->getScalar('SELECT COUNT(id) FROM archivos WHERE para = ?', array($para));
    }
    
    public function getArchivos($para, $pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT * FROM archivos WHERE para = ? ORDER BY id DESC LIMIT ?, ?', array($para, $pagina, REG_PAG));
    }
    
    public function getArchivo($id) {
        return $this->_bd->getFila('SELECT * FROM archivos WHERE id = ?', array($id));
    }
    
    public function editarArchivo($id, $descripcion) {
        $this->_bd->ejecutar('UPDATE archivos SET descripcion = ? WHERE id = ?', array($descripcion, $id));
    }
    
    public function eliminarArchivo($id) {
        $this->_bd->ejecutar('DELETE FROM archivos WHERE id = ?', array($id));
    }
    
    public function existeNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM archivos WHERE nombre = ?', array($nombre)); 
    }

    public function descaragarArchivo($id) {
        $this->_bd->ejecutar('UPDATE archivos SET descarga = descarga + 1 WHERE id = ?', array($id));
    }
    
    public function contarImg() {
        return $this->_bd->getScalar('SELECT COUNT(id) FROM archivos WHERE para = ' .  "'articulos'");
    }
    
    public function getImgs($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT * FROM archivos WHERE para = ? ORDER BY id DESC LIMIT ?, ?', array('articulos' ,$pagina, REG_PAG));
    }
}