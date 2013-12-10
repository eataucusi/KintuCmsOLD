DROP TABLE IF EXISTS `slider` ;---
DROP TABLE IF EXISTS `menus` ;---
DROP TABLE IF EXISTS `comentarios` ;---
DROP TABLE IF EXISTS `control` ;---
DROP TABLE IF EXISTS `archivos` ;---
DROP TABLE IF EXISTS `asignacion` ;---
DROP TABLE IF EXISTS `posicion` ;---
DROP TABLE IF EXISTS `widget` ;---
DROP TABLE IF EXISTS `plantilla` ;---
DROP TABLE IF EXISTS `articulos` ;---
DROP TABLE IF EXISTS `usuarios` ;---
DROP TABLE IF EXISTS `roles` ;---
DROP TABLE IF EXISTS `categorias` ;---

CREATE TABLE IF NOT EXISTS `archivos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usua_id` int(10) unsigned NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `subido` date NOT NULL DEFAULT '1000-01-01',
  `descarga` int(10) unsigned NOT NULL DEFAULT '0',
  `descripcion` varchar(100) NOT NULL,
  `para` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `fk_archivos_usuarios1` (`usua_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;---

INSERT INTO `archivos` (`id`, `usua_id`, `nombre`, `subido`, `descarga`, `descripcion`, `para`) VALUES
(1, 2, '52a1e784886f9_728.jpg', '2013-12-06', 0, 'Logo kintucms jpg', 'articulos'),
(2, 2, '52a20389df382_433.jpg', '2013-12-06', 0, 'arquitectura de kintucms', 'articulos'),
(3, 2, '52a205d703a74_318.png', '2013-12-06', 0, 'Logo kintucms png', 'articulos'),
(4, 2, '52a2105becbac_311.jpg', '2013-12-06', 0, 'UNIVERSIDAD NACIONAL JOSE MARIA ARGUEDAS', 'articulos') ;---

CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usua_id` int(10) unsigned NOT NULL,
  `cate_id` smallint(5) unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `resumen` text NOT NULL,
  `cuerpo` mediumtext NOT NULL,
  `meta` varchar(250) NOT NULL DEFAULT 'metadadto',
  `estado` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `leido` int(5) unsigned NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `modificado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `fk_posts_usuarios1_idx` (`usua_id`),
  KEY `fk_posts_categorias1_idx` (`cate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;---

INSERT INTO `articulos` (`id`, `usua_id`, `cate_id`, `nombre`, `titulo`, `resumen`, `cuerpo`, `meta`, `estado`, `leido`, `creado`, `modificado`) VALUES
(1, 2, 2, 'introduccion-a-kintucms', 'Introducción a KintuCms', '<p><img src="http://localhost/kintucms/publico/img/articulos/52a1e784886f9_728.jpg" alt="Logo kintucms jpg" /></p>\r\n<h2>&iquest;Qu&eacute; es KintuCms</h2>\r\n<p>Es un sistema de gesti&oacute;n de contenidos (en ingl&eacute;s Content Management System, o CMS) b&aacute;sico y liviano que permite desarrollar sitios web din&aacute;micos e interactivos.&nbsp;</p>\r\n<p>Permite crear, modificar o eliminar contenido de un sitio web de manera sencilla a trav&eacute;s de un panel de Administraci&oacute;n.&nbsp;</p>\r\n<p>Es un software de c&oacute;digo abierto, desarrollado en PHP y liberado bajo licencia &ldquo;Atribucion-CompartirIgual 2.5 Peru&rdquo;.&nbsp;</p>\r\n<p>KintuCms puede utilizarse en una PC local (en Localhost), en Internet y requiere para su funcionamiento una base de datos creada con un gestor MySQL, as&iacute; como de un servidor HTTP.</p>\r\n<p>Su nombre hace referencia a la palabra quechua &ldquo;kintu&rdquo;, que significa hoja escogida de la coca. Se escogi&oacute; por el consumo de la hoja de coca por el desarrollador mientras se desarrollaba &nbsp;KintuCms.</p>', '<h2>&iquest;Qu&eacute; es un Sistema de Gesti&oacute;n de Contenidos</h2>\n<p>Un Sistema de Gesti&oacute;n de Contenidos es un software que te permite crear y administrar p&aacute;ginas web f&aacute;cilmente separando la creaci&oacute;n de su contenido de la mec&aacute;nica necesaria para presentarlo en la web.</p>\n<p>En este sitio, el contenido se almacena en una base de datos. El aspecto y la sensaci&oacute;n son creados por una plantilla. El CMS re&uacute;ne la plantilla y el contenido para crear p&aacute;ginas web.</p>\n<h2>Caracter&iacute;sticas de KintuCms</h2>\n<ul>\n<li>Generaci&oacute;n de c&oacute;digo HTML bien formado</li>\n<li>Gesti&oacute;n de blogs</li>\n<li>Gesti&oacute;n de noticias</li>\n<li>Gesti&oacute;n de usuarios</li>\n<li>Gesti&oacute;n de plantillas&nbsp;</li>\n</ul>\n<h2>Historia del proyecto KintuCms</h2>\n<p>KintuCms surge de la necesidad de tener un CMS b&aacute;sico, liviano, que cualquier persona con o sin conocimientos de PHP lo pueda utilizar y que no necesite mucha inversi&oacute;n econ&oacute;mica para tener y mantener un sitio web.</p>\n<p>El objetivo principal de KintuCms es dar soluci&oacute;n a las necesidad &nbsp;de tener presencia en internet de las MYPE&rsquo;s y PYMES.&nbsp;</p>\n<h2>Arquitectura de KintuCms</h2>\n<p>Esta desarrollado en una arquitectura MVC lo que permite un gran nivel de personalizaci&oacute;n en el desarrollo de los plantillas (Vista), logrando una transformaci&oacute;n total de un sitio con tan solo cambiar la plantilla.</p>\n<p style="text-align: center;"><img src="http://localhost/kintucms/publico/img/articulos/52a20389df382_433.jpg" alt="arquitectura de kintucms" /></p>\n<h2>Sitio y Administrador de KintuCms</h2>\n<p>El KintuCms tiene actualmente dos sitios separados:</p>\n<ol>\n<li>El sitio de la parte delantera (tambi&eacute;n llamado (Frontend) es lo que los visitantes ver&aacute;n de su sitio.</li>\n<li>El administrador (tambi&eacute;n llamado el backend) es utilizado por personas que gestionan el sitio.</li>\n</ol>\n<h2>Reslatador de c&oacute;digo KintuCms</h2>\n<pre>&lt;?php   \n /**\n     * Metodo que ejecuta una consulta en MySQL\n     * \n     * @param string $sql consulta SQL\n     * @param array $parametros parametros para reemplazar en la consulta SQL\n     */\n    private function _query($sql, $parametros) {\n        $this-&gt;_result = $this-&gt;_mysqli-&gt;query($this-&gt;_prepare($sql, $parametros));\n        \n        if ($this-&gt;_mysqli-&gt;error) {\n            $msg = ''&lt;h3&gt;ERROR DE MYSQL&lt;/h3&gt;&lt;h4&gt;CODIGO: '' . $this-&gt;_mysqli-&gt;errno;\n            $msg = $msg . ''&lt;br&gt;DESCRIPCION: '';\n            if (isset($this-&gt;_error[$this-&gt;_mysqli-&gt;errno])) {\n                $msg = $msg . ''Error de sintaxis SQL, '' . $this-&gt;_error[$this-&gt;_mysqli-&gt;errno];\n            } else {\n                $msg = $msg .  ''Busque el codido en el manual de MySQL'';\n            }\n            $msg = $msg . ''&lt;/h4&gt;'';\n            if (Sesion::get(''nivel'') == 4) {\n                $msg = $msg . $this-&gt;_sql;\n            }\n            throw new Exception($msg);\n        }\n    }\n?&gt;</pre>', 'Introducción a KintuCms, Qué es KintuCms, Características de KintuCms, Historia del proyecto KintuCms, Arquitectura de KintuCms', 2, 0, '2013-12-06 12:06:12', '2013-12-09 19:34:56') ;---

CREATE TABLE IF NOT EXISTS `asignacion` (
  `posi_id` int(11) NOT NULL,
  `widg_id` int(11) NOT NULL,
  PRIMARY KEY (`posi_id`,`widg_id`),
  KEY `fk_posi_widget_posicion1` (`posi_id`),
  KEY `fk_posi_widget_widget1` (`widg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;---

INSERT INTO `asignacion` (`posi_id`, `widg_id`) VALUES
(1, 5),
(2, 2),
(3, 1),
(4, 5),
(5, 4),
(6, 1),
(7, 2),
(8, 3),
(9, 6),
(9, 7),
(9, 9),
(10, 8),
(11, 10) ;---

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL DEFAULT 'categoria',
  `detalle` varchar(200) NOT NULL DEFAULT 'detalle',
  `img` varchar(30) DEFAULT NULL,
  `padre` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `fk_categorias_1_idx` (`padre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;---

INSERT INTO `categorias` (`id`, `nombre`, `categoria`, `detalle`, `img`, `padre`) VALUES
(1, 'sin-categoria', 'Sin categoría', 'En esta categoría se publicará temas diferentes categorías.', '5151d274646ed_328.jpg', NULL),
(2, 'principal', 'Principal', 'En esta categoría se publicará artículos para la página principal.', '529c9e8f4a2d0_962.jpg', NULL) ;---

CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `arti_id` int(10) unsigned NOT NULL,
  `usua_id` int(10) unsigned NOT NULL,
  `comentario` text NOT NULL,
  `creado` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_comentarios_posts1_idx` (`arti_id`),
  KEY `fk_comentarios_usuarios1_idx` (`usua_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;---

CREATE TABLE IF NOT EXISTS `control` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parametro` varchar(30) DEFAULT NULL,
  `valor` varchar(255) DEFAULT NULL,
  `detalle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parametro_UNIQUE` (`parametro`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;---

INSERT INTO `control` (`id`, `parametro`, `valor`, `detalle`) VALUES
(1, 'dominio', 'KintuCsm.org', 'Tu dominio (por ejemplo: www.kintucms.org)'),
(2, 'titulo', 'Kintucms un simple cms', 'Título de tu sitio'),
(3, 'meta', 'Meta etiquetas', 'Descripción de tu sitio (20 palabras)'),
(4, 'paginacion', '10', 'Número de filas por página'),
(5, 'sesion', '60', 'Tiempo de expiración de sesión (en minutos)'),
(6, 'correoMail', 'waylis.soft@gmail.com', 'Correo en Gmail para el envío de Mails.'),
(7, 'claveMail', 'eUDu?Ow!,W0Py-zn', 'Contraseña del correo para el envío de Mails con Gmail'),
(8, 'inicio', 'articulo/ver/introduccion-a-kintucms/1', 'Página de inicio'),
(9, 'hash', '50f889dace692', 'Hash único para genera contraseñas') ;---

CREATE TABLE IF NOT EXISTS `menus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(3) unsigned NOT NULL,
  `texto` varchar(60) NOT NULL,
  `url` varchar(100) NOT NULL,
  `detalle` varchar(150) DEFAULT 'descripcion',
  `class` varchar(45) DEFAULT NULL,
  `padre` smallint(5) unsigned DEFAULT NULL,
  `ventana` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menus_roles1_idx` (`role_id`),
  KEY `fk_menu_1_idx` (`padre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;---

INSERT INTO `menus` (`id`, `role_id`, `texto`, `url`, `detalle`, `class`, `padre`, `ventana`) VALUES
(1, 4, 'Articulos', 'articulo', 'El módulo artículo le permite la administración de artículos.', NULL, NULL, NULL),
(2, 4, 'Categorías', 'categoria', 'El módulo categoría le permite la administración de las categorías.', NULL, NULL, NULL),
(3, 4, 'Archivos', 'archivo', 'El módulo archivo le permite la administración archivos subidos.', NULL, NULL, NULL),
(4, 4, 'Menús', 'menu', 'El módulo menú le permite la administración del menú principal (ubicada en la parte superior derecha de la página).', NULL, NULL, NULL),
(5, 4, 'Widgets', 'widget', 'El módulo widget le permite la administración de widgets. Un widget es una porción de contenido que se muestra en las posiciones de tu plantilla.', NULL, NULL, NULL),
(6, 4, 'Plantillas', 'plantilla', 'El módulo plantilla le permite la administración de plantillas.', NULL, NULL, NULL),
(7, 4, 'Sliders', 'slider', 'El módulo Slider nos permite gestionar el slider de la página.', NULL, NULL, NULL),
(8, 4, 'Configuración', 'config', 'El módulo config le permite la administración a configuración del sitio.', NULL, NULL, NULL),
(9, 4, 'Usuarios', 'usuario', 'El módulo usuario le permite la administración de los usuarios.', NULL, NULL, NULL),
(10, 4, 'Roles', 'rol', 'El módulo rol le permite la administración de los roles (permisos de usuario).', NULL, NULL, NULL),
(11, 4, 'Nuevo Menú', 'menu/nuevo', 'Esta opción le permite agregar un nuevo menú.', NULL, 4, NULL),
(12, 4, 'Listar Menús', 'menu/listar', 'Esta opción le permite listar los menús.', NULL, 4, NULL),
(13, 4, 'Registrados', 'menu/listar/2', 'Esta opción le permite listar los menús de usuarios registrados y no registrados.', NULL, 12, NULL),
(14, 4, 'Publicadores', 'menu/listar/3', 'Esta opción le permite listar los menús de usuarios publicadores.', NULL, 12, NULL),
(15, 4, 'Administradores', 'menu/listar/4', 'Esta opción le permite listar los menús de usuarios Administradores.', NULL, 12, NULL),
(16, 4, 'Nuevo Artículo', 'articulo/nuevo', 'Esta opción le permite crear un nuevo artículo.', NULL, 1, NULL),
(17, 4, 'Listar Artículos', 'articulo/listar', 'Esta opción le permite listar los artículos.', NULL, 1, NULL),
(18, 4, 'Nueva Categoría', 'categoria/nuevo', 'Esta opción le permite crear una nueva categoría.', NULL, 2, NULL),
(19, 4, 'Listar Categorías', 'categoria/listar', 'Esta opción le permite listar las ctegorías.', NULL, 2, NULL),
(20, 4, 'Nuevo Archivo', 'archivo/nuevo', 'Esta opción le permite subir un nuevo archivo.', NULL, 3, NULL),
(21, 4, 'Listar Archivos', 'archivo/listar', 'Esta opción le permite listar los archivos subidos.', NULL, 3, NULL),
(22, 4, 'Nuevo Widget', 'widget/nuevo', 'Esta opción le permite agregar un nuevo widget.', NULL, 5, NULL),
(23, 4, 'Listar Widgets', 'widget/listar', 'Esta opción le permite listar los widgets.', NULL, 5, NULL),
(24, 4, 'Instalar plantilla', 'plantilla/nuevo', 'Esta opción le permite instalar una nueva plantilla.', NULL, 6, NULL),
(25, 4, 'Gestor de plantillas', 'plantilla/listar', 'Esta opción le permite gestionar las plantillas instaladas.', NULL, 6, NULL),
(26, 4, 'Agregar Imagen', 'slider/nuevo', 'Esta opción le permite agregar imágenes al slider.', NULL, 7, NULL),
(27, 4, 'Listar Imágenes', 'slider/listar', 'Esta opción le permite listar las imágenes del slider.', NULL, 7, NULL),
(28, 4, 'Agregar Usuario', 'usuario/nuevo', 'Esta opción le permite agregar usuarios al sitio.', NULL, 9, NULL),
(29, 4, 'Listar Usuarios', 'usuario/listar', 'Esta opción le permite listar los usuarios del sitio.', NULL, 9, NULL),
(30, 2, 'Enlace de ejemplo', 'articulo/ver/introduccion-a-kintucms/1', 'Enlace al artículo de introducción de kintu', NULL, NULL, NULL),
(31, 3, 'Artículos', '#', 'El módulo artículo le permite la administración de artículos.', NULL, NULL, NULL),
(32, 3, 'Nuevo Artículo', 'articulo/nuevo', 'Esta opción le permite crear un nuevo artículo.', NULL, 31, NULL),
(33, 3, 'Listar Artículos', 'articulo/listar', 'Esta opción le permite listar los artículos.', NULL, 31, NULL),
(34, 3, 'Archivos', '#', 'El módulo archivo le permite la administración archivos subidos.', NULL, NULL, NULL),
(35, 3, 'Nuevo Archivo', 'archivo/nuevo', 'Esta opción le permite subir un nuevo archivo.', NULL, 34, NULL),
(36, 3, 'Listar Archivos', 'archivo/listar', 'Esta opción le permite listar los archivos subidos.', NULL, 34, NULL) ;---

CREATE TABLE IF NOT EXISTS `plantilla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `para` varchar(20) DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `autor` varchar(60) DEFAULT NULL,
  `emailAutor` varchar(45) DEFAULT NULL,
  `urlAutor` varchar(45) DEFAULT NULL,
  `detalle` varchar(250) DEFAULT NULL,
  `img` varchar(250) DEFAULT NULL,
  `defecto` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;---

INSERT INTO `plantilla` (`id`, `nombre`, `para`, `creado`, `autor`, `emailAutor`, `urlAutor`, `detalle`, `img`, `defecto`) VALUES
(1, 'kintuadmin', 'Administrador', '2013-12-01 00:00:00', 'Ediar', 'ediar89@gmail.com', 'www.kintucms.org', 'Plantilla por defecto para publicadores y administradores de KintuCms', NULL, 2),
(2, 'kintusitio', 'Sitio', '2013-12-01 00:00:00', 'Edison Ataucusi', 'ediar89@gmail.com', 'http://kintucms.org/', 'Plantilla para sitio de kintucms', 'img', 2) ;---

CREATE TABLE IF NOT EXISTS `posicion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_posicion_plantilla1` (`plan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;---

INSERT INTO `posicion` (`id`, `plan_id`, `nombre`) VALUES
(1, 1, 'usuario_admin'),
(2, 1, 'navegacion_admin'),
(3, 1, 'menu_admin'),
(4, 2, 'usuario'),
(5, 2, 'social'),
(6, 2, 'menu'),
(7, 2, 'navegacion'),
(8, 2, 'slider'),
(9, 2, 'derecha'),
(10, 2, 'footer'),
(11, 2, 'derechos')
(12, 2, 'top-script')
(13, 2, 'bot-script') ;---

CREATE TABLE IF NOT EXISTS `roles` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(30) NOT NULL DEFAULT 'nombre',
  `detalle` varchar(255) NOT NULL DEFAULT 'detalle',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;---

INSERT INTO `roles` (`id`, `rol`, `detalle`) VALUES
(1, 'Visitante', 'Usuarios no registrados.'),
(2, 'Registrado', 'Este rol tiene los permisos de comentar, modificar su perfil y ver artículos solo para registrados.'),
(3, 'Publicador', 'Este rol tiene los permisos para publicar artículos.'),
(4, 'Administrador', 'Este rol tiene el control total de la aplicación.') ;---

CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200) NOT NULL,
  `ruta` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;---

INSERT INTO `slider` (`id`, `descripcion`, `ruta`) VALUES
(1, 'Logo KintuCms', '52a208910ba6c_482.jpg'),
(2, 'Segundo logo de kintu', '52a20c5351f6e_846.jpg') ;---

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(3) unsigned NOT NULL,
  `login` varchar(30) NOT NULL DEFAULT 'login',
  `nombre` varchar(60) NOT NULL DEFAULT 'nombre y apellido' COMMENT 'nombres y apellidos del usuario',
  `email` varchar(60) NOT NULL DEFAULT 'email',
  `pass` char(32) NOT NULL DEFAULT 'password',
  `estado` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `foto` varchar(30) DEFAULT NULL,
  `acerca` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT 'fecha de registro',
  `codigo` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_UNIQUE` (`login`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_usuarios_rol_idx` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;---

INSERT INTO `usuarios` (`id`, `role_id`, `login`, `nombre`, `email`, `pass`, `estado`, `foto`, `acerca`, `fecha`, `codigo`) VALUES
(1, 3, 'anonimo', 'usuario anónimoh', 'anonimo@anonimo.com', '0083e216d0308eb1d187850f261031e4', 2, 'usuario.jpg', 'Este usuario fue creado por la aplicación con la finalidad de que este usuario publique artículos de manera anónima.', '2013-03-26 09:44:25', 1323714095),
(2, 4, 'administrador', 'administrador de la aplicación', 'ediar8@gmail.com', '0083e216d0308eb1d187850f261031e4', 2, 'usuario.jpg', 'Hola soy el administrador de la aplicación', '1000-01-01 00:00:00', 1385854600) ;---

CREATE TABLE IF NOT EXISTS `widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `titulo` varchar(45) DEFAULT NULL,
  `cuerpo` mediumtext,
  `visible` tinyint(4) DEFAULT '1',
  `peso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;---

INSERT INTO `widget` (`id`, `nombre`, `titulo`, `cuerpo`, `visible`, `peso`) VALUES
(1, 'menu', 'Menu principal', NULL, 2, 1),
(2, 'navegacion', 'Menu navegacion', NULL, 2, 1),
(3, 'slider', 'Slider', NULL, 2, 1),
(4, 'social', NULL, '<ul>\r\n<li><span style="color: #800000;"><a class="facebook" title="S&iacute;gueme en facebook" href="http://www.facebook.com/eataucusi" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-facebook.png" alt="img" /></span></a></span></li>\r\n<li><span style="color: #800000;"><a class="twitter" title="S&iacute;gueme en twitter" href="https://twitter.com/encoti" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-twitter.png" alt="img" /></span></a></span></li>\r\n<li><span style="color: #800000;"><a class="google" title="S&iacute;gueme en google+" href="https://plus.google.com/102260440795158860355" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-google.png" alt="img" /></span></a></span></li>\r\n<li><span style="color: #800000;"><a class="linkedin" title="S&iacute;gueme en linkedin" href="#" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-linkedin.png" alt="img" /></span></a></span></li>\r\n<li><span style="color: #800000;"><a class="youtube" title="S&iacute;gueme en youtube" href="http://www.youtube.com/channel/UC8X78jDBI_Xw47jmWsiWpPQ" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-youtube.png" alt="img" /></span></a></span></li>\r\n<li><span style="color: #800000;"><a class="vimeo" title="S&iacute;gueme en vimeo" href="#" target="_blank"><span style="color: #800000;"><img src="http://localhost/kintucms/publico/img/widget/s-vimeo.png" alt="img" /></span></a></span></li>\r\n</ul>', 2, 1),
(5, 'usuario', 'Menu de usuario', NULL, 2, 1),
(6, 'eventos', 'Próximos Eventos', '<p>Ummm eventos</p>', 2, 1),
(7, 'descargakintu', 'Descarga KintuCms', '<p>Descarga la &uacute;ltima versi&oacute;n de KintuCms en Github, link de descarga <a href="https://github.com/ediar89/kintucms">www.github.com/ediar89/kintucms</a>.</p>', 2, 2),
(8, 'servicios', 'Servicios Kintu', '<ul>\r\n<li>Creaci&oacute;n de p&aacute;ginas web a medida</li>\r\n<li>Creaci&oacute;n de tiendas virtuales</li>\r\n<li>Desarrollo de M&oacute;dulos para frameworks PHP</li>\r\n</ul>', 2, 1),
(9, 'mi-universidad', 'Mi universidad', '<p style="text-align: center;">UNIVERSIDAD NACIONAL JOSE MARIA ARGUEDAS</p>\r\n<p style="text-align: center;"><a title="Unajma" href="http://www.unajma.edu.pe/" target="_blank"><img src="http://localhost/kintucms/publico/img/articulos/52a2105becbac_311.jpg" alt="UNIVERSIDAD NACIONAL JOSE MARIA ARGUEDAS" /></a></p>\r\n<p>&nbsp;</p>', 2, 2),
(10, 'copyrigth', NULL, '<p>Copyright &copy; 2013.<span style="color: #ffffff;"> KintuCms</span> | Funciona con <a href="http://kintucms.org/">KintuCms</a></p>', 2, 1) ;---

ALTER TABLE `archivos`
  ADD CONSTRAINT `fk_archivos_usuarios1` FOREIGN KEY (`usua_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ;---

ALTER TABLE `articulos`
  ADD CONSTRAINT `fk_posts_categorias1` FOREIGN KEY (`cate_id`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_posts_usuarios1` FOREIGN KEY (`usua_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;---

ALTER TABLE `asignacion`
  ADD CONSTRAINT `fk_posi_widget_posicion1` FOREIGN KEY (`posi_id`) REFERENCES `posicion` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_posi_widget_widget1` FOREIGN KEY (`widg_id`) REFERENCES `widget` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ;---

ALTER TABLE `categorias`
  ADD CONSTRAINT `fk_categorias_1` FOREIGN KEY (`padre`) REFERENCES `categorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;---

ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_comentarios_posts1` FOREIGN KEY (`arti_id`) REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comentarios_usuarios1` FOREIGN KEY (`usua_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ;---

ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_menu_1` FOREIGN KEY (`padre`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ;---

ALTER TABLE `posicion`
  ADD CONSTRAINT `fk_posicion_plantilla1` FOREIGN KEY (`plan_id`) REFERENCES `plantilla` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION ;---

ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;---