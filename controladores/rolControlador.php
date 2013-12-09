<?php
/**
 * Archivo 'rolControlador'
 * 
 * En este archivo de define la clase 'rolControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 25/02/2013 04:37:50 PM
 */
/**
 * Clase 'rolControlador'
 * 
 * Esta clase nos permitira manejar los roles de la aplicación
 * 
 * @package Controlador
 */
class rolControlador extends Controlador {
    /**@var rolModelo*/
    private $_rol;
    public function __construct() {
        parent::__construct();
        $this->_rol = $this->getModelo('rol');
    }
    
    public function index() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $this->listar();
    }  
    
    public function listar() {
        if (!Sesion::accesoVista('Publicador')) {
            $this->redireccionar();
        }
        $this->_vista->datos = $this->_rol->listarRoles();
        $this->_vista->titulo = 'Lista de roles';
        $this->_vista->renderizar('listar');
    }        
}