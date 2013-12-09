<?php
/**
 * Archivo Bd(Base de datos)
 * 
 * Este archivo define la clase Bd
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:29:55 AM
 */
/**
 * Clase para las conexiones a base de datos
 * 
 * Esta clase se encarga de administrar la conexion a mysql, usa el patron singleton
 */
class Bd {
    private static $_instancia;
    private $_parametros;
    private $_error;
    
    /**@var mysqli*/
    private $_mysqli;
    /**@var mysql_result*/
    private $_result;
    
    private $_sql;


    private function __construct($bd_host, $bd_user, $bd_pass, $bd_name) {
        $this->_error = array(
            '2002' => 'No se pudo conectar con el servidor MySQL, compruebe la constante "DB_HOST"',
            '2005' => 'No se pudo conectar con el servidor MySQL, compruebe la constante "DB_HOST"',
            '1045' => 'Acceso denegado, compruebe la constante "DB_USER" y "DB_PASS"',
            '1049' => 'La base de datos no existe, compruebe la constante "DB_NAME"',
            '1064' => 'compruebe su consulta',
            '1146' => 'tabla no existente',
            '1054' => 'columna no existente',
            '1136' => 'No hay correspondencia en numero de columnas'
        );
        $this->_mysqli = new mysqli($bd_host, $bd_user, $bd_pass, $bd_name);
        
        if ($this->_mysqli->connect_errno) {
            throw new Exception('<h3>ERROR DE MySQL</h3><h4>CODIGO: ' . $this->_mysqli->connect_errno . 
                    '<br>DESCRIPCION: ' . $this->_error[$this->_mysqli->connect_errno] .
                    ' en el archivo "Configuracion.php"</h4>');
        }
        $this->_mysqli->set_charset('utf8');
    }
    
    /**
     * Metodo que comprueba una coneccion a MySQL
     * 
     * @param type $bd_host
     * @param type $bd_user
     * @param type $bd_pass
     * @param type $bd_name
     * @return \self
     */
    public static function test($bd_host, $bd_user, $bd_pass, $bd_name) {
        return new self($bd_host, $bd_user, $bd_pass, $bd_name);
    }


    /**
     * Metodo que ejecuta una consulta en MySQL
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar en la consulta SQL
     */
    private function _query($sql, $parametros) {
        $this->_result = $this->_mysqli->query($this->_prepare($sql, $parametros));
        
        if ($this->_mysqli->error) {
            $msg = '<h3>ERROR DE MYSQL</h3><h4>CODIGO: ' . $this->_mysqli->errno;
            $msg = $msg . '<br>DESCRIPCION: ';
            if (isset($this->_error[$this->_mysqli->errno])) {
                $msg = $msg . 'Error de sintaxis SQL, ' . $this->_error[$this->_mysqli->errno];
            } else {
                $msg = $msg .  'Busque el codido en el manual de MySQL';
            }
            $msg = $msg . '</h4>';
            if (Sesion::get('nivel') == 4) {
                $msg = $msg . $this->_sql;
            }
            throw new Exception($msg);
        }
    }
    
    /**
     * Metodo que limpia todos los caracteres que tienen significado especial para MySQL
     * 
     * @param variable $var variable a escapar
     * @return string
     */
    private function _escape($var) {
        return $this->_mysqli->real_escape_string($var);
    }
    
    /**
     * Metodo que obtine el resultado de consulta escalar(un solo campo o valor)
     * 
     * @param mysqli_result $result identificador de una consulta
     * @return string
     */
    private function _fetchRow() {
        $row = $this->_result->fetch_array(MYSQLI_NUM);
        $this->_result->free();
        return $row[0];
    }
    
    /**
     * Metodo que obtiene el resultado de una consulta(varios campos)
     * 
     * @param mysqli_result $result identificador de consulta
     * @return array
     */
    private function _fetchArray() {
        $rows = array();
        while ($row = $this->_result->fetch_array(MYSQLI_ASSOC)) {
            $rows[] = $row;
        }
        $this->_result->free();
        return $rows;
    }

    /**
     * Metodo crea una instancia de esta clase
     * 
     * @return Bd
     */
    public static function getIntancia() {
        if (!self::$_instancia) {
            self::$_instancia = new self(BD_HOST, BD_USER, BD_PASS, BD_NAME);
        }
        return self::$_instancia;
    }
    
    /**
     * Metodo que sera utilizado en el metodo prepare
     */
    private function _reemplazar() {
        $actual = current($this->_parametros);
        next($this->_parametros);
        return $actual;
    }
    
    /**
     * Metodo que retorna una cadena escapeada y lista para ejecutar
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar en la consulta SQL
     * @return string
     */
    private function _prepare($sql, $parametros) {
        for ($i = 0; $i < count($parametros); $i++) {
            if (is_bool($parametros[$i])) {
                $parametros[$i] = $parametros[$i] ? 1: 0;
            } elseif (is_double($parametros[$i])) {
                $parametros[$i] = str_replace(',', '.', $parametros[$i]);
            } elseif (is_numeric($parametros[$i])) {
                $parametros[$i] = $this->_escape($parametros[$i]);
            } elseif (is_null($parametros[$i])) {
                $parametros[$i] = 'NULL';
            } else {
                $parametros[$i] = "'" . $this->_escape($parametros[$i]) . "'";
            }
        }        
        $this->_parametros = $parametros;
        $sql = preg_replace_callback("/(\?)/i", array($this, "_reemplazar"), $sql);
        $this->_sql = $sql;
        return $sql;
    }   
    
    /**
     * Metodo que retorna un valor escalar de la consulta
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar cada ? en la consulta SQL
     * @return type
     */
    public function getScalar($sql, $parametros =  array()) {
        $this->_query($sql, $parametros);
        $row = $this->_fetchRow();
        if ($row) {
            return $row;
        }
        return FALSE;
    }
    
    /**
     * Metodo que retorna una fila del array asociativo del resultado de la consulta
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar cada ? en la consulta SQL
     * @return type
     */
    public function getFila($sql, $parametros =  array()) {
        $this->_query($sql, $parametros);
        $all = $this->_fetchArray();
        if ($all) {
            return $all[0];
        }
        return FALSE;
    }
    
    /**
     * Metodo que retorna un array asociativo del resultado de la consulta
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar cada ? en la consulta SQL
     * @return type
     */
    public function getArray($sql, $parametros =  array()) {
        $this->_query($sql, $parametros);
         $all = $this->_fetchArray();
        if ($all) {
            return $all;
        }
        return FALSE;
    }
    
    /**
     * Metodo que ejecuta una consulta(insert, update)
     * 
     * @param string $sql consulta SQL
     * @param array $parametros parametros para reemplazar cada ? en la consulta SQL
     * @return type
     */
    public function ejecutar($sql, $parametros =  array()) {
        $this->_query($sql, $parametros);
    }
}