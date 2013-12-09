<?php
class usuarioWidget extends Widget{
    
    public function __construct(){}

    public function getHtml($peticion='') {
        return $this->renderizar('usuario');
    }    
}