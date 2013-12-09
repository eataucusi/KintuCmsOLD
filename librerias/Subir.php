<?php
/**
 * Archivo 'Subir.php'
 * 
 * Archivo que define la clase 'Subir'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 10/03/2013 03:53:19 AM
 */
/**
 * Clase Subir
 * 
 * Esta clase gestiona la subida de archivos
 */
class Subir {
    private $_nombre;
    private $_tamanio;
    private $_temp;
    private $_extension;    
    private $_carpeta;
    private $_maxTamanio;    
    private $_nuevoNombre;
    private $_msj;
    private $_bloqueo;
    
    public function __construct() {
        $this->_bloqueo = array('php', 'phtml', 'php3', 'php4', 'shtml', 'pl', 'py');
        $this->_msj = "";
    }
    
    public function setFile($clave) {
        $this->_tamanio = $_FILES[$clave]['size'];
        $this->_nombre = $_FILES[$clave]['name'];
        $this->_temp = $_FILES[$clave]['tmp_name'];
        if (empty($this->_nombre)) {
            $this->_msj = 'No se envió ningún archivo.';
            return FALSE;
        }
        return TRUE;
    }
    
    public function guardar($carpeta, $maxTamanio = 2048) {
        $this->_extension = substr($this->_nombre, strrpos($this->_nombre, '.') + 1);
        $this->_maxTamanio = $maxTamanio * 1024;
        $this->_nuevoNombre =  uniqid() . '_' . rand(1,999) . '.' . $this->_extension;
        $this->_carpeta = $carpeta;
        if (is_uploaded_file($this->_temp)) {      
        
            if (!$this->_nombre) {
                $this->_msj = 'No subido';
                return FALSE;
            }

            if ($this->_tamanio > $this->_maxTamanio) {
                $this->_msj = 'Muy grande';
                return FALSE;
            }

            if (in_array($this->_extension, $this->_bloqueo)) {
                $this->_msj = 'tipo de archivo no válido *.' . $this->_extension;
                return FALSE;
            }

            if (move_uploaded_file($this->_temp, $this->_carpeta . $this->_nuevoNombre)) {
                if (chmod($this->_carpeta . $this->_nuevoNombre, 0777)) {
                    $this->_msj = 'ok';
                    return TRUE;
                }
                $this->_msj = 'No se pudo establecer permisos';
                return FALSE;
            } else {
                $this->_msj = 'No tiene permisos';
                return FALSE;
            }
        } else {
            $this->_msj = 'Tamaño máximo superado';
            return FALSE;
        }
    }
    
    public function getNombre() {
        return $this->_nuevoNombre;
    }
    
    public function getMsj() {
        return $this->_msj;
    }
    
    public function getExt() {
        return $this->_extension;
    }    
    public function getAntNombre() {
        return substr($this->_nombre, 0, strpos($this->_nombre, $this->_extension) - 1);
    }
}