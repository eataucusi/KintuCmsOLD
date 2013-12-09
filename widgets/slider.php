<?php
class sliderWidget extends Widget{
    /** @var sliderModeloWidget */
    private $_modelo;
    public function __construct() {
        $this->_modelo = $this->getModelo('slider');
    }

    public function getHtml($peticion) {
        return $this->renderizar('slider', array('slides' => $this->_modelo->getSlides()));
    }    
}