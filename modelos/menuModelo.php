<?php
/**
 * Archivo menuModelo
 * 
 * Archivo que define la clase 'menuModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 08/02/2013 03:34:55 PM
 */
/**
 * Clase 'menuModelo'
 * 
 * Este modelo gestionara los menus desde la base de datos
 * 
 * @package Modelo
 */
class menuModelo extends Modelo {

    public function __construct() {
        parent::__construct();
    }
    /**
     * Metodo que retorna los menus principlaes
     * @return Array
     */
    public function getPadres($rol = FALSE) {
        if (!$rol) {
            return $this->_bd->getArray('SELECT id, texto, url, class, ventana FROM menus WHERE padre is NULL');            
        }
        return $this->_bd->getArray('SELECT id, texto, url, class, ventana FROM menus WHERE padre is NULL AND role_id = ?', array($rol));
    }
    
    /**
     * Metodo que retorna los hijos de un menu
     * @param string $id
     * @return array
     */
    public function getHijos($id, $rol = FALSE) { 
        if (!$rol) {
            return $this->_bd->getArray('SELECT id, texto, url, class, detalle, ventana FROM menus WHERE padre = ?', array($id));
        }
        return $this->_bd->getArray('SELECT id, texto, url,class,detalle, ventana FROM menus WHERE padre = ? AND role_id = ?', array($id, $rol));
    }
    
    /**
     * Metodo que retorna toda la estructura del menu
     * @return array
     */
    public function getMenus($rol = FALSE) {
        if (!$rol) {
            $rol = Sesion::get('nivel')? Sesion::get('nivel'): 2;
        }
        $menus = $this->getPadres($rol);
        $menu = array();        
        for ($i = 0; $i < count($menus); $i++) {
            $hijos = $this->getHijos($menus[$i]['id'], $rol);
            if ($hijos) {
                $submenu = array();
                for ($j = 0; $j < count($hijos); $j++) {
                    $nieto = $this->getHijos($hijos[$j]['id'], $rol);  
                    $submenu[] = array('menu' => $hijos[$j], 'submenu' => $nieto);
                }   
                $menu[] = array('menu' => $menus[$i], 'submenu' => $submenu);
            } else {
                $menu[] = array('menu' => $menus[$i], 'submenu' => FALSE);
            }
        }
        return $menu;
    }
    
    /**
     * Metodo que retorna el submenú
     * @return array
     */
    public function getSubmenu($controlador, $metodo) {
        $url = ($metodo == 'index')? $controlador: $controlador . '/' . $metodo;
        $id = $this->_bd->getFila('SELECT id, texto FROM menus WHERE url = ? AND role_id = ?', array($url, Sesion::get('nivel')));  
        $ar = array('url' => $url, 'txt' => $id['texto'], 'submenu' => $this->getHijos($id['id'], Sesion::get('nivel')));
        return $ar;
    }    
   
    /**
     * Metodo que retorna los datos de un menú
     * @param type $id
     */
    public function getMenu($id) {
        return $this->_bd->getFila('SELECT * FROM menus WHERE id = ?', array($id));
    }
    
    /**
     * Metodo que inserta un menú
     * @return array
     */
    public function insertarMenu($rol, $texto, $url, $detalle, $class, $padre, $ventana) {
        $this->_bd->ejecutar('INSERT INTO menus VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)', array($rol, $texto, $url, $detalle, $class, $padre, $ventana));
    }
        
    /**
     * Metodo que modifica un menú
     * @return array
     */
    public function editarMenu($id, $rol, $texto, $url, $detalle, $class, $padre, $ventana) {
        $this->_bd->ejecutar('UPDATE menus SET role_id = ?, texto = ?, url = ?, detalle = ?, class = ?, padre = ?, ventana = ? WHERE id = ?', array($rol, $texto, $url, $detalle, $class, $padre, $ventana, $id));
    }
    
    /**
     * Metodo que elimina un menú
     * @return array
     */
    public function eliminarMenu($id) {
        $this->_bd->ejecutar('DELETE FROM menus WHERE id = ?', array($id));
    }
    
    public function getCategorias() {
        return $this->_bd->getArray('SELECT nombre FROM categorias');
    }
    
    public function nArticulos() {
        return $this->_bd->getScalar('SELECT COUNT(id) FROM articulos WHERE estado = 2');
    } 
    
    public function getArticulos($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT nombre FROM articulos WHERE estado = 2 ORDER BY creado DESC LIMIT ?, ?', array($pagina, REG_PAG));
    }    
}