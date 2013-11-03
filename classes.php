<?php
class objetoHTML {
	protected $id;
	protected $nombre;
	
	function __construct($name,$iden = null) {
		$this->nombre = $name;
		$this->id = $name;
		if ($iden !== null) {
			$this->id = $iden;
		}
		/*
		 * Hay que checar que no haya otro objeto con un tag igual y mismo nombre
		 * Tambien que no haya otro objeto con este id
		 * Tal vez crear un arreglo de objetos en un objecto {document}???
		 */
	}
	
}

class forma extends objetoHTML {
	private $campos = array();
	private $atributos = array(
			'accept-charset' => 'utf8',
			// Ver comentario en el constructor respecto del valor de action
			'action' => '',
			'autocomplete' => 'on',
			'enctype' => 'application/x-www-form-urlencoded',
			'method' => 'POST',
			'novalidate' => false,
			'target' => '_self'
	);

	function __construct($name,$id = null) {
		parent::__construct($name,$id);
		// Hay una razón lógica que no conozco para que en la declaración de
		// $atributos NO se puedan poner variables como $_SERVER['PHP_SELF']
		$this->atributos['action'] = $_SERVER['PHP_SELF'];
		
	}
	
	function inicio() {
		/*
		 * Regresar el código HTML para declarar la forma con sus atributos.
		 */
		$retval = '<form name="'.$this->nombre.'" id="'.$this->id.'" ';
		foreach($this->atributos as $att => $valor) {
			if (is_bool($valor)) {
				$retval .= $att.' ';
			} else {
				$retval .= $att.'="'.$valor.'" ';
			}
		}
		$retval = substr($retval,0,-1);
		$retval .= '>';
		return $retval;
	}
	
	function fin() {
		/*
		 * Codigo HTML para cerrar la forma.
		 */
		return '</form>';
	}
}


?>