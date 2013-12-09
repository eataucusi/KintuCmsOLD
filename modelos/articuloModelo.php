<?php
/**
 * Archivo 'articuloModelo.php'
 * 
 * Este archivo define la clase 'articuloModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 02/03/2013 07:36:30 PM
 */
/**
 * Clase 'articuloModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla articulos de la bd
 * 
 * @package Modelo
 */
class articuloModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function existeNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM articulos WHERE nombre = ?', array($nombre));
    }
    
    public function insertarArticulo($usuario, $categoria, $nombre, $titulo, $resumen, $cuerpo, $meta) {        
        $this->_bd->ejecutar('INSERT INTO articulos VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, 2, 0, now(), NULL)', array($usuario, $categoria, $nombre, $titulo, $resumen, $cuerpo, $meta));
    }
    
    public function getArticulo($id) {
        return $this->_bd->getFila('SELECT * FROM articulos WHERE id = ?', array($id));
    }
    
    public function getArticuloNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM articulos WHERE nombre = ?', array($nombre));
    }
    
    public function getArticulos($id_cate, $pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT p.id, p.nombre, p.estado, p.leido, u.login FROM articulos AS p, usuarios AS u WHERE p.cate_id = ? AND p.usua_id = u.id ORDER BY p.id DESC LIMIT ?, ?', array($id_cate, $pagina, REG_PAG));
    }
    
    public function contarArticulos($id_cate = FALSE) {
        if ($id_cate) {
            return $this->_bd->getScalar('SELECT COUNT(id) FROM articulos WHERE cate_id = ?', array($id_cate));
        }
        return $this->_bd->getScalar('SELECT COUNT(id) FROM articulos');
    }
    
    public function editarArticulo($id, $usuario, $categoria, $nombre, $titulo, $resumen, $cuerpo, $meta, $estado) {
        return $this->_bd->ejecutar('UPDATE articulos SET usua_id = ?, cate_id = ?, nombre = ?, titulo = ?, resumen = ?, cuerpo = ?, meta = ?, estado = ?, modificado = NOW() WHERE id = ?', array($usuario, $categoria, $nombre, $titulo, $resumen, $cuerpo, $meta, $estado, $id));
    }
    
    public function habilitarArticulo($id) {
        return $this->_bd->ejecutar('UPDATE articulos SET estado = 2 WHERE id = ?', array($id));
    }
    
    public function deshabilitarArticulo($id) {
        return $this->_bd->ejecutar('UPDATE articulos SET estado = 1 WHERE id = ?', array($id));
    }
    
    public function verArticulo($id) {
        $this->_bd->ejecutar('UPDATE articulos SET leido = leido + 1 WHERE id = ?', array($id));
        return $this->_bd->getFila('SELECT p.resumen, p.titulo, p.cuerpo, p.meta, p.estado, p.creado, p.cate_id, p.leido, u.login, u.acerca, u.foto, c.categoria, c.img, c.nombre FROM articulos AS p, usuarios AS u, categorias AS c WHERE p.usua_id = u.id AND p.cate_id = c.id AND p.id = ?', array($id));
    }
    
    public function getRelacionado($id, $categoria) {
        return $this->_bd->getArray('SELECT p.titulo, p.nombre FROM articulos AS p, categorias AS c WHERE p.estado = 2 AND p.cate_id = c.id AND p.id <> ? AND p.cate_id = ?', array($id, $categoria));
    }
    
    public function getNombreArticulo($id) {
        return $this->_bd->getFila('SELECT nombre, usua_id FROM articulos WHERE id = ?', array($id));
    }
    
    public function articuloCategorias($padre = FALSE) {
        if (!$padre) {
            return $this->_bd->getArray('SELECT COUNT(p.id) AS narticulo, c.id, c.nombre, c.categoria, c.img FROM articulos AS p, categorias AS c WHERE p.cate_id = c.id AND c.padre IS NULL GROUP BY c.id');
        }
    }
    
    public function getCategoria($id, $pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT p.nombre,p.id, p.titulo, p.leido, p.creado, p.resumen, u.login, c.categoria, c.img, c.detalle FROM articulos AS p, usuarios AS u, categorias AS c WHERE p.cate_id = ? AND u.id = p.usua_id AND p.cate_id = c.id AND p.estado = 2 ORDER BY p.id DESC LIMIT ?, ?', array($id, $pagina, REG_PAG));
    }
    
    public function getUltimos($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT p.nombre, p.titulo, p.id, p.leido, p.creado, p.resumen, u.login, c.categoria, c.nombre as cnombre, c.img FROM articulos AS p, usuarios AS u, categorias AS c WHERE u.id = p.usua_id AND p.cate_id = c.id AND p.estado = 2 ORDER BY p.id DESC LIMIT ?, ?', array($pagina, REG_PAG));
    }
    
    public function getMasLeidos() {
        return $this->_bd->getArray('SELECT a.nombre, a.titulo, a.leido, c.categoria, u.login FROM articulos AS a, categorias AS c, usuarios AS u WHERE a.cate_id = c.id AND u.id = a.usua_id ORDER BY a.leido DESC LIMIT 10');
    }
}