<?php

// Constantes para ser modificadas dependiendo de la instalación.
const BD_HOST = 'localhost';
const USUARIO_BD = 'php_test';
const PASSWORD_BD = '0390846c3a90c91224572a69642e0a7c';
const BASE_DATOS = 'php_test';


/* activar excepciones en lugar de errores */
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_ALL;

/*
 * Conectar a base de datos
 */
$mysql = new mysqli(BD_HOST,USUARIO_BD,PASSWORD_BD,BASE_DATOS);

function consulta_sql ($sql) {
	// Funcion para hacer consultas a la base de datos
	global $mysql;
	// Si no tenemos un string para la consulta salimos.
	if (gettype($sql) != 'string') {
		throw new InvalidArgumentException("Error - comando SQL inválido\n$sql.\n");
	}
	fb($sql);
	$query = $mysql->query($sql); // Esto manda excepcion mysqli_sql_exception

	// Revisamos a ver si nos regresó algo

	if (is_object($query) and $query->num_rows > 0) {
		$retval = array();
		while ($resultado = $query->fetch_assoc()) {
			array_push($retval,$resultado);
		}
		return $retval;
	} else {
		// Si no regresó nada la consulta se hizo con éxito pero sin resultados.
		if (is_object($query)) {
			return null;
		} else {
			return true;
		}
	}
}


?>