<?php
/**
 * Archivo 'comentarioModelo.php'
 * 
 * Este archivo define la clase 'comentarioModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 05/03/2013 04:22:31 PM
 */
/**
 * Clase 'comentarioModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla comentarios de la bd
 * 
 * @package Modelo
 */
class comentarioModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function addComentario($articulo, $usuario, $coment) {
        $this->_bd->ejecutar('INSERT INTO comentarios VALUES(NULL, ?, ?, ?, NOW())', array($articulo, $usuario, $coment));
    }
    
    public function getComentarios($articulo, $pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT c.id, c.comentario, c.creado, u.login, u.foto FROM comentarios AS c, usuarios AS u WHERE c.arti_id = ? AND c.usua_id = u.id ORDER BY c.id DESC LIMIT ?, ?', array($articulo, $pagina, REG_PAG));
    }
    
    public function contarComentarios($articulo) {
        return $this->_bd->getScalar('SELECT COUNT(id) FROM comentarios WHERE arti_id = ?', array($articulo));
    }
    
    public function getComentario($id) {
        return $this->_bd->getFila('SELECT * FROM comentarios WHERE id = ?', array($id));
    }
    public function eliminarComentario($id) {
        $this->_bd->ejecutar('DELETE FROM comentarios WHERE id = ?', array($id));
    }
    
    public function ultimosComentarios() {
        return $this->_bd->getArray('SELECT c.comentario, c.creado, a.nombre, a.titulo, u.login FROM comentarios AS c, articulos as a, usuarios AS u WHERE a.id = c.arti_id AND u.id = c.usua_id ORDER BY creado DESC LIMIT 10');
    }
}