<?php
/**
 * Archivo Widget
 * 
 * Este archivo define la clase Widget
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:29:55 AM
 */
/**
 * Clase para el manejo de widgets
 * 
 * Esta clase se encarga de administrar los widgets de la plantilla
 */
abstract class Widget{
    protected function getModelo($modelo){
        $ruta = RAIZ . 'widgets' . SD . 'modelos' . SD . $modelo . '.php';
        if (is_readable($ruta)) {
            include_once $ruta;
            $clase = $modelo . 'ModeloWidget';
            if (class_exists($clase)) {
                return new $clase;
            }
        }
        throw new Exception("Eror, modelo de widget no encontrado");
    }
    /**
     * Metodo para renderizar los Widgtes
     * 
     * @param plantilla $vista
     * @param array asociativo $datos
     * @param extencion de la plantilla $ext
     * @return String
     * @throws Exception Si la vista no fue encontrada
     */
    protected function renderizar($vista, $datos = array(), $ext = 'phtml') {
        $ruta = RAIZ . 'widgets' . SD . 'vistas' . SD . $vista . '.' . $ext;
        if (is_readable($ruta)) {
            ob_start();
            extract($datos);
            include $ruta;
            $contenido = ob_get_contents();
            ob_end_clean();
            return $contenido;
        }
        throw new Exception("Eror, vista de widget no encontrado");
    }
}
