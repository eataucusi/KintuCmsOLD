<?php

/**
 * Archivo 'indexControlador'
 * 
 * Este archivo define la clase 'indexControlador'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 31/01/2013 09:54:49 AM
 */

/**
 * Clase 'indexControlador'
 * 
 * Esta clase extiende de la clase 'Controlador'
 * 
 * @package Controlador
 */
class indexControlador extends Controlador {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if (Sesion::accesoVista('Publicador') && ADMIN != '') {
            $this->admin();
        }
        $fc = curl_init();
        curl_setopt($fc, CURLOPT_URL, URL_BASE . INICIO . '/777');
        curl_setopt($fc, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($fc, CURLOPT_HEADER, 0);
        curl_setopt($fc, CURLOPT_VERBOSE, 0);
        curl_setopt($fc, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($fc, CURLOPT_TIMEOUT, 30);
        $res = curl_exec($fc);
        curl_close($fc);
        $this->_vista->datos = $res;
        $this->_vista->renderizar('index');
    }

    public function admin() {
        /*         * @var articuloModelo */
        $articulo = $this->getModelo('articulo');
        $articulos = $articulo->getMasLeidos();

        $comentario = $this->getModelo('comentario');
        $comentarios = $comentario->ultimosComentarios();
        if ($comentarios) {
            for ($i = 0; $i < count($comentarios); $i++) {
                $comentarios[$i]['fecha'] = $this->_fechaTexto($comentarios[$i]['creado']);
            }
        }
        $this->_vista->articulos = $articulos;
        $this->_vista->comentarios = $comentarios;
        $this->_vista->titulo = 'Últimas actividades en ' . DOMINIO;
        $this->_vista->renderizar('admin');
    }
}
