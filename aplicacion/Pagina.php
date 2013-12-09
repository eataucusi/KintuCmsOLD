<?php
/**
 * Archivo Pagina
 * 
 * Este archivo define la clase Pagina
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 27/01/2013 12:29:55 AM
 */
/**
 * Clase para el manejo de las Configuraciones de la Pagina
 * 
 * Esta clase se encarga de obtener configuraciones de la pagina 
 */
class Pagina{
    public static function generar() {
        $_bd = Bd::getIntancia();
        
        
        /**Tiempo de expireración de la sesion (en minutos)*/
        define('TIEMPO_SESION', (int)$_bd->getScalar('SELECT valor FROM control WHERE parametro = "sesion"')); 
        
        Sesion::iniciar();
        
        /**
         * Configuracion de la pagina
         */       
        if (isset($_GET['admin'])) {
            define('ES_ADMIN', TRUE);
            define('ADMIN', '?admin');
        } else {
            define('ES_ADMIN', FALSE);
            define('ADMIN', '');
        }
        /**plantilla*/       
        if (Sesion::accesoVista('Publicador') && ES_ADMIN) {
            define('PLANTILLA', $_bd->getScalar('SELECT nombre FROM plantilla WHERE para = "Administrador" AND defecto = 2'));
        } else {
            define('PLANTILLA', $_bd->getScalar("SELECT nombre FROM plantilla WHERE para = 'Sitio' AND defecto = 2"));
        }       
        /**Nombre de la aplicacion*/
        define('TITULO', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "titulo"'));
        
        /**Descripcion de la aplicacion*/
        define('META', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "meta"'));
        
        /**dominio de la aplicacion*/
        define('DOMINIO', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "dominio"')); 
        
        /**página de inicio de la aplicacion*/
        define('INICIO', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "inicio"')); 
        
        /**Numero de registros por pagina para la paginacion*/
        define('REG_PAG', (int)$_bd->getScalar('SELECT valor FROM control WHERE parametro = "paginacion"'));        
        
        /**String unico para generar los password*/
        define('HASH_KEY', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "hash"'));
        
        /**
         * Configuracion para envio de Mail con gmail
         */
        /**Email con la que enviara los correos*/
        define('MAIL_USER', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "correoMail"'));

        /**Password del email*/
        define('MAIL_PASS', $_bd->getScalar('SELECT valor FROM control WHERE parametro = "claveMail"'));
    }
    
    public static function getPosiciones($plantilla) {
        $_bd = Bd::getIntancia();
        $ver = array();
        $posiciones = $_bd->getArray('SELECT posicion.id, posicion.nombre FROM posicion, plantilla WHERE plantilla.nombre = '."'". $plantilla ."'".' AND plantilla.id = posicion.plan_id');

        for ($i = 0; $i < count($posiciones); $i++){
            $widgtes = $_bd->getArray('SELECT wid.nombre FROM widget AS wid, asignacion AS pow WHERE pow.posi_id = ? AND wid.visible = 2 AND wid.id = pow.widg_id ORDER BY wid.peso ASC', array($posiciones[$i]['id']));
            if (is_array($widgtes)) { 
                $ver[$posiciones[$i]['nombre']] = $widgtes;
            }            
        }
        return $ver;
    }
}