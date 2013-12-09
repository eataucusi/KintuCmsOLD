<?php
/**
 * Archivo principal de la aplicacion
 * 
 * En este archivo se declaran constantes para incluir todos los archivos de la
 * carpeta 'aplicacion', por favor no modifique este archivo si no esta capacitado
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 04/01/2013 09:35:54 PM
 */
/**
 * Separador de directorio, constante que almacena el separador de directorio puede ser '/' o '\'
 */
define('SD', DIRECTORY_SEPARATOR);

/**
 * Ruta base, constante que almacena la ruta raiz de la aplicacion, es decir 
 * el directorio donde esta el archivo index.php
 */
define('RAIZ', realpath(dirname(__FILE__)) . SD);

/**
 * Ruta base, constante que almacena la ruta raiz de la aplicacion para la vistas, es decir 
 * el directorio donde esta el archivo index.php
 */
define('URL_BASE', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/');

if (!is_readable(RAIZ . 'aplicacion' . SD . 'Configuracion.php')) {
    header('location:' . URL_BASE . 'instalacion');
    exit(0);
}

try {
    /**
     * Inclusion del archivo 'Configuracion'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Configuracion.php';

    /**
     * Inclusion del archivo 'Peticion'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Peticion.php';

    /**
     * Inclusion del archivo 'Lanzador'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Lanzador.php';

    /**
     * Inclusion del archivo 'Controlador'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Controlador.php';

    /**
     * Inclusion del archivo 'Modelo'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Modelo.php';

    /**
     * Inclusion del archivo 'Vista'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Vista.php';

    /**
     * Inclusion del archivo 'Bd'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Bd.php';

    /**
     * Inclusion del archivo 'Sesion'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Sesion.php';

    /**
     * Inclusion del archivo 'Widget'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Widget.php';

    /**
     * Inclusion del archivo 'Pagina'
     */
    require_once RAIZ . 'aplicacion' . SD . 'Pagina.php';

    Pagina::generar();

    Lanzador::ejecutar(new Peticion);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>