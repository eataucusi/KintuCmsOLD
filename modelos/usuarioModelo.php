<?php
/**
 * Archivo 'usuarioModelo.php'
 * 
 * Este archivo define la clase 'usuarioModelo'
 * 
 * @license http://creativecommons.org/licenses/by-sa/2.5/pe/ Atribucion-CompartirIgual 2.5 Peru
 * @author Edison Ataucusi R. <ediar89@gmail.com>
 * @version 1.0 28/02/2013 07:36:30 PM
 */
/**
 * Clase 'usuarioModelo'
 * 
 * Este Modelo nos permite interactuar con la tabla usuarios de la bd
 * 
 * @package Modelo
 */
class usuarioModelo extends Modelo {
    public function __construct() {
        parent::__construct();
    }
    
    public function existeLogin($login) {
        return $this->_bd->getScalar('SELECT id FROM usuarios WHERE login = ?', array($login));
    }
    
    public function existeEmail($email) {
        return $this->_bd->getScalar('SELECT id FROM usuarios WHERE email = ?', array($email));
    }
    
    public function insertarUsuario($rol, $login, $nombre, $email, $pas, $codigo) {        
        $this->_bd->ejecutar('INSERT INTO usuarios VALUES(NULL, ?, ?, ?, ?, ?, 1, ?, NULL, now(), ?)', array($rol, $login, $nombre, $email, $pas, 'usuario.jpg', $codigo));
    }
    
    public function getUsuario($id) {
        return $this->_bd->getFila('SELECT u.*, r.rol FROM usuarios AS u, roles AS r WHERE u.role_id = r.id AND u.id = ?', array($id));
    }
    
    public function getUsuarioLogin($login) {
        return $this->_bd->getFila('SELECT * FROM usuarios WHERE login = ?', array($login));
    }
    
    public function getUsuarios($pagina) {
        $pagina = ($pagina - 1) * REG_PAG;
        return $this->_bd->getArray('SELECT u.id, u.login, u.nombre, u.email, u.estado, r.rol FROM usuarios AS u, roles AS r WHERE u.role_id = r.id LIMIT ?, ?', array($pagina, REG_PAG));
    }
    
    public function contarUsuarios() {
        return $this->_bd->getScalar('SELECT COUNT(id) FROM usuarios');
    }
    
    public function editarUsuario($id, $rol, $login, $nombre, $email, $pass, $estado, $foto, $acerca) {
        if ($pass) {
            $this->_bd->ejecutar('UPDATE usuarios SET role_id = ?, login = ?, nombre = ?, email = ?, pass = ?, estado = ?, foto = ?, acerca = ? WHERE id = ?', array($rol, $login, $nombre, $email, $pass, $estado, $foto, $acerca, $id));
        } else {
            $this->_bd->ejecutar('UPDATE usuarios SET role_id = ?, login = ?, nombre = ?, email = ?, estado = ?, foto = ?, acerca = ? WHERE id = ?', array($rol, $login, $nombre, $email, $estado, $foto, $acerca, $id));
        }
    }
    
    public function habilitarUsuario($id) {
        return $this->_bd->ejecutar('UPDATE usuarios SET estado = 2 WHERE id = ?', array($id));
    }
    
    public function deshabilitarUsuario($id) {
        return $this->_bd->ejecutar('UPDATE usuarios SET estado = 1 WHERE id = ?', array($id));
    }
    
    public function getSelect() {
        return $this->_bd->getArray('SELECT id, login FROM usuarios WHERE estado = 2 AND role_id > 1');
    }
    
    public function activarUsuario($id) {
        return $this->_bd->ejecutar('UPDATE usuarios SET estado = 2 WHERE id =  ?', array($id));
    }
    
    public function editarDatos($id, $nombre, $login, $email, $acerca) {
        $this->_bd->ejecutar('UPDATE usuarios SET nombre = ?, login = ?, email = ?, acerca = ? WHERE id = ?', array($nombre, $login, $email, $acerca, $id));
    }
    
    public function cambiarFoto($id, $foto) {
        $this->_bd->ejecutar('UPDATE usuarios SET foto = ? WHERE id = ?', array($foto, $id));
    }
    
    public function cambiarPass($id, $pass) {
        $this->_bd->ejecutar('UPDATE usuarios SET pass = ?, codigo = ? WHERE id = ?', array($pass, time(), $id));
    }
    
    public function loginUsuario($login, $pass) {
        return $this->_bd->getScalar('SELECT id FROM usuarios WHERE login = ? AND pass = ?', array($login, $pass));
    }
    
    public function recuperarUsuario($id, $codigo) {
        $this->_bd->ejecutar('UPDATE usuarios SET codigo = ? WHERE id = ?', array($codigo, $id));
    }
}