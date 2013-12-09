<?php
/**
 * Archivo que contiene la clase Bootstrap
 * 
 * Este Archivo declara la clase 'Lanzador'. 
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 04/01/2013 10:19:39 PM
 */

/**
 * Clase Lanzador
 * 
 * Esta clase es el encargado de incluir el controlador y ejecutar el metodo
 * con sus respectivos argumentos, todo esto con ayuda de 'Peticion'.
 */
class Lanzador {
    /**
     * Metodo que incluye el controlador y ejecuta el metodo con sus respectivos
     * parametros o argumentos, con ayuda de 'Peticion'.
     * 
     * @param Request $peticion
     * @throws Se produce una excepcion cuando no existe el controlador requerido 
     */
    public static function ejecutar(Peticion $peticion) {
        $controlador = $peticion->getControlador() . 'Controlador';
        $rutaControlador = RAIZ . 'controladores' . SD . $controlador . '.php';
        $metodo = $peticion->getMetodo();
        $argumentos = $peticion->getArgumentos();
        
        if (is_readable($rutaControlador)) {
            require_once $rutaControlador;
            $controlador = new $controlador;
            
            if (!is_callable(array($controlador, $metodo))) {
                header('location:' . URL_BASE . 'traspie/acceso/404');
            }
            
            if (isset($argumentos)) {
                call_user_func_array(array($controlador, $metodo), $argumentos);
            } else {
                call_user_func(array($controlador, $metodo));
            }
        } else {
            if (Sesion::accesoVista('Administrador')) {
                throw new Exception('Controlador no encontrado: ' . $controlador);
            }  else {
                header('location:' . URL_BASE . 'traspie/acceso/404');
            }          
        }
    }
}