<?php
class forma {
	private $campos = array();
	private $nombre;
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
	
	function __construct($name) {
		$this->nombre = $name;
		// Hay una razón lógica que no conozco para que en la declaración de
		// $atributos NO se puedan poner variables como $_SERVER['PHP_SELF']
		$this->atributos['action'] = $_SERVER['PHP_SELF'];
	}
	
	function inicio() {
		/*
		 * Regresar el código HTML para declarar la forma con sus atributos.
		 */
		$retval = '<form name="'.$this->nombre.'" id="'.$this->nombre.'" ';
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