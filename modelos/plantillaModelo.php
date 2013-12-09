<?php
/**
 * Archivo 'plantillaModelo.php'
 * 
 * Este archivo define la clase 'plantillaModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:22:31 PM
 */
/**
 * Clase 'plantillaModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla plantilla de la bd
 * 
 * @package Modelo
 */
class plantillaModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function existeNombre($nombre) {
        return $this->_bd->getScalar('SELECT id FROM plantilla WHERE nombre = ?', array($nombre));
    }
    
    public function insertarPlantilla($nombre, $para, $creado, $autor, $emailAutor, $urlAutor, $detalle, $img) {
         $this->_bd->ejecutar('INSERT INTO plantilla VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, 1)', array($nombre, $para, $creado, $autor, $emailAutor, $urlAutor, $detalle, $img));
    }
    
    public function insertarPosicion($plantilla, $nombre) {
        $this->_bd->ejecutar('INSERT INTO posicion VALUES(NULL, ?, ?)', array($plantilla, $nombre));
    }

    public function editarPlantilla($nombre, $titulo, $cuerpo, $visible, $peso, $id) {
         $this->_bd->ejecutar('UPDATE plantilla SET nombre = ?, titulo = ?, cuerpo = ?, visible = ?, peso = ? WHERE id = ?', array($nombre, $titulo, $cuerpo, $visible, $peso, $id));
    }
    
    public function eliminarPlantilla($id) {
         $this->_bd->ejecutar('DELETE FROM plantilla WHERE id = ?', array($id));
    }
    
    public function contarPlantilla() {
         return $this->_bd->getScalar('SELECT COUNT(id) FROM plantilla');
    }
    
    public function getPlantillas($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT id, nombre, para, defecto FROM plantilla LIMIT ?, ?', array($pagina, REG_PAG));
    }
    
    public function getPlantilla($id) {
        return $this->_bd->getFila('SELECT * FROM plantilla WHERE id = ?', array($id));
    }
    
    public function habilitar($id) {
        return $this->_bd->ejecutar('UPDATE plantilla SET defecto = 2 WHERE id = ?', array($id));
    }
    public function deshabilitar($para) {
        return $this->_bd->ejecutar('UPDATE plantilla SET defecto = 1 WHERE para = ? AND defecto = 2', array($para));
    }
    
    public function getWidgetAs($plantilla) {
        return $this->_bd->getArray('SELECT w.id, w.nombre, p.id AS pos FROM widget AS w, posicion AS p, asignacion AS a WHERE p.plan_id = ? AND p.id = a.posi_id AND w.id = a.widg_id AND w.visible = 2', array($plantilla));
    }
    
    public function getWidgetsNoAs($plantilla) {
        return $this->_bd->getArray('SELECT id, nombre, NULL AS pos FROM widget WHERE visible = 2 AND nombre NOT IN(SELECT w.nombre FROM widget AS w, posicion AS p, asignacion AS a WHERE p.plan_id = ? AND p.id = a.posi_id AND w.id = a.widg_id AND w.visible = 2)', array($plantilla));
    }
    
    public function asignar($posicion, $widget, $nuevop) {
        $pos = $this->_bd->getScalar('SELECT posi_id FROM asignacion WHERE widg_id = ? AND posi_id = ?', array($widget, $posicion));
        if ($pos) {
            $this->_bd->ejecutar('UPDATE asignacion SET posi_id = ? WHERE widg_id = ? AND posi_id = ?', array($nuevop, $widget, $posicion));
        } else {
            $this->_bd->ejecutar('INSERT INTO asignacion VALUES(?, ?)', array($nuevop, $widget));
        }        
    }
    
    public function getPosiciones($plantilla) {
        return $this->_bd->getArray('SELECT id, nombre FROM posicion WHERE plan_id = ?', array($plantilla));
    }
    
}