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
			'novalidate' => 'false',
			'target' => '_self'
	);
	
	public function __construct($name) {
		$nombre = $name;
		// Hay una razón lógica que no conozco para que en la declaración de
		// $atributos NO se puedan poner variables como $_SERVER['PHP_SELF']
		$this->atributos['action'] = $_SERVER['PHP_SELF'];
	}
}
?>