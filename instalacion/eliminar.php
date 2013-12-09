<?php

function remdir($carpeta) {
    if (is_dir($carpeta) && !is_link($carpeta)) {
        if ($dh = opendir($carpeta)) {
            while (($sf = readdir($dh)) !== false) {
                if ($sf == '.' || $sf == '..') {
                    continue;
                }
                if (!remdir($carpeta . DIRECTORY_SEPARATOR . $sf)) {
                    throw new Exception($carpeta . '/' . $sf . ' no se pudo eliminar');
                }
            }
            closedir($dh);
        }
        return rmdir($carpeta);
    }
    return unlink($carpeta);
}

define('RAIZ', realpath(dirname(__FILE__)));
try {
    remdir(RAIZ);
    ?>
<div class="correcto">
    <p>La carpeta Instalación se ha eliminado correctamente.</p>
    <p><a href="../">Ya puede dirigirse a la ruta base de tu sitio</a></p>
</div>
<?php
} catch (Exception $ex) {
    ?>
<div class="error">
    <p>La carpeta Instalación no se ha eliminado correctamente.</p>
    <p>Elimine la acrpeta instalacion manualmente.</p>
</div>
<?php
}

