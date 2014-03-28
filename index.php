<?php
include_once 'ConexionMysql.php';

try{
    ConexionMysql::getInstance();
?>

<?php
if(ConexionMysql::$_singleton->isConexion()):?>
<h1>Si Hay Conexion</h1>
<?php else: ?>
<h1>No Hay Conexion</h1>
<?php endif; 
}catch (Exception $e){
    die ("Error ".$e->getCode()."en la linea ".$e->getLine()." : ".$e->getMessage()."<br/>");
}?>

