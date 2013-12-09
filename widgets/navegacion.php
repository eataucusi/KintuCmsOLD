<?php
class navegacionWidget extends Widget{

    public function __construct(){}

    public function getHtml($peticion) {
        return $this->renderizar('navegacion', array('peticion' => $peticion));
    }    
}