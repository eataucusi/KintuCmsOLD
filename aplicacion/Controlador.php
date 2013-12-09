<?php
/**
 * Archivo 'Controlador'
 * 
 * En este archivo se declara la clase 'Controlador', del cual extenderan los
 * otros controllers.
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 31/01/2013 10:19:09 PM
 */

/**
 * Clase 'Controlador' del cual iran extendiendo los demas controladores
 * 
 * Esta clase es la clase padre de todos los controladores por ejemplo: indexControlador.
 * Tiene un metodo abstacto index, que sera heredado obligatoriamente para sus hijos.
 */
abstract class Controlador {
    /**@var Vista*/
    protected $_vista;
    
    public function __construct() {
        $this->_vista = new Vista(new Peticion());
        if (ADMIN == ''){
            $this->_vista->setJs('jquery.Wslider');
            $this->_vista->setJs('jquery.menu');
        }
    }
    
    abstract public function index();  

    /**
     * Metodo que retorna la  instancia de un modelo
     * 
     * @param type $modelo
     * @return \modelo
     * @throws Exception modelo no encontrado
     */
    protected function getModelo($modelo) {
        $modelo = $modelo . 'Modelo';
        $rutaModelo = RAIZ . 'modelos' . SD . $modelo . '.php';
        
        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
            $modelo = new $modelo;
            return $modelo;
        }
        throw new Exception('Error modelo no encontrado');
    }
    
    
    protected function getLibreria($libreria) {
        $rutaLibreria = RAIZ . 'librerias' . SD . $libreria . '.php';
        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error libreria no encontrada');
        }
    }


    /**
     * Metodo que Redirecciona a una url
     * 
     * @param type $ruta
     */
    public function redireccionar($ruta = FALSE, $hash = '') {
        if ($ruta) {
            header('location: ' . URL_BASE . $ruta . ADMIN . $hash);
            exit(0);
        }
        header('location: ' . URL_BASE . ADMIN . $hash);
        exit(0);
    }

    /**
     * Metodo que convierte a enteros
     * 
     * @param type $int
     * @return int
     */
    protected function aInt($int) {
        $int = (int) $int;
        if (is_int($int)) {
            return $int;
        }
        return 0;
    }

    /**
     * Metodo que obtuene un entero enviado por post
     * 
     * @param type $clave
     * @return int
     */
    protected function getInt($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }
            return filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
        }
        return 0;
    }
    
    /**
     * Metodo que obtine un texto enviad por post
     * 
     * @param type $clave
     * @return string
     */
    protected function getTexto($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            return htmlspecialchars($_POST[$clave], ENT_NOQUOTES);
        }
        return '';
    }
    
    /**
     * Metodo que obtiene un campo alternativo enviado por post
     * 
     * @param type $clave
     * @return null
     */
    protected function getAlt($clave) {
        if (isset($_POST[$clave])) {
            if (!empty($_POST[$clave])) {
                if ('ninguno' == strtolower($_POST[$clave])) {
                    return NULL;
                }
                return $_POST[$clave];
            }
            return NULL;
        }
    } 
    
    
    protected function getAlphaNum($clave, $tam = 6) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $cad = strtolower($_POST[$clave]);
            if (preg_match('/^[a-z0-9_\-]{' .$tam. ',}$/', $cad)) {
                return $cad;
            }
        }
        return FALSE;
    }
    
    protected function getEmail($clave) {
        if (filter_input(INPUT_POST, $clave, FILTER_VALIDATE_EMAIL)) {
            return $_POST[$clave];
        }
        return FALSE;
    }
    
    protected function getHash($pass) {
        $hash = hash_init('md5', HASH_HMAC, HASH_KEY);
        hash_update($hash, $pass);
        return hash_final($hash);
    }
    
    protected function getPass($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            if (preg_match('/^.{6,}$/', $_POST[$clave])) {
                return $this->getHash($_POST[$clave]);
            }
        }
        return FALSE;
    }
    
    protected function getHtml($clave, $alt = FALSE) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            return $_POST[$clave];
        }
        if ($alt) {
            return NULL;
        }
        return FALSE;
    }
    
    protected function dump($var) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        exit(0);
    }
    
    protected function _fechaTexto($fecha) {
        $fecha = explode(" ", $fecha);
        $hora = $fecha[1];
        $fecha = $fecha[0];
        $partes = array();
        if (!preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/', $fecha, $partes)) {
            return '';
        }
        $meses = array('error','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
        $partes[2] = intval($partes[2]);
        return $partes[3] . ' de ' . $meses[$partes[2]] . ' de ' . $partes[1] . ' a las ' . $hora;
    }
}