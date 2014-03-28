<?php
/**
* CLASE PARA REALIZAR UNA CONEXION CON MYSQL DESDE PHP                      
*                                                                                      
* @author FERNANDO JOSE TORRES BERMUDEZ                                                 
* @version 1.0                                                                        
* @copyright (c) 2012, @darkingsoft                                                     
*                                                                                     
**/
class ConexionMysql
{
	private $user="";//usuario
	private $servidor="";//nombre del servidor
	private $clave="";//contraseña del usuario
	private $base="";//base de datos
	private $dbd;//manejador de la base de datos
	private $sql;//cadena de consulta
	private $manejador;//manejador de la consulta
	public static $_singleton;//variable estatica para realizar llamados fuera de la clase

/** EVITA QUE SE REALICE MAS DE UNA CONEXION**/

	public static function getInstance()
	{
		if (is_null (self::$_singleton))
		{
			self::$_singleton = new ConexionMysql();
		}
		return self::$_singleton;
	}
	
/** CONSTRUCTOR DE LA CLASE CONEXION**/	

	function __construct()
	{
		$this->conecta();
	}
	
	function cerrar()
	{
		mysql_close($this->dbd);
	}
	
/**FUNCION DESTRUCTORA DE LA CLASE CONEXION**/

	function __destruct()
	{
		if($this->dbd)
		mysql_close($this->dbd);
	}

/**FUNCION PARA REALIZAR CONEXIONES A LA BASE DE DATOS**/

	protected function conecta()
	{
		try
		{
			$this->dbd=@mysql_pconnect($this->servidor,$this->user,$this->clave);
	
			if(!$this->dbd)
			{
				die("No existe la Conexion con Mysql: ".mysql_errno()." - ".mysql_error()."<br>");
				
			}elseif(mysql_select_db($this->base,$this->dbd)==false)
			{
				die("No Existe la Conexion con la Base de Datos".mysql_error()."<br>");
			}
			
		}catch(Exception $e)
		{
			die("Error ".$e->getCode()."en la linea ".$e->getLine()." : ".$e->getMessage()."<br/>");
		}
		
	}
	
/**FUNCION PARA COMPROBAR SI HAY O NO CONEXION CON LA BASE DE DATOS**/
		
	public function isConexion(){
		if($this->dbd)
			return true;
		return false;
	}
	
/**FUNCION PARA REALIZAR QUERYS O CONSULTAS A LA BASE DE DATOS**/

	public function consulta($string)
	{
		try
		{
			$this->sql=$string;
			$this->manejador=mysql_query($this->sql, $this->dbd);

		}catch(Exception $e)
		{
			echo"Error ".$e->getCode()."en la linea ".$e->getLine()." : ".$e->getMessage()."<br/>";
		}
	}
	
/**FUNCION PARA EXTRAER LOS DATOS DE LAS CONSULTAS**/

	public function datosArray()
	{
		return mysql_fetch_array($this->manejador);
	}
	
/**FUNCION QUE RETORNA EL ID DEL VALOR INSERTADO**/

	public function idInsertado()
	{
		return mysql_insert_id($this->dbd);
	}
	
/**FUNCION QUE RETORNA EL MANEJADOR DE LA BASE DE DATOS**/
	
	public function getDbd()
	{
		return $this->dbd;
	}
	
/**FUNCION QUE CAMBIA EL MANEJADOR DE LA BASE DA DATOS**/

	public function setDbd($dbd)
	{
		$this->dbd=$dbd;
	}
	
	/**FUNCION QUE RETORNA EL MANEJADOR DE LA CONSULTA**/
	
	public function getManejador()
	{
		return $this->manejador;
	}
	
	/**FUNCION QUE CAMBIA EL MANEJADOR DE LA CONSULTA**/
	
	public function setManejador($manejador)
	{
		$this->manejador=$manejador;
	}
/**FUNCION QUE DEVUELVE EL TOTAL DE FILAS ENCONTRADAS EN LA CONSULTA**/

	public function counter()
	{
		return mysql_num_rows($this->manejador);
	}
	
/**FUNCION QUE CONSULTA TODOS LOS ID DE LA TABLA MENSAJE**/

	public function consultarIds($tabla)
	{
		$id=array();
		
		try
		{
			$this->consulta("Select id From $tabla");
			$total = $this->counter();
			for( $i=0; $i<$total; $i++)
			{
				$valor=$this->datosArray();
				$id[]=$valor['id'];
			}
		}catch(Exception $e)
		{
			echo"Error ".$e->getCode()."en la linea ".$e->getLine()." : ".$e->getMessage()."<br/>";
		}
		
		return $id;
	}
	
	
	public function error(){
		echo "<br>".mysql_error()."<br>";
	}
	
	public function codigoError(){
		return mysql_errno();
	}
	

}

?>
	
