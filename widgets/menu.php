<?php
class menuWidget extends Widget{
    /** @var menuModeloWidget */
    private $_modelo;
    public function __construct() {
        $this->_modelo = $this->getModelo('menu');
    }

    public function getHtml($peticion = '') {
        return $this->renderizar('menu', array(
            'menu' => $this->_modelo->getMenus()
        ));
    }    
}