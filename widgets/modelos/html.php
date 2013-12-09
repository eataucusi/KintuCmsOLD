<?php

class htmlModeloWidget extends Modelo {
    private $_titulo;
    private $_cuerpo;
    private $_nombre;

    public function __construct() {
        parent::__construct();
    }
    
    public function getWiget($widget) {
        $w = $this->_bd->getFila('SELECT titulo, cuerpo FROM widget WHERE nombre = ?', array($widget));
        $this->_titulo = $w['titulo'];
        $this->_cuerpo = $w['cuerpo'];
        $this->_nombre = $widget;
    }
    
    public function getTitulo() {
        return $this->_titulo;
    }
    public function getCuerpo() {
        return $this->_cuerpo;
    }
    public function getNombre() {
        return $this->_nombre;
    }

}
