<?php
/**
 * Archivo 'traspieControlador'
 * 
 * En este archivo de define la clase 'traspieControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 13/03/2013 04:37:50 PM
 */
/**
 * Clase 'traspieControlador'
 * 
 * Esta clase nos permitira manejar los errores de la aplicación
 * 
 * @package Controlador
 */
class traspieControlador extends Controlador {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->_vista->titulo = '¡Oops!';
        $this->_vista->msj = $this->_getError();
        $this->_vista->renderizar('index');
    }  
    
    public function acceso($codigo = FALSE) {
        $this->_vista->titulo = '¡Oops ha ocurrido un error!';
        $this->_vista->msj = $this->_getError($codigo);
        $this->_vista->renderizar('index');
    }


    private function _getError($codigo = FALSE) {
        if (!$codigo) {
            return 'Ha ocurrido un error y la pagina no puede mostrarse.';
        }
        $error['401'] = 'Acceso prohibido.';
	$error['402'] = 'No tiene los privilegios necesarios para acceder al sitio, necesita registrarse en el sitio para acceder a él.';
	$error['403'] = 'Tiempo de la session expirada.';
        $error['404'] = 'El recurso solicitado ya no existe o ha sido desplazado, o es posible que la dirección esté mal escrita.';
        if (array_key_exists($codigo, $error)) {
            return $error[$codigo];
        }
        return 'Ha ocurrido un error y la pagina no puede mostrarse.';
    }
}