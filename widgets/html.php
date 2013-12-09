<?php
class htmlWidget extends Widget{
    /** @var htmlModeloWidget */
    private $_modelo;
    public function __construct() {
        $this->_modelo = $this->getModelo('html');
    }

    public function getHtml($widget, $peticion) {
        $this->_modelo->getWiget($widget);
        return $this->renderizar('html', array(
            'nombre' => $this->_modelo->getNombre(),
            'titulo' => $this->_modelo->getTitulo(),
            'cuerpo' => $this->_modelo->getCuerpo()
        ));
    }    
}