<?php

class sliderModeloWidget extends Modelo {

    public function __construct() {
        parent::__construct();
    }
    
    public function getSlides() {
        return $this->_bd->getArray('SELECT * FROM slider');        
    }

}
