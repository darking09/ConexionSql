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
	private $user="root";//usuario
	private $servidor="localhost";//nombre del servidor
	private $clave="mafer09";//contraseña del usuario
	private $base="csi";//base de datos
	private $dbd;//manejador de la base de datos
	private $sql;//cadena de consulta
	private $manejador;//manejador de la consulta
	/**
         *
         * @var ConexionMysql
         */
	public static $_singleton;//variable estatica para realizar llamados fuera de la clase

        /**
         * Funcion que revisa si la conexion existe si no la crea
         * @return ConexionMysql conexion estatica
         */
	public static function getInstance()
	{
		if (is_null (self::$_singleton))
		{
			self::$_singleton = new ConexionMysql();
		}
		return self::$_singleton;
	}
	
        /**
         * Construcctor
         */
	function __construct()
	{
		$this->conecta();
	}

        /**
         * Destructor
         */
	function __destruct()
	{
            if($this->dbd)
		mysql_close($this->dbd);
	}

        /**
         * Funcion que conecta a la base de datos
         * @throws Exception cualquier problema con la conexion
         */
	protected function conecta()
	{
            
            $this->dbd=@mysql_pconnect($this->servidor,$this->user,$this->clave);

            if(!$this->dbd)			
                throw new Exception("No existe la Conexion con Mysql: ".mysql_errno()." - ".mysql_error());				
            elseif(mysql_select_db($this->base,$this->dbd)==false)            
                 throw new Exception("No Existe la Conexion con la Base de Datos ".mysql_error());		
	}
	
	/**
         * Funcion que comprueba si existe o no una conexion
         * @return boolean retorna si ha y o no una conexion
         */	
	public function isConexion(){
		if($this->dbd)
			return true;
		return false;
	}
	
        /**
         * 
         * @param string $string
         * @throws Exception
         *
         */
	public function query($from, $data = array('*'),  $where = "", $order = array("var" => "id", "dir" => "ASC"), $limit = '')
	{
            $string = '';
            
            foreach ($data as $d)
                $string .= $d.', ';
            
            $string = substr($string, 0, -2);

            $orderBy = "Order By ".$order['var']." ".$order['dir'];
            
            $this->sql = "Select ".$string." From ".$from." ".$orderBy." ".$limit;
            
            
            $this->manejador=mysql_query($this->sql, $this->dbd);
            
            if(!$this->manejador)
                throw new Exception ($this->error (), $this->codigoError ());
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
		echo mysql_error();
	}
	
	public function codigoError(){
		return mysql_errno();
	}
	

}

?>
	
