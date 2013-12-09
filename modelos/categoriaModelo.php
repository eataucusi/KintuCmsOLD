<?php
/**
 * Archivo 'categoriaModelo.php'
 * 
 * Este archivo define la clase 'categoriaModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 05:24:30 PM
 */
/**
 * Clase 'categoriaModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla categorias de la bd
 * 
 * @package Modelo
 */
class categoriaModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    

    public function getPadres() {
        return $this->_bd->getArray('SELECT id, categoria, detalle, img, nombre FROM categorias WHERE padre is NULL');
    }
    

    public function getHijos($id) {
        return $this->_bd->getArray('SELECT id, categoria, detalle, img, nombre FROM categorias WHERE padre = ?', array($id));
    }
    
    public function existeNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM categorias WHERE nombre = ?', array($nombre));
    }

   
    public function getCategorias() {
        $menus = $this->getPadres();
        $menu = array();
        if ($menus) {
            for ($i = 0; $i < count($menus); $i++) {
                $hijos = $this->getHijos($menus[$i]['id']);
                if ($hijos) {
                    $submenu = array();
                    for ($j = 0; $j < count($hijos); $j++) {
                        $nieto = $this->getHijos($hijos[$j]['id']); 
                        if ($nieto) {
                            for ($k = 0; $k < count($nieto); $k++) {
                                $nieto[$k]['narticulo'] = $this->contarArticulo($nieto[$k]['id']);
                            }
                        }
                        $submenu[] = array('menu' => $hijos[$j],'narticulo' => $this->contarArticulo($hijos[$j]['id']) , 'submenu' => $nieto);
                    }   
                    $menu[] = array('menu' => $menus[$i], 'narticulo' => $this->contarArticulo($menus[$i]['id']), 'submenu' => $submenu);
                } else {
                    $menu[] = array('menu' => $menus[$i], 'narticulo' => $this->contarArticulo($menus[$i]['id']), 'submenu' => FALSE);
                }
            }
        }
        return $menu;
    }
    
    public function getCategoria($id) {
        return $this->_bd->getFila('SELECT * FROM categorias WHERE id = ?', array($id));
    }
    
    public function insertarCategoria($nombre, $categoria, $detalle, $img, $padre) {
        $this->_bd->ejecutar('INSERT INTO categorias VALUES(NULL, ?, ?, ?, ?, ?)', array($nombre, $categoria, $detalle, $img, $padre));
    }
    
    public function editarCategoria($id, $nombre, $categoria, $detalle, $img, $padre) {
        $this->_bd->ejecutar('UPDATE categorias SET nombre = ?,categoria = ?, detalle = ?, img =?, padre = ? WHERE id = ?', array($nombre, $categoria, $detalle, $img, $padre, $id));
    }
    
    public function contarArticulo($categoria) {
        $r = $this->_bd->getScalar('SELECT COUNT(id) FROM articulos WHERE cate_id = ? AND estado = 2', array($categoria));
        if ($r) {
            return $r;
        } else {
            return 0;
        }
    }
    
    public function eliminarCategoria($id) {
        $this->_bd->ejecutar('DELETE FROM categorias WHERE id = ?', array($id));
    }
    
    public function sinCategoria($id) {
        $this->_bd->ejecutar('UPDATE articulos SET cate_id = 1 WHERE cate_id = ?', array($id));
    }

    public function getSelect(array $_menu) {
        $menu = array();
        for ($i = 0; $i < count($_menu); $i++) {
            $menu[] = array('id' => $_menu[$i]['menu']['id'], 'texto' => '-- ' . $_menu[$i]['menu']['categoria']);
            if ($_menu[$i]['submenu']) {
                $_smenu = $_menu[$i]['submenu'];
                
                for ($j = 0; $j < count($_smenu); $j++) {
                    $menu[] = array('id' => $_smenu[$j]['menu']['id'], 'texto' => '---- ' . $_smenu[$j]['menu']['categoria']);
                    
                    if ($_smenu[$j]['submenu']) {
                        $_ssmenu = $_smenu[$j]['submenu'];  
                        
                        for ($k = 0; $k < count($_ssmenu); $k++) {
                            $menu[] = array('id' => $_ssmenu[$k]['id'], 'texto' => '------ ' . $_ssmenu[$k]['categoria']);
                        }
                    }
                }
            }  
        }
        return $menu;
    }
}