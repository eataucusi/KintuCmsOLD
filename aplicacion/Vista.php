<?php

/**
 * Archivo 'Vista', vista principal
 * 
 * En este archivo se define la clase 'Vista'
 *  
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:31:40 AM
 */

/**
 * Clase View
 * 
 * Esta clase es la Vista principal, de esta clase no heredara ninguna,
 * a diferncia del controlador principal. Esta clase se encargar de incluir
 * una determinada vista
 */
class Vista {

    private $_controlador;
    private $_css;
    private $_js;
    public $ruta;
    public $plantilla;
    public $rutaPublico;
    private $_widget;
    public $peticion;
    public $titulo;
    public $meta;
    public $url;

    public function __construct(Peticion $_peticion) {
        $this->peticion = $_peticion;
        $this->_controlador = $this->peticion->getControlador();
        $this->url = $this->peticion->url;
        $this->plantilla = PLANTILLA;
        $this->ruta = URL_BASE . 'vistas/_plantillas/' . $this->plantilla . '/';
        $this->rutaPublico = URL_BASE . 'publico/';
        $this->_css = array();
        $this->_js = array();
        $this->_widget = array();
        $this->titulo = TITULO;
        $this->meta = META;
    }

    public function renderizar($vista, $parcial = FALSE) {
        $rutaVista = RAIZ . 'vistas' . SD . $this->_controlador . SD . $vista . '.phtml';

        if (is_readable($rutaVista)) {
            if ($parcial) {
                require_once $rutaVista;
            } else {
                $this->getWidgets();
                ob_start();
                require_once $rutaVista;
                $this->contenido = ob_get_contents();
                ob_end_clean();
                require_once RAIZ . 'vistas' . SD . '_plantillas' . SD . $this->plantilla . SD . 'plantilla.phtml';
            }
        } else {
            throw new Exception('Vista no encontrada');
        }
    }

    public function getPaginacion($actual, $npaginas, $url, $ancla = '') {
        $desde = ($actual - 2 < 1) ? 1 : $actual - 2;
        $hasta = ($desde + 4 > $npaginas) ? $npaginas : $desde + 4;
        $this->paginar = array('desde' => $desde, 'hasta' => $hasta, 'npaginas' => $npaginas, 'actual' => $actual, 'url' => URL_BASE . $url, 'ancla' => $ancla);
    }

    public function setCss($css, $plantilla = false) {
        if ($plantilla) {
            $this->_css[] = $this->ruta . 'css/' . $css . '.css';
        } else {
            $this->_css[] = $this->rutaPublico . 'css/' . $css . '.css';
        }
    }

    public function setJs($js, $plantilla = false) {
        if ($plantilla) {
            $this->_js[] = $this->ruta . 'js/' . $js . '.js';
        } else {
            $this->_js[] = $this->rutaPublico . 'js/' . $js . '.js';
        }
    }

    public function setWidget($posicion, $html) {
        if (array_key_exists($posicion, $this->_widget)) {
            $this->_widget[$posicion] = $this->_widget[$posicion] . $html;
        } else {
            $this->_widget[$posicion] = $html;
        }
    }

    public function getWidget($posicion) {
        if (!array_key_exists($posicion, $this->_widget)) {
            $this->_widget[$posicion] = '';
        }
        if (isset($_GET['tp'])) {
            $this->_widget[$posicion] = '<div class="posicion">[' . $posicion . ']</div>' . $this->_widget[$posicion];
        }
        return $this->_widget[$posicion];
    }

    public function widget($widget, $opciones = array()) {
        if (!is_array($opciones)) {
            $opciones = array($opciones);
        }
        $ruta = RAIZ . 'widgets' . SD . $widget . '.php';
        if (is_readable($ruta)) {
            require_once $ruta;
            $clase = $widget . 'Widget';
            if (!class_exists($clase)) {
                throw new Exception("Eror, no existe widget" . $clase);
            }
            $opciones[] = $this->peticion;
            return call_user_func_array(array(new $clase, 'getHtml'), $opciones);
        } else {
            $ruta = RAIZ . 'widgets' . SD . 'html.php';
            require_once $ruta;
            $clase = 'htmlWidget';
            $opciones[] = $widget;
            $opciones[] = $this->peticion;
            return call_user_func_array(array(new $clase, 'getHtml'), $opciones);
        }
        throw new Exception("Eror, ruta widget no encontrado" .$ruta);
    }

    private function getWidgets() {
        $datos = Pagina::getPosiciones($this->plantilla);
        foreach ($datos as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $widget = $value[$i]['nombre'];
                $this->setWidget($key, $this->widget($widget));
            }
        }
    }

    public function setPlnatilla($plantilla) {
        $this->plantilla = $plantilla;
    }

}
