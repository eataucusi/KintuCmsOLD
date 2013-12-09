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

//    public function index() {
//        if ($this->getInt('guardar') == 1) {
//            $this->_vista->datos = $_POST;
//            if (!$this->getTexto('nombre')) {
//                $this->_vista->error[] = 'Nombre completo: utiliza 15 caracteres como minimo.';
//            }
//            if (!$this->getEmail('email')) {
//                $this->_vista->error[] = 'Email: utiliza un email válido para responderte.';
//            }
//            if (!$this->getTexto('mensaje')) {
//                $this->_vista->error[] = 'Mensaje: este campo no puede ser vacío.';
//            }
//            if (!isset($this->_vista->error)) {
//                $this->getLibreria('mail' . SD . 'class.phpmailer');
//                $mail = new PHPMailer();
//                $mail->IsSMTP();
//                $mail->SMTPAuth = TRUE;
//                $mail->SMTPSecure = 'ssl';
//                $mail->Host = "smtp.gmail.com";
//                $mail->Port = 465;
//                $mail->Username = MAIL_USER;
//                $mail->Password = MAIL_PASS; 
//                $mail->FromName = $this->getTexto('nombre');
//                $mail->Subject = 'Comentario en la pagina principal';
//                $mail->Body = '<p><strong>' . $this->getTexto('nombre') . ' ( ' . $this->getEmail('email') . ' ) ' . '</strong>, ha comentado en tu pagina principal, el comentario es: </p><p>' . 
//                        $this->getTexto('mensaje') . '</p>';
//                $mail->AltBody = 'Su servidor de correo no soporta html';
//                $mail->AddAddress(MAIL_USER, $this->getTexto('nombre'));
//                $mail->Send();                
//                Sesion::set('msj', 'Su mensaje fue enviado, en el transcurso de estos días te responderé.');
//                $this->redireccionar();
//            }
//        }    
//        $this->_vista->titulo = 'KintuCms un simple cms';
//        $this->_vista->getPaginacion(2, 10, 'url');
//        $this->_vista->renderizar('index');
//    }
}
