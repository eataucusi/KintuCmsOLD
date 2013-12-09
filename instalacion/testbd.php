<?php
define('RAIZ', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
require_once RAIZ . 'class' . DIRECTORY_SEPARATOR . 'Bd.php';
$bd_host = $_POST['servidor_bd'];
$bd_user = $_POST['usuario_bd'];
$bd_pass = $_POST['clave_bd'];
$bd_name = $_POST['bd'];
try {
    $bd = Bd::test($bd_host, $bd_user, $bd_pass, $bd_name);
    ?>
<div class="correcto">
    <?php echo 'Conección OK!'; ?>
</div>
<?php    
} catch (Exception $exc) {
    ?>
<div class="error">
    <?php  echo $exc->getMessage()?>
</div>
    <?php
    }

