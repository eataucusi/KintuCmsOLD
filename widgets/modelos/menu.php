<?php
class menuModeloWidget extends Modelo {


    public function __construct() {
        parent::__construct();
    }

    /**
     * Metodo que retorna toda la estructura del menu
     * @return array
     */
    public function getMenus($rol = FALSE) {
        if (!$rol) {
            if (!ES_ADMIN) {
                $rol = 2;
            } else {
                $rol = Sesion::get('nivel') ? Sesion::get('nivel') : 2;
            }
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
     * Metodo que retorna los menus principlaes
     * @return Array
     */
    public function getPadres($rol = FALSE) {
        if (!$rol) {
            return $this->_bd->getArray('SELECT id, texto, url, ventana, class FROM menus WHERE padre is NULL');
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
            return $this->_bd->getArray('SELECT id, texto, url,class,detalle, ventana FROM menus WHERE padre = ?', array($id));
        }
        return $this->_bd->getArray('SELECT id, texto, url,class,detalle, ventana FROM menus WHERE padre = ? AND role_id = ?', array($id, $rol));
    }

}
