<?php
class forma {
	private $campos = array();
	private $nombre;
	private $attributos = array(
			'accept-charset' => 'utf8',
			'action' => $_SERVER['PHP_SELF'],
			'autocomplete' => 'on',
			'enctype' => 'application/x-www-form-urlencoded',
			'method' => 'POST',
			'novalidate' => 'false',
			'target' => '_self'
	);

	function __construct($name, $campos = array()) {
		if (!is_string($name)) throw new InvalidArgumentException("El nombre de la forma no es String");
		$nombre = $name;
		addField($campos);
	}

	private function defineTipo($data_type) {
		switch ($data_type) {
			case 'bit':
				/*
				 * Validar si es un bit(1) se refiere a un checkbox
				 * De lo contrario se refiere a un número binario
				 */
				if ($length !== null and $length == 1) {
					return "checkbox";
				} else {
					return "text";
				}
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'int':
			case 'integer':
			case 'bigint':
				/*
				 * Esto es un valor entero revisar length.
				 */
				return 'text';
			case 'real':
			case 'double':
			case 'float':
			case 'decimal':
			case 'numeric':
				/*
				 * Valor con decimales length y otro? ¿donde estan los decimales?
				 */
				return 'text';
			case 'date':
			case 'time':
				return $data_type;
			case 'datetime':
			case 'timestamp':
				return 'datetime-local';
			case 'year':
				/*
				 * Fecha y hora...
				 */
				return 'text';
			case 'char':
			case 'varchar':
			case 'binary':
			case 'varbinary':
				/*
				 * Valores de texto ya sea que se almacene como tal o como binary strings
				 */
				return 'text';
			case 'tinyblob':
			case 'blob':
			case 'mediumblob':
			case 'longblob':
				/*
				 * ¿¿¿archivos???
				 * ¿¿imagenes??
				 * ¿texto que nos interesa en binario?
				 */
				return 'file';
			case 'tinytext':
			case 'text':
			case 'mediumtext':
			case 'longtext':
				/*
				 * Mucho texto como string o binario
				 */
				return 'textarea';
			case 'enum':
				return 'select';
			case 'set':
				return 'select-multi';
				/*
				 * opciones!!!
				 * OJO enum es un juego de radios... set es un juego de checkboxes
				 * o opciones multiples para set y una opcion para enum
				 * entonces ¿enum es un multi de una opcion o es un select?
				 */
		}

	}

	public function addField($campos) {
		if (isArray($campos)) {
			/*
			 * Sacar de la BD las características del campo para hacer su <input>
			*/
			foreach ($campos as $tabla => $campo) {
				$sql = 'SELECT '.
						'columns.data_type, '.
						'ifnull(columns.numeric_precision, columns.character_maximum_length) as length, '.
						'columns.numeric_scale as scale, columns.is_nullable as requerido, '.
						'key_column_usage.referenced_table_name, '.
						'key_column_usage.referenced_column_name '.
						'FROM information_schema.columns '.
						'LEFT JOIN information_schema.key_column_usage '.
						'ON key_column_usage.table_name = columns.table_name '.
						'AND key_column_usage.column_name = columns.column_name '.
						'WHERE columns.table_schema = (SELECT DATABASE()) '.
						'AND columns.table_name = \''.$tabla.'\' AND columns.column_name = \''.$campo.'\'';
				$resultado = consulta_sql($sql);
				if ($resultado !== null) {
					if ($resultado['referenced_table_name'] == null) {
						array_push($this->campos,new inputElement($resultado[0]['column_name'],defineTipo($resultado[0]['data_type'],this.nombre)));

					} else {
						// Aqui poner que es un select referenciado!!!
					}
				} else {
					throw new UnexpectedValueException("No se obtuvieron resultados para el campo.\n$sql");
				}
			}
		} else {
			throw new InvalidArgumentException("El argumento no es un arreglo.");
		}
	}

	public function start() {
		$retval = '<form name="'.$nombre.'"';
		foreach($atributos as $att => $valor) {
			$retval .= ' '.$att.'="'.$valor.'"';
		}
		$retval .= '>';
		return $retval;
	}

}

class inputElement {
	private $atributos = array();
	private $type;
	private $name;
	private $forma;

	function __construct($nombre,$tipo,$forma) {
		$name = $nombre;
		$tipo = $type;
		$forma = $form;
	}

	public function setAttributes($nuevosAtributos = null) {
		if (isArray($nuevosAtributos)) {
			foreach ($nuevosAtributos as $key => $value) {
				switch ($key) {
					case 'accept':
						if ($type == 'file') {
							$atributos['accept'] = $value;
						}
						break;
					case 'alt':
						if ($type == 'image') {
							$atributos['alt'] = $value;
						}
						break;
					case 'autocomplete':
						if ($type == 'text' and ($value == 'on' or $value == 'off')) {
							$atributos['autocomplete'] = $value;
						}
						break;
					case 'autofocus':
						switch ($value) {
							case 'true':
							case 'on':
							case true:
							case 1:
							case '1':
								$atributos['autofocus'] = true;
								break;
						}
						break;
					case 'checked':
						if ($type == 'radio' or $type == 'checkbox') {
							switch ($value) {
								case 'true':
								case 'on':
								case true:
								case 1:
								case '1':
									$atributos['checked'] = true;
									break;
							}
					}
						break;
					case 'disabled':
						switch ($value) {
							case 'true':
							case 'on':
							case true:
							case 1:
							case '1':
								$atributos['disabled'] = true;
								break;
						}
						break;
					case 'action':
						if ($type == 'submit') {
							$atributos['action'] = $value;
						}
						break;
				} // aqui termina el switch de los atributos
			}
		} else {
			$atributos = array();
		}
	}

}

?>