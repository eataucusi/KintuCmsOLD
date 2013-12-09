<?php
/**
 * Archivo 'Sesion.php'
 * 
 * Archivo que define la clase 'Sesion'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:31:24 AM
 */
/**
 * Clase 'Sesion'
 * 
 * Esta clase se encarga de manejar las sesiones
 */
class Sesion {
    /**
     * Metodo que inicia una Sesion
     */
    public static function iniciar() {
        session_start();
        self::tiempo();
    }
    
    /**
     * Metodo que elimina una variable de sesion o elimina la sesion
     * @param string $clave variable de sesion
     */
    public static function matar($clave = FALSE) {
        if ($clave) {
            if (is_array($clave)) {
                for ($i = 0; $i < count($clave); $i++) {
                    if (isset($_SESSION[$clave[$i]])) {
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            } else {
                unset($_SESSION[$clave]);
            }
        } else {
            session_destroy();
        }
    }
    
    /**
     * Metodo que guarda una variable en la sesion
     * @param type $clave
     * @param type $valor
     */
    public static function set($clave, $valor) {
        if (!empty($clave)) {
            $_SESSION[$clave] = $valor;
        }
    }
    
    /**
     * Metodo que obtiene una variable almacenada en la sesion
     * @param type $clave
     * @return string
     */
    public static function get($clave) {
        if (isset($_SESSION[$clave])) {
            return $_SESSION[$clave];
        }
        return '';
    }
    
    /**
     * Metodo que obtiene el nivel(id) de un rol
     * @param type $rol
     * @return type
     */
    public static function getNivel($rol) {
        $_rol = Bd::getIntancia();
        return $_rol->getScalar('SELECT id FROM roles WHERE rol = ?', array($rol));
    }
    
    /**
     * Metodo que maneja los permisos
     * @param type $rol
     */
    public static function acceso($rol) {
        if (!Sesion::get('autenticado')) {
            header('location: ' . URL_BASE . 'traspie/acceso/402');
            exit(0);
        }
        if (Sesion::getNivel($rol) > Sesion::get('nivel')) {
            header('location: ' . URL_BASE . 'traspie/acceso/401');
            exit(0);
        }
    }
    
    public static function accesoVista($rol) {
        if (!Sesion::get('autenticado')) {
            return FALSE;
        }
        if (Sesion::getNivel($rol) > Sesion::get('nivel')) {
            return FALSE;
        }
        return TRUE;
    }
    
    public static function regenerar() {
        session_regenerate_id(TRUE);
    }


    public static function tiempo() {
        if (Sesion::get('autenticado')) {
            if (time() - Sesion::get('tiempo') > (TIEMPO_SESION * 60)) {
                Sesion::matar();
                header('location: ' . URL_BASE . 'traspie/acceso/403');
                exit(0);
            }
            if (Sesion::get('ip') != $_SERVER['REMOTE_ADDR']) {
                Sesion::matar();
                header('location: ' . URL_BASE . 'traspie/acceso/403');
                exit(0);
            }
            Sesion::set('tiempo', time());
        }
    }
}