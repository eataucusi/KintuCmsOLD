<?php
/**
 * Archivo 'Imagen.php'
 * 
 * Archivo que define la clase 'Imagen'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 21/02/2013 03:53:19 PM
 */
/**
 * Clase Imagen
 * 
 * Esta clase gestiona la subida y cambio de tamaño de las imágenes
 */
class Imagen {
    private $_nombre;
    private $_tamanio;
    private $_temp;
    private $_tipo;
    private $_extension;    
    private $_carpeta;
    private $_maxTamanio;    
    public $_nuevoNombre;
    private $_msj;
    private $_tipos;
    private $_imagen;

    public function __construct() {
        $this->_msj = '';
        $this->_tipos = array('image/gif', 'image/jpeg', 'image/png');
    }
    
    /**
     * Metodo que recoge las imagenes enviadas por post
     * @param type $clave
     * @param type $maxTamanio
     */
    public function setImagen($clave) {
        if (isset($_FILES[$clave]['name'])) {    
            $this->_nombre = $_FILES[$clave]['name'];
            $this->_tamanio = $_FILES[$clave]['size'];
            $this->_temp = $_FILES[$clave]['tmp_name']; 
            $this->_tipo = $_FILES[$clave]['type'];
        }        
    }
    
    /**
     * Metodo que sube imagenes
     * @param type $carpeta
     * @return boolean
     */
    public function subir($carpeta, $maxTamanio = 1024) {
        if (empty($this->_nombre)) {
            return FALSE;
        }
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
                $this->_msj = 'muy grande';
                return FALSE;
            }

            if (!getimagesize($this->_temp)) {
                $this->_msj = 'imagen invalido';
                return FALSE;
            }

            if (!in_array($this->_tipo, $this->_tipos)) {
                $this->_msj = 'tipo de imagen invalido';
                return $this->_nuevoNombre;
            }

            if (move_uploaded_file($this->_temp, $this->_carpeta . $this->_nuevoNombre)) {
                if (chmod($this->_carpeta . $this->_nuevoNombre, 0777)) {
                    $this->_msj = 'ok';
                    return TRUE;
                }
                $this->_msj = 'No se pudo establecer permisos';
                return FALSE;
            } else {
                $this->_msj = 'No tiene permisos par subir archivos';
                return FALSE;
            }
        } else {
            $this->_msj = 'archivo no enviado';
            return FALSE;
        }
    }
    
    /**
     * Metodo que retorna la direccion del archivo subido
     * @return type
     */
    public function getDireccion() {
        return $this->_carpeta . $this->_nuevoNombre;
    }

    /**
     * Metodo que obtiene una imagen del servidor
     * @param type $archivo
     * @return boolean
     */
    public function getImagen($archivo, $eliminar = TRUE) {
        if (!$info = getimagesize($archivo)) {
            $this->_msj = 'Archivo no encontrado';
            return FALSE;
        }        
        $this->_tipo = $info[2];
        if ($this->_tipo == IMAGETYPE_JPEG) {
            $this->_imagen = imagecreatefromjpeg($archivo);            
        } elseif ($this->_tipo == IMAGETYPE_PNG) {
            $this->_imagen = imagecreatefrompng($archivo);
        } elseif ($this->_tipo == IMAGETYPE_GIF) {
            $this->_imagen = imagecreatefromgif($archivo);
        } else {
            $this->_msj = 'Tipo de archivo no soportado';
            return FALSE;
        }
        if ($eliminar) {
            unlink($archivo);
        }
        return TRUE;
    }
    
    /**
     * Metodo para cambiar de tamaño las imagenes del servidor
     * @param type $width
     * @param type $height
     */
    public function ajustar($width, $height) {
        $w = imagesx($this->_imagen);
        $h = imagesy($this->_imagen);     
        $nuevo = imagecreatetruecolor($width, $height);
        imagecopyresampled($nuevo, $this->_imagen, 0, 0, 0, 0, $width, $height, $w, $h);
        $this->_imagen = $nuevo;
    }
    
    /**
     * Metodo que guarda las ediciones de las imagenes del servidor y retorna el nombre
     * @param type $carpeta
     * @param type $tipo
     * @param type $calidad
     * @param type $permiso
     * @return string
     */
    public function guardar($carpeta, $tipo = IMAGETYPE_JPEG, $calidad = 75, $permiso = 0777) {
        $nombre = uniqid() . '_' . rand(1,999);
        if ($tipo == IMAGETYPE_JPEG) {
            $nombre = $nombre . '.jpg';
            imagejpeg($this->_imagen, $carpeta . $nombre, $calidad);
        } elseif ($tipo == IMAGETYPE_PNG) {
            $nombre = $nombre . '.png';
            imagepng($this->_imagen, $carpeta . $nombre, $calidad);
        } elseif ($tipo == IMAGETYPE_GIF) {
            $nombre = $nombre . '.gif';
            imagepng($this->_imagen, $carpeta . $nombre, $calidad);
        } else {
            $nombre = $nombre . '.jpg';
            imagejpeg($this->_imagen, $carpeta . $nombre, $calidad);
        }
        chmod($carpeta . $nombre, $permiso);
        $this->_nuevoNombre = $nombre;
    }
    
    /**
     * Metodo que obtiene el mensaje
     * @return type
     */
    public function getMensaje() {
        return $this->_msj;
    }
}