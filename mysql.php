<?php
/***********************************************************************************************************/
/* mysql_conectar_bbdd ()                                                                                  */
/* Funcion que abre una conexión con un servidor de bases de datos en el host $host y con la clave $clave. */
/*                                                                                                         */
/*   link: Valor devuelto por la funcion y que vincula con la conexión al servidor de bases de datos que   */ 
/*             hemos abierto, o cero encaso de fallo.                                                      */
/***********************************************************************************************************/
function mysql_conectar_bbdd () {
  $host = "mysql5-21";
  $usuario = "losconquporra";
  $clave = "alap0rra";
  $base_datos = "losconquporra";
  $link = mysql_connect ($host, $usuario, $clave);
  mysql_select_db ($base_datos, $link);
  return $link;
}


/***********************************************************************************************************/
/* mysql_cerrar_bbdd ($link)                                                                               */
/* Funcion cierra una conexión con un servidor de bases de datos abierta con anterioridad.                 */
/*   link: Identificativo de la conexión con el servidor de bases de datos que queremos cerrar.            */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function mysql_cerrar_bbdd ($link) {
  mysql_close ($link);
}


/***********************************************************************************************************/
/* mysql_obtener_grupos ()                                                                                 */
/* Funcion que obtiene los grupos que forman las eliminatorias.                                            */
/*                                                                                                         */
/*   datos: Grupos que forman las eliminatorias.                                                           */
/***********************************************************************************************************/
function mysql_obtener_grupos () {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select * from grupos;");

  $i=0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
	  $datos [$i] = $linea ['nombre_grupo'];
    $i++;
  }

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/***********************************************************************************************************/
/* mysql_equipos_en_un_grupo ($grupo)                                                                      */
/* Funcion cierra una conexión con un servidor de bases de datos abierta con anterioridad.                 */
/*   grupo: Grupo del que queremos obtener los equipos que lo componen, así como los datos de cada uno.    */
/*                                                                                                         */
/*   datos: Datos de los equipos que pertenecen al grupo consultado.                                       */
/***********************************************************************************************************/
function mysql_equipos_en_un_grupo ($grupo) {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select * from equipo where grupo='$grupo';");

  $i=0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
	  $datos [$i] = $linea;
    $i++;
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/***********************************************************************************************************/
/* mysql_partidos_primera_fase_grupo ($grupo)                                                              */
/* Funcion cierra una conexión con un servidor de bases de datos abierta con anterioridad.                 */
/*   grupo: Grupo del que queremos obtener los equipos que lo componen, así como los datos de cada uno.    */
/*                                                                                                         */
/*   datos: Datos de los equipos que pertenecen al grupo consultado.                                       */
/***********************************************************************************************************/
function mysql_partidos_primera_fase_grupo ($grupo) {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select * from partidos_1fase where grupo='$grupo' order by partidos_1fase_id;");

  $i=0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
	  $datos [$i] = $linea;
    $i++;
  }

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/***********************************************************************************************************/
/* mysql_datos_de_equipo ($equipo)                                                                         */
/* Funcion cierra una conexión con un servidor de bases de datos abierta con anterioridad.                 */
/*   grupo: Grupo del que queremos obtener los equipos que lo componen, así como los datos de cada uno.    */
/*                                                                                                         */
/*   datos: Datos de los equipos que pertenecen al grupo consultado.                                       */
/***********************************************************************************************************/
function mysql_datos_de_equipo ($equipo_id) {
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query ("select * from equipo where equipo_id='$equipo_id';");
	$datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/***********************************************************************************************************/
/* mysql_actualiza_puestos_grupos ($clave, $grupo)                                                         */
/* Funcion que asigna el puesto de cada equipo dentro de su grupo.                                         */
/*   clave: Hash de la porra que se está generando.                                                        */
/*   grupo: Grupo del que queremos obtener los equipos que lo componen, así como los datos de cada uno.    */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function actualiza_puestos_grupos ($clave, $grupo) {
  $conexion = mysql_conectar_bbdd ();

	$puesto = 1;
//	$consulta = mysql_query("select * from participante_puestos_grupos where clave='$clave' and grupo='$grupo' order by puntos desc;");
	$consulta = mysql_query("select * from participante_puestos_grupos where clave='$clave' and grupo='$grupo' order by puntos desc, puesto asc;");
	while ($equipo = mysql_fetch_object($consulta)) {
		mysql_query("update participante_puestos_grupos set puesto=$puesto where id_equipo=$equipo->id_equipo and clave='$equipo->clave';");
		$puesto++;
	}
  mysql_cerrar_bbdd ($conexion);
}

/***********************************************************************************************************/
/* mysql_puestos_grupo ($grupo, $clave)                                                                    */
/* Funcion que devuelve los equipos de un grupo, ordenados según su puesto, de primero a último.           */
/*   clave: Hash de la porra que se está generando.                                                        */
/*   grupo: Grupo del que queremos obtener los equipos que lo componen, así como los datos de cada uno.    */
/*                                                                                                         */
/*   datos: Datos del puesto y puntos de un equipo dentro de su grupo.                                     */
/***********************************************************************************************************/
function mysql_puestos_grupo ($grupo, $clave) {
  $conexion = mysql_conectar_bbdd ();

/* CAMBIO1: ESTO HA SIDO UN ERROR... DEBE SER EL SEGUNDO SELECT. SI PONEMOS EL PRIMERO, A LA HORA DE ORDENAR LOS EQUIPOS EN EL GRUPO, EN CASO DE EMPATE DE PUNTOS, COJE EL QUE MENOR ID TENGA, AUNQUE LUEGO LOS OCTABOS LOS HAGA BIEN. ESTO SUPONE UN CAMBIO "CAMBIO2" EN apuestas.php */
  $consulta = mysql_query ("select * from participante_puestos_grupos where (grupo='$grupo' and clave='$clave') order by puesto;");
//  $consulta = mysql_query ("select * from apuesta_grupos where (grupo='$grupo' and clave='$clave') order by puesto;");
  $i=0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
	  $datos [$i] = $linea;
    $i++;
  }
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/***********************************************************************************************************/
/* mysql_dime_equipo ($clave, $grupo, $puesto)                                                             */
/* Funcion que devuelve el ID del equipo que está en un puesto determinado en su grupo, para una porra.    */
/*   clave: Hash de la porra en la que queremos buscar.                                                    */
/*   grupo: Grupo el el que está el equipo del que queremos saber su ID.                                   */
/*   puesto: Puesto en el que está el equipo del que queremos saber su ID.                                 */
/*                                                                                                         */
/*   datos ['equipo']: ID del equipo que estábamos buscando.                                               */
/***********************************************************************************************************/
function mysql_dime_equipo ($clave, $grupo, $puesto, $tabla='apuesta_grupos_temp') {
  $conexion = mysql_conectar_bbdd ();

// al utilizarlo para realizar las apuestas, hay que hacer que tire de apuesta_grupos_temp. Cuando la apuesta esta correcta para las consultas definitivas hay que quitarle el temp. Lo suyo seria crear otra funcion 
  if ($tabla == 'apuesta_grupos_temp')
	  $consulta = mysql_query ("select * from apuesta_grupos_temp where clave='$clave' and grupo='$grupo' and puesto='$puesto';");

	else if ($tabla == 'apuesta_grupos')
	  $consulta = mysql_query ("select * from apuesta_grupos where clave='$clave' and grupo='$grupo' and puesto='$puesto';");
	                            
  $datos = mysql_fetch_array ($consulta);

  mysql_cerrar_bbdd ($conexion);

	return $datos ['equipo'];
}


/***********************************************************************************************************/
/* mysql_dime_ganador ($clave, $fase)                                                                      */
/* Funcion que devuelve el ID del equipo que ha ganado una determinada fase.                               */
/*   clave: Hash de la porra en la que queremos buscar.                                                    */
/*   fase: Fase en la que queremos buscar un ganador. Los valores para esta variable son:                  */
/*        'c1' a 'c8': El ganador de cada uno de los cruces de Octavos de Final.                           */
/*        's1' a 's4': El ganador de cada uno de los cruces de Cuartos de Final.                           */
/*        'f1' a 'f2': El ganador de cada uno de los cruces de Semifinales.                                */
/*                   : El ganador de la Final.                                                             */
/*                                                                                                         */
/*   datos ['equipo']: ID del equipo que estábamos buscando.                                               */
/***********************************************************************************************************/
function mysql_dime_ganador ($clave, $fase) {
  $conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query("select * from apuesta_fase_final_temp where clave='$clave' and partido='$fase';");
	$datos = mysql_fetch_array ($consulta);

  mysql_cerrar_bbdd ($conexion);

	return $datos ['equipo'];
}

/**********/
function mysql_inserta_apostante ($nick, $nombre_apostante, $nombre_tde, $extension, $mail, $clave) {
  $conexion = mysql_conectar_bbdd ();

/********************** Controlamos los errores de $nick **********************/
	if ($nick == '') {
		return "Debes introducir un nick.";
	}

/***************** Controlamos los errores de $nombre_apostante ***************/
	else if ($nombre_apostante == '') {
		return "Debes introducir el nombre del apostante.";
	}

/******************* [JL] SE SUPRIME AL PONER COMO OPCIONAL  Controlamos los errores de $nombre_tde *******************/
  //else if ($_GET['nombre_tde'] == '') {
  //  return "Debes introducir el nombre del empleado de TdE.";
  //}

/******************** Controlamos los errores de $extension *******************/
  else if (strlen($extension)!=9 || $extension == '') {
    return "Telefono no V&aacute;lido. Debe contener nueve d&iacute;gitos.";
  }

/********************** Controlamos los errores de $mail **********************/
	if ($mail == '') {
		return "Debes introducir el mail del apostante";
	}

  else if (strlen($mail) < 5 || strpos ($mail, '@') < 1) {
    return "Debes introducir correctamente el mail del apostante.";
  }




	$esta = mysql_esta_en_tabla ($clave, participante_temp);
	if ($esta == 0) {
		mysql_query ("insert into participante_temp (nick,nombre,nombre_tde,extension,mail, clave) values('$nick','$nombre_apostante','$nombre_tde','$extension','$mail','$clave');");

	}
	else {
		mysql_query ("update participante_temp set nick='$nick', nombre='$nombre_apostante', nombre_tde='$nombre_tde', extension='$extension', mail='$mail' where clave='$clave'");
	}

  return 0;

  mysql_cerrar_bbdd ($conexion);
}


/***********************************************************************************************************/
/* mysql_inserta_1fase ($resultado1, $resultado2, $resultado3, $resultado4, $resultado5, $resultado6,      */
/*                       $grupo, $clave)                                                                   */
/* Funcion que inserta en la tabla de apuestas temporal la apuesta de la primera fase (Quiniela 1, X, 2).  */
/*   resultado1 a resultado6: Resultados de cada uno de los partidos del grupo, en formato quiniela.       */
/*   grupo: Grupo al que pertenecen los resultados pasados como parámetro.                                 */
/*   clave: Hash de la porra en la que queremos buscar.                                                    */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function mysql_inserta_1fase ($resultado1, $resultado2, $resultado3, $resultado4, $resultado5, $resultado6, $grupo, $clave) {

  $PARTIDOS_POR_GRUPO = 6;

  // Asignamos el ID de partido inicial para cada grupo, en base al número de partidos por grupo. 
  $A = 1;
  $B = $A + $PARTIDOS_POR_GRUPO;
  $C = $B + $PARTIDOS_POR_GRUPO;
  $D = $C + $PARTIDOS_POR_GRUPO;
  $E = $D + $PARTIDOS_POR_GRUPO;
  $F = $E + $PARTIDOS_POR_GRUPO;
  $G = $F + $PARTIDOS_POR_GRUPO;
  $H = $G + $PARTIDOS_POR_GRUPO; 	

  $conexion = mysql_conectar_bbdd ();

  $partidos = array ($resultado1, $resultado2, $resultado3, $resultado4, $resultado5, $resultado6);
  
  // De esta forma damos a $i el ID inicial del grupo para el que queremos dar los valores.
  $i = $$grupo;

  $esta = mysql_esta_en_tabla ($clave, 'apuesta_1fase_temp', $$grupo);
	if ($esta == 0) { 
    foreach ($partidos as $partido) {
      mysql_query("insert into apuesta_1fase_temp (partido, resultado, clave) values ($i, '$partido', '$clave');");
      $i++;
    }
	  mysql_inserta_puntos_equipo ($clave, $grupo, 'INSERTAR');
  }  
  
  else {
    foreach ($partidos as $partido) {
      mysql_query ("update apuesta_1fase_temp set resultado='$partido' where clave='$clave' and partido=$i;");
      $i++;
    }
	  mysql_inserta_puntos_equipo ($clave, $grupo, 'ACTUALIZAR');
  }

  mysql_cerrar_bbdd ($conexion);
}


/***********************************************************************************************************/
/* mysql_esta_en_tabla ($clave, $tabla, $partido)                                                          */
/* Funcion que busca en una tabla una apuesta con una clave determinada. Si pasamos un número de partido   */
/*     (para la fase de grupos), buscará ese partido en concreto.                                          */
/*   clave: Hash de la porra en la que queremos buscar.                                                    */
/*   tabla: Tabla de la base de datos en la que queremos hacer la búsqueda.                                */
/*   partido: ID del partido de la fase de grupos que queremos buscar.                                     */
/*                                                                                                         */
/*   esta: Indica si la busqueda realizada ha devuelto algún valor o no.                                   */
/*        FALSE: La búsqueda realizada no ha devuelto ningún valor, por tanto no está en la tabla.         */
/*        TRUE: La búsqueda realizada ha devuelto un valor, con lo que, lo buscado, está en la tabla.      */
/***********************************************************************************************************/
function mysql_esta_en_tabla ($clave, $tabla, $partido = 0) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

	if ($partido == 0)
	  $consulta = mysql_query ("select distinct(clave) from $tabla where clave='$clave';");

	else
	  $consulta = mysql_query ("select distinct(clave) from $tabla where clave='$clave' and partido=$partido;");

	$datos = @mysql_fetch_array ($consulta);

	if ($datos[0] != '')
	  $esta = 1;

	else
	  $esta = 0;
  //mysql_cerrar_bbdd ($conexion);
  return $esta;
}

//***** ¿Se puede poner en buscar en tabla? *****//
function mysql_esta_en_tabla_grupo ($clave, $grupo, $puesto) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select distinct(clave) from apuesta_grupos_temp where clave='$clave' and grupo='$grupo' and puesto='$puesto';");
	$datos = @mysql_fetch_array ($consulta);

	if ($datos[0] != '')
	  $esta = 1;

	else
	  $esta = 0;
  //mysql_cerrar_bbdd ($conexion);
  return $esta;
}


//***** ¿Se puede poner en buscar en tabla? *****//
function mysql_esta_en_tabla_fasefinal ($clave, $partido) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select distinct(clave) from apuesta_fase_final_temp where clave='$clave' and partido='$partido';");

	$datos = @mysql_fetch_array ($consulta);

	if ($datos[0] != '')
	  $esta = 2;

	else
	  $esta = 0;
  //mysql_cerrar_bbdd ($conexion);
  return $esta;
}


//***** ¿Se puede poner en buscar en tabla? *****//
function mysql_esta_en_tabla_final ($clave) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select distinct(clave) from apuesta_final_temp where clave='$clave';");

	$datos = @mysql_fetch_array ($consulta);

	if ($datos[0] != '')
	  $esta = 1;

	else
	  $esta = 0;
  //mysql_cerrar_bbdd ($conexion);
  return $esta;
}


//***** ¿Se puede poner en buscar en tabla? *****//
function mysql_existe_porra ($clave) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select clave from participante where clave='$clave';");

	$datos = @mysql_fetch_array ($consulta);

	if ($datos[0] != '')
	  $existe = 1;

	else
	  $existe = 0;
	  
  //mysql_cerrar_bbdd ($conexion);
  return $existe;
}

/***********************************************************************************************************/
/* mysql_inserta_puntos_equipo ($clave, $grupo, $accion)                                                   */
/* Funcion que inserta los puntos de la fase de grupos en cada uno de los equipos de un grupo.             */
/*   clave: Hash de la porra en la que queremos actualizar.                                                */
/*   grupo: Grupo para el que queremos actualizar los puntos de cada equipo.                               */
/*   accion: Acción a realizar:                                                                            */
/*        'INSERTAR':   Inserta los puntos del equipo.                                                     */
/*        'ACTUALIZAR': Actualiza los puntos del equipo.                                                   */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function mysql_inserta_puntos_equipo ($clave, $grupo, $accion) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

		  $consulta = mysql_query ("select * from equipo where grupo='$grupo';");

		  while ($datos = mysql_fetch_object ($consulta)) {
			  $puntos_equipo = mysql_dime_puntos_equipo ($clave, $datos->equipo_id, $grupo);

			  if ($accion == 'INSERTAR')
			  	mysql_query ("insert into participante_puestos_grupos (clave, id_equipo, grupo, puntos) values('$clave', $datos->equipo_id, '$grupo', $puntos_equipo);");

				if ($accion == 'ACTUALIZAR')
					mysql_query("update participante_puestos_grupos set puntos=$puntos_equipo where clave='$clave' and id_equipo=$datos->equipo_id;");
		  }
  //mysql_cerrar_bbdd ($conexion);
}


/***********************************************************************************************************/
/* mysql_dime_puntos_equipo ($clave, $equipo, $grupo)                                                      */
/* Funcion que devuelve los puntos de un equipo en la fase de grupos.                                      */
/*   clave: Hash de la porra en la que queremos actuar.                                                    */
/*   equipo: ID del equipo del que queremos obtener los puntos en la fase de grupos.                       */
/*   grupo: Grupo al que pertenece el equipo del que queremos obtener los puntos.                          */
/*                                                                                                         */
/*   puntos: Puntos que ha obtenido el equipo en la fase de grupos.                                        */
/***********************************************************************************************************/
function mysql_dime_puntos_equipo ($clave, $equipo, $grupo) {
  // No inicia conexión en la Base de Datos. Hay que iniciarla previamente.
  //$conexion = mysql_conectar_bbdd ();

  $puntos = 0;

  // Partidos como Local
  // Vemos los partidos en los que ha jugado el equipo como local, y sumamos los puntos
  $consulta_local = mysql_query ("select * from partidos_1fase where grupo='$grupo' and equipo1=$equipo;");

  while ($partidos_local = mysql_fetch_object ($consulta_local)) {
  	$consulta_resultado = mysql_query("select * from apuesta_1fase_temp where clave='$clave' and partido=$partidos_local->partidos_1fase_id;");

  	while ($resultados_local = mysql_fetch_object ($consulta_resultado)) {
		  if ($resultados_local->resultado == '1') 
		  	$puntos = $puntos + 3;

		  if ($resultados_local->resultado == 'X')
		  	$puntos = $puntos + 1;
		}
  }

  // Partidos como Visitante
  // Vemos los partidos en los que ha jugado el equipo como visitante, y sumamos los puntos
  $consulta_visitante = mysql_query ("select * from partidos_1fase where grupo='$grupo' and equipo2=$equipo;");

  while ($partido_visitante = mysql_fetch_object ($consulta_visitante)) {
  	$consulta_resultado = mysql_query ("select * from apuesta_1fase_temp where clave='$clave' and partido=$partido_visitante->partidos_1fase_id;");
  	while ($resultado_visitante = mysql_fetch_object ($consulta_resultado)) {
		  if ($resultado_visitante->resultado == '2')
		  	$puntos = $puntos + 3;

		  if ($resultado_visitante->resultado == 'X')
		  	$puntos = $puntos + 1;
		}
  }
  //mysql_cerrar_bbdd ($conexion);

	return $puntos;
}


/***********************************************************************************************************/
/* mysql_inserta_grupos ($clave, $puesto1, $puesto2, $puesto3, $puesto4, $grupo)                           */
/* Funcion que inserta, en la tabla temporal de la base de datos, el orden de los equipos para cada grupo. */
/*   clave: Hash de la porra en la que queremos actuar.                                                    */
/*   puesto1 a puesto4: ID del equipo según su orden en el grupo para la fase de grupos.                   */
/*   grupo: Grupo al que pertenece el equipo del que queremos guardar el orden de clasificación.           */
/*                                                                                                         */
/*   error: Devuelve un error si se intenta repetir un mismo equipo en dos puestos del grupo.              */
/*        '0': No existe ningun equipo repetido y se han insertado los grupos de forma correcta.           */
/*        '1': Al menos un equipo está repetido en dos puestos distintos dentro del mismo grupo.           */
/***********************************************************************************************************/
function mysql_inserta_grupos ($clave, $puesto1, $puesto2, $puesto3, $puesto4, $grupo) {
  $equipos = array ($puesto1, $puesto2, $puesto3, $puesto4);
  $puesto = 1;

  $conexion = mysql_conectar_bbdd ();

	if ($puesto1 == $puesto2 || $puesto1 == $puesto3 || $puesto1 == $puesto4 || $puesto2 == $puesto3 || $puesto2 == $puesto4 || $puesto3 == $puesto4)
		$error = 1; 

  else {
		$esta = mysql_esta_en_tabla_grupo ($clave, $grupo, 1);
	  foreach ($equipos as $equipo) {
      mysql_query("update participante_puestos_grupos set puesto=$puesto where id_equipo=$equipo and clave='$clave';");

  		if ($esta == '0')
        mysql_query ("insert into apuesta_grupos_temp (grupo, puesto, equipo, clave) values ('$grupo', $puesto, $equipo, '$clave');");
  		else
        mysql_query ("update apuesta_grupos_temp set equipo=$equipo where clave='$clave' and puesto=$puesto and grupo='$grupo';");

      $puesto++;
		}
		$error = 0; 
	}

  mysql_cerrar_bbdd ($conexion);
	return $error;	
}


/***********************************************************************************************************/
/* mysql_inserta_octavos ($clave, $octavos1, $octavos2, $octavos3, $octavos4, $octavos5, $octavos6,        */
/*                         $octavos7, $octavos8, $octavos9, $octavos10, $octavos11, $octavos12,            */
/*                         $octavos13, $octavos14, $octavos15, $octavos16)                                 */
/* Funcion que inserta, en la tabla temporal de octavos, el orden de los equipos para cada grupo.          */
/*   clave: Hash de la porra en la que queremos actuar.                                                    */
/*   octavos1 a octavos16: ID de cada uno de los equipos que pasa a octavos de final.                      */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function mysql_inserta_octavos ($clave, $octavos1, $octavos2, $octavos3, $octavos4, $octavos5, $octavos6, $octavos7, $octavos8, $octavos9, $octavos10, $octavos11, $octavos12, $octavos13, $octavos14, $octavos15, $octavos16) {
  $octavos = array ($octavos1, $octavos2, $octavos3, $octavos4, $octavos5, $octavos6, $octavos7, $octavos8, $octavos9, $octavos10, $octavos11, $octavos12, $octavos13, $octavos14, $octavos15, $octavos16);
  $i = 1;

  $conexion = mysql_conectar_bbdd ();

	$esta = mysql_esta_en_tabla_fasefinal ($clave, 'o1');

  foreach ($octavos as $octavo) {
 		if ($esta == '0')
      mysql_query ("insert into apuesta_fase_final_temp (partido, equipo, clave) values ('o$i', $octavo, '$clave');");
 		else
      mysql_query ("update apuesta_fase_final_temp set equipo=$octavo where clave='$clave' and partido='o$i';");

    $i++;
	}

  mysql_cerrar_bbdd ($conexion);
}


/**********************/
function mysql_inserta_cuartos ($clave, $cuartos1, $cuartos2, $cuartos3, $cuartos4, $cuartos5, $cuartos6, $cuartos7, $cuartos8) {
  $cuartos = array ($cuartos1, $cuartos2, $cuartos3, $cuartos4, $cuartos5, $cuartos6, $cuartos7, $cuartos8);
  $i = 1;

  $conexion = mysql_conectar_bbdd ();

	$esta = mysql_esta_en_tabla_fasefinal ($clave, 'c1');

  foreach ($cuartos as $cuarto) {
 		if ($esta == '0') {
      mysql_query ("insert into  apuesta_fase_final_temp (partido, equipo, clave) values ('c$i', $cuarto, '$clave');");
    }
 		else {
      mysql_query ("update apuesta_fase_final_temp set equipo=$cuarto where clave='$clave' and partido='c$i';");
    }

    $i++;
	}

  mysql_cerrar_bbdd ($conexion);
}


/**********************/
function mysql_inserta_semifinal ($clave, $semifinal1, $semifinal2, $semifinal3, $semifinal4) {
  $semifinales = array ($semifinal1, $semifinal2, $semifinal3, $semifinal4);
  $i = 1;

  $conexion = mysql_conectar_bbdd ();

	$esta = mysql_esta_en_tabla_fasefinal ($clave, 's1');
  foreach ($semifinales as $semifinal) {
 		if ($esta == '0') {
      mysql_query ("insert into  apuesta_fase_final_temp(partido,equipo,clave) values('s$i', $semifinal, '$clave');");
    }
 		else {
      mysql_query ("update apuesta_fase_final_temp set equipo=$semifinal where clave='$clave' and partido='s$i';");
    }

    $i++;
	}

  mysql_cerrar_bbdd ($conexion);
}


/**********************/
function mysql_inserta_final ($clave, $final1, $final2) {
  $finales = array ($final1, $final2);
  $i = 1;

  $conexion = mysql_conectar_bbdd ();

	$esta = mysql_esta_en_tabla_fasefinal ($clave, 'f1');
  foreach ($finales as $final) {		
    if ($esta == '0') {
		  mysql_query ("insert into apuesta_fase_final_temp (partido, equipo, clave) values ('f$i', $final, '$clave');");
	  }
	  else {
	  	mysql_query("update apuesta_fase_final_temp set equipo=$final where clave='$clave' and partido='f$i';");
	  }

    $i++;
	}

  mysql_cerrar_bbdd ($conexion);
}



/**********************/
function mysql_inserta_finalisima ($clave, $campeon, $goles1, $goles2) {
  $conexion = mysql_conectar_bbdd ();

	if (($goles1 == '') || ($goles2 == ''))
		$error = 1;

  else {
	  $esta = mysql_esta_en_tabla_final ($clave);
    if ($esta == '0') {
		  mysql_query ("insert into apuesta_final_temp (campeon, goles1, goles2, clave) values ($campeon, $goles1, $goles2, '$clave');");
	  }
	  else {
		  mysql_query ("update apuesta_final_temp set campeon=$campeon, goles1=$goles1, goles2=$goles2 where clave='$clave';");
	  }
	  $error = 0;
  }
  mysql_cerrar_bbdd ($conexion);
	return $error;
}


/**********************/
function mysql_dime_final ($clave) {
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query ("select * from apuesta_final_temp where clave='$clave';");
	$datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


/**********************/
function mysql_confirma_porra ($clave) {
  $conexion = mysql_conectar_bbdd ();
	
	// Chequeo si la clave ya estÃ¡ en la tabla participante.
  $existe = mysql_existe_porra ($clave);
  if ($existe == '0') {
    // Insertamos participante
    mysql_query("insert into participante (nick, nombre, nombre_tde, extension, mail, clave, fecha) select nick, nombre, nombre_tde, extension, mail, clave, now() from participante_temp where clave='$clave';");

    // Insertamos Primera fase
    mysql_query("insert into apuesta_1fase (partido, resultado, clave) select partido, resultado, clave from apuesta_1fase_temp where clave='$clave'");

    // Insertamos apuesta de grupos
    mysql_query("insert into apuesta_grupos (grupo, puesto, equipo, clave) select grupo, puesto, equipo, clave from apuesta_grupos_temp where clave='$clave'");

    // Insertamos la fase final
    mysql_query("insert into apuesta_fase_final (partido, equipo, clave) select partido, equipo, clave from apuesta_fase_final_temp where clave='$clave'");

    // Insertamos final
    mysql_query("insert into apuesta_final (campeon, goles1, goles2, clave) select campeon, goles1, goles2, clave from apuesta_final_temp where clave='$clave';");
  }

  mysql_cerrar_bbdd ($conexion);

  return $existe;  
}



/**********************/
function mysql_obten_datos_porra ($clave) {
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query ("select * from participante where clave='$clave';");
	$datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


///////////////
function mysql_resultado_partido ($clave, $partido_id){
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query ("select * from apuesta_1fase_temp where clave='$clave' and partido='$partido_id';");
	$datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

  mysql_cerrar_bbdd ($conexion);
  return $datos ['resultado'];
}


///////////////
function mysql_resultado_real_primera_fase ($partido_id) {
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query ("select * from partidos_1fase where partidos_1fase_id='$partido_id';");
	$datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

  mysql_cerrar_bbdd ($conexion);
  return $datos ['resultado'];
}


///////////////
function mysql_confirma_pagar_porra ($porra) {
	if (!is_numeric($porra))
		return 2;

  $conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select * from participante where participante_id='$porra';");
	if (mysql_num_rows($consulta) != 1)
		return 3;

	$datos = mysql_fetch_object ($consulta);
	if ($datos->pagado == 1)
		return 4;
	
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


///////////////
function mysql_pagar_porra ($clave, $gestor) {
  $conexion = mysql_conectar_bbdd ();

	$consulta = mysql_query ("select * from participante where clave='$clave';");
	if (mysql_num_rows ($consulta) != 1)
		return 3;

  $datos = mysql_fetch_object ($consulta);

  if (!mysql_query("update participante set pagado=1, gestor='$gestor' where clave='$clave';"))
    return 4;
    
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}

function fecha_a_mysql ($fecha) { 
  ereg( "([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})", $fecha, $mifecha); 
  $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
  return $lafecha; 
} 


function mysql_a_fecha ($fecha) { 
  ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
  $lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1]; 
  return $lafecha; 
}

///////////////
function mysql_confirma_noticia ($fecha, $titulo, $texto) {
  $conexion = mysql_conectar_bbdd ();
  $titulo_formateado = htmlentities ($titulo);
  $texto_formateado = htmlentities ($texto);
  $fecha_formateada = fecha_a_mysql ($fecha);
	mysql_query ("insert into noticias (fecha, titulo, texto) values ('$fecha_formateada','$titulo_formateado','$texto_formateado');");
  mysql_cerrar_bbdd ($conexion);
}


///////////////
function mysql_confirma_amistoso ($texto) {
  $conexion = mysql_conectar_bbdd ();

  $texto_formateado = htmlentities ($texto);
	mysql_query("insert into amistosos (texto) values ('$texto_formateado');");

  mysql_cerrar_bbdd ($conexion);
}

function mysql_busca_porra ($porra) {
  $conexion = mysql_conectar_bbdd ();

  $consulta = mysql_query("select * from participante where participante_id=$porra;");
  $datos = mysql_fetch_object ($consulta);

  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


function mysql_actualiza_fecha ($fecha) {
  $conexion = mysql_conectar_bbdd ();

  $fecha_formateada = fecha_a_mysql ($fecha);
  if (mysql_query("update fecha_hoy set fecha='$fecha_formateada' where indice=1;"))
    mysql_query ("insert into fecha_hoy (fecha, indice) values ('$fecha_formateada', 1);");

  mysql_cerrar_bbdd ($conexion);
}



function mysql_partidos_en_fecha () {
  $conexion = mysql_conectar_bbdd ();

	$consulta1 = mysql_query("select * from fecha_hoy where indice=1;");
	$fecha = mysql_fetch_object ($consulta1);
	$consulta2 = mysql_query ("select * from partidos_1fase where fecha='$fecha->fecha';");

  $i=0;
	while ($linea = mysql_fetch_array ($consulta2, MYSQL_ASSOC)) {
	  $datos [$i] = $linea;
    $i++;
  }
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


function mysql_actualiza_resultado ($partido, $resultado) {
  $conexion = mysql_conectar_bbdd ();

	$resultado = strtoupper($resultado);
	if ($resultado != '1' && $resultado != 'X' && $resultado != '2')
    $error = 1;

  else {
    mysql_query("update partidos_1fase set resultado='$resultado' where partidos_1fase_id=$partido;");
    $error = 0;
  }

  mysql_cerrar_bbdd ($conexion);
  return $error;
}



function mysql_inserta_resultado_grupos ($puesto1, $puesto2, $puesto3, $puesto4, $grupo) {
// Inserta en la BD temporal res_grupos los datos del formulario
  $conexion = mysql_conectar_bbdd ();

	if ($puesto1 == $puesto2 || $puesto1 == $puesto3 || $puesto1 == $puesto4 || $puesto2 == $puesto3 || $puesto2 == $puesto4 || $puesto3 == $puesto4) 
		$error = 1; 

  else {
   	$consulta = mysql_query ("select distinct(res_grupos_id) from res_grupos where grupo='$grupo' and puesto='1';");
  	$datos = @mysql_fetch_array ($consulta);
	  if ($datos[0] != '') {
		  mysql_query ("update res_grupos set equipo=$puesto1 where puesto=1 and grupo='$grupo';");
		  mysql_query ("update res_grupos set equipo=$puesto2 where puesto=2 and grupo='$grupo';");
		  mysql_query ("update res_grupos set equipo=$puesto3 where puesto=3 and grupo='$grupo';");
		  mysql_query ("update res_grupos set equipo=$puesto4 where puesto=4 and grupo='$grupo';");
	  }
	  else {
		  mysql_query ("insert into res_grupos (grupo, puesto, equipo) values('$grupo', '1', $puesto1)");
		  mysql_query ("insert into res_grupos (grupo, puesto, equipo) values('$grupo', '2', $puesto2)");
		  mysql_query ("insert into res_grupos (grupo, puesto, equipo) values('$grupo', '3', $puesto3)");
		  mysql_query ("insert into res_grupos (grupo, puesto, equipo) values('$grupo', '4', $puesto4)");
	  }
	  $error = 0;
  }
  mysql_cerrar_bbdd ($conexion);
  return $error;
}	


//////////////
function mysql_actualiza_puntos_quiniela () {
  $conexion = mysql_conectar_bbdd ();
	$consulta = mysql_query("select * from participante where pagado=1;");

	print "<a href=index.php>[ Principal ]</a>";

  print "<table align=0 border=1>";
  print "<tr><td><center><b>Id Porra</b></center></td><td><b>Nombre Participante</b></td><td><center><b>Partidos<br>Acertados</b></center></td><td><center><b>Puntos</b></center></td></tr>";    

	while($datos_participante = mysql_fetch_object($consulta)) {
		$puntos=0;
		$datos_partido = mysql_query ("select count(*) from partidos_1fase as p, apuesta_1fase as a where p.partidos_1fase_id=a.partido and p.resultado!='NULL' and p.resultado=a.resultado and a.clave='$datos_participante->clave';");
		$num_partidos = mysql_fetch_array ($datos_partido);
		// 3 puntos por cada partido de la primera fase cuyo pronóstico sea acertado
    $puntos = 3 * $num_partidos [0];

		print "<tr><td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td><td><center>$num_partidos[0]</center></td><td><center>$puntos</center></td></tr>";
		mysql_query( "update participante set puntos=$puntos where clave='$datos_participante->clave';");
	}

  print "</table>";

	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


//////////////
function mysql_actualiza_puntos_por_grupo () {
  $grupos = mysql_obtener_grupos ();

  $conexion = mysql_conectar_bbdd ();
  // Ponemos a cero todos los puntos de grupo de los participantes para que no se vayan sumando de forma recurrente 
  mysql_query ("update participante set puntos_grupo=0;");

foreach ($grupos as $grupo) {
  print "<table align=0 border=1>";
  print "<tr><td>Id Porra</td><td><b>Nombre Participante</b></td><td><center><b>Puntos<br>Grupo $grupo</b></center></td><td><center><b>Puntos<br>Totales</b></center></td></tr>";    

  $puntos = 0;

  $consulta_participante = mysql_query ("select * from participante where pagado=1;");
  while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
		// Calculo de los equipos que estan en su posicion
		$consulta = mysql_query("select r.equipo,a.equipo,r.puesto from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and r.equipo=a.equipo and r.puesto=a.puesto and a.grupo='$grupo';");
		$n_equipos = @mysql_num_rows($consulta);
		// 4 puntos por cada equipo cuya posición final se acierte dentro de su Grupo
    $puntos = 4 * $n_equipos;
		
		// Calcular si los 2 primeros estan en orden
		$consulta = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=r.puesto and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");
	
		$n = @mysql_num_rows ($consulta);
		if ($n == 2)
			// Los dos primeros están en orden. Suman 5 puntos por acertar los dos primeros y 2 + 2 por que ambos equipos pasan a la siguiente fase
      $puntos = $puntos + 9;
		else if ($n == 1)
      // Sólo hay un equipo de los dos primeros en orden. Suma 2 puntos por acertar que el equipo pasa a la siguiente fase.
			$puntos = $puntos + 2;

	
		// Calcular si los 2 primeros estan alreves
		// Miramos si el primero de la apuesta coincide con el segundo del resultado 
    $consulta = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=1 and r.puesto=2 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");

		$n = @mysql_num_rows($consulta);

		// Si coinciden 
		if ($n == 1) {
  		// Miramos si el segundo de la apuesta coincide con el primero del resultado (con lo que ambos estarían cruzados) 
			$consulta2 = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=2 and r.puesto=1 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");

			$n2 = @mysql_num_rows ($consulta2);
			if ($n2 == 1)
        // Si están en orden cruzado, sumamos 3 puntos por acertar en orden inverso, y 2 + 2 por que ambos pasan a la siguiente fase
				$puntos = $puntos + 7;
			else
			  // Sólo estaría uno cruzado, que pasaría a la siguiente fase, con lo que sumaría 2 puntos
				$puntos = $puntos + 2;
		}
		
    else {
      // Si el primero de la apuesta no coincide, miramos a ver si coincide el segundo de la apuesta con el primero del resultado
			$consulta2 = mysql_query("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=2 and r.puesto=1 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");
			$n2 = @mysql_num_rows($consulta2);
      // Si coincide, sólo estaría uno cruzado, que pasaría a la siguiente fase, con lo que sumaría 2 puntos
			if ($n2 == 1)
				$puntos = $puntos + 2;
		}
    print "<tr>";
		print "<td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td><td><center>$puntos</center></td>";
	  $puntos = $datos_participante->puntos_grupo + $puntos;

	  mysql_query ("update participante set puntos_grupo=$puntos where clave='$datos_participante->clave';");
	  print "<td><center>$puntos</center></td>";
    print "</tr>";
	}
	print "</table>";
	print "<a href=index.php>[ Principal ]</a>";
}
  mysql_cerrar_bbdd ($conexion);
}




//////////////
/*  BACKUP DE LA FUNCIÓN ANTERIOR
function mysql_actualiza_puntos_por_grupo_buena ($grupo) {
  $conexion = mysql_conectar_bbdd ();

  print "<table align=0 border=1>";
  print "<tr><td>Id Porra</td><td><b>Nombre Participante</b></td><td><center><b>Puntos<br>Grupo $grupo</b></center></td><td><center><b>Puntos<br>Totales</b></center></td></tr>";    

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");
	while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
		$puntos = 0;
		// Calculo de los equipos que estan en su posicion
		$consulta = mysql_query("select r.equipo,a.equipo,r.puesto from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and r.equipo=a.equipo and r.puesto=a.puesto and a.grupo='$grupo';");
		$n_equipos = @mysql_num_rows($consulta);
		$puntos = 4 * $n_equipos;
		
		// Calcular si 2 primero estan en orden
		$consulta = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=r.puesto and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");
	
		$n = @mysql_num_rows ($consulta);
		if ($n == 2)
			$puntos = $puntos + 15;
		else if ($n == 1)
			$puntos = $puntos + 3;

	
		// Calcular si los 2 primeros estan alreves
		$consulta = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=1 and r.puesto=2 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");

		$n = @mysql_num_rows($consulta);

		if ($n == 1) {
			$consulta2 = mysql_query ("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=2 and r.puesto=1 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");
			$n2 = @mysql_num_rows ($consulta2);
			if ($n2 == 1)
				$puntos = $puntos + 12;
			else
				$puntos = $puntos + 3;
		}
		
    else {
			$consulta2 = mysql_query("select r.equipo,a.equipo,r.puesto,r.grupo from res_grupos as r, apuesta_grupos as a where a.clave='$datos_participante->clave' and r.grupo=a.grupo and a.puesto=2 and r.puesto=1 and (r.puesto=1 or r.puesto=2) and a.equipo=r.equipo and r.grupo='$grupo';");
			$n2 = @mysql_num_rows($consulta2);
			if ($n2 == 1)
				$puntos = $puntos + 3;
		}
    print "<tr>";
		print "<td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td><td><center>$puntos</center></td>";
	  $puntos = $datos_participante->puntos_grupo + $puntos;

	  mysql_query ("update participante set puntos_grupo=$puntos where clave='$datos_participante->clave';");
	  print "<td><center>$puntos</center></td>";
    print "</tr>";
	}
	print "</table>";
	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}
*/

//////////////
function mysql_traspasa_apuestas_fase_final () {
  $conexion = mysql_conectar_bbdd ();

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");

  while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
    // Octavos
    for ($i = 1, $k = 1; $i < 16; $i = $i + 2, $k++) {
      $consulta_octavo1 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='o$i';");
      $octavo1 = mysql_fetch_object ($consulta_octavo1);
      $j = $i + 1;
      $consulta_octavo2 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='o$j';");
      $octavo2 = mysql_fetch_object ($consulta_octavo2);
      mysql_query ("insert into apuesta_fase_final2 (partido, equipo1, equipo2, clave) values('o$k', $octavo1->equipo, $octavo2->equipo, '$datos_participante->clave')");
    }
		echo "<br><b>Octavos Actualizados</b><br>";
		flush();
    // Fin Octavos

		// Cuartos
    for ($i = 1, $k = 1; $i < 8; $i = $i + 2, $k++) {
			$consulta_cuarto1 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='c$i';");
			$cuarto1 = mysql_fetch_object ($consulta_cuarto1);
      $j = $i + 1;
      $consulta_cuarto2 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='c$j';");
      $cuarto2 = mysql_fetch_object ($consulta_cuarto2);
			mysql_query ("insert into apuesta_fase_final2 (partido, equipo1, equipo2, clave) values('c$k', $cuarto1->equipo, $cuarto2->equipo, '$datos_participante->clave')");
		}
		echo "<br><b>Cuartos Actualizados</b><br>";
		flush();
    // Fin Cuartos

		// Semifinales
    for ($i = 1, $k = 1; $i < 4; $i = $i + 2, $k++) {
      $consulta_semifinal1 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='s$i';");
      $semifinal1 = mysql_fetch_object ($consulta_semifinal1);
      $j = $i + 1;
      $consulta_semifinal2 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='s$j';");
      $semifinal2 = mysql_fetch_object ($consulta_semifinal2);
      mysql_query ("insert into apuesta_fase_final2 (partido, equipo1, equipo2, clave) values('s$k', $semifinal1->equipo, $semifinal2->equipo, '$datos_participante->clave')");
		}
		echo "<br><b>Semifinales Actualizadas</b><br>";

    // Final
    $consulta_final1 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='f1'");
    $final1 = mysql_fetch_object ($consulta_final1);
    $consulta_final2 = mysql_query ("select * from apuesta_fase_final where clave='$datos_participante->clave' and partido='f2'");
    $final2 = mysql_fetch_object ($consulta_final2);
    mysql_query ("insert into apuesta_fase_final2 (partido, equipo1, equipo2, clave) values('f1', $final1->equipo, $final2->equipo, '$datos_participante->clave')");
		echo "<br><b>Final Actualizada</b><br>";
	}

  mysql_cerrar_bbdd ($conexion);
}


//////////////
function mysql_actualiza_puntos_octavos () {
  $conexion = mysql_conectar_bbdd ();

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");

	print "<a href=index.php>[ Principal ]</a>";

  print "<table align=0 border=1>";
  print "<tr><td><b>Id Porra</b></td><td><b>Nombre Participante</b></td><td><center><b>Enfrentamientos<br>Acertados</b></center></td><td><center><b>Pases a Cuartos<br>Acertados</b></center></td><td><center><b>Vencedores y Pase<br>Acertados</b></center></td><td><b>Pases Desordenados<br>Acertados</b></td><td><center><b>Puntos</b></center></td></tr>";    

	while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
    print "<tr><td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td>";
		$puntos = 0;

		// 5 puntos por cada enfrentamiento de octavos de final que se acierte en el orden adecuado
		$consulta_enfrentamiento_octavos = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido=r.partido and a.partido like 'o%' and (a.equipo1=r.equipo1 and a.equipo2=r.equipo2) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_enfrentamiento_octavos);
		// Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
		while ($enfrentamiento_octavos = mysql_fetch_object($consulta_enfrentamiento_octavos)) {
			mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$enfrentamiento_octavos->apuesta_fase_final2_id;");
		}
		$puntos = $puntos + ($n * 5);
		print "<td>$n</td>";

		// 5 puntos por cada equipo que pase a cuartos de final, habiéndolo pronosticado.
		$consulta_pase_a_cuartos = mysql_query ("select * from apuesta_fase_final as a, res_fase_final2 as r where a.partido like 'c%' and r.partido like 'c%' and a.clave='$datos_participante->clave' and (r.equipo1=a.equipo or r.equipo2=a.equipo);");
		$n = @mysql_num_rows ($consulta_pase_a_cuartos);
		$puntos = $puntos + ($n * 5);
		print "<td>$n</td>";
		
		// 10 puntos por acertar el vencedor de una eliminatoria de octavos de final, cuyos equipos se hayan averiguado
		$consulta_octavos_acertados = mysql_query ("select * from apuesta_fase_final2 where clave='$datos_participante->clave' and acertado=1 and partido like 'o%';");
    $c = 0;
		while ($octavos_acertados = mysql_fetch_object ($consulta_octavos_acertados)) {
			$consulta_ganador_octavos_acertados = mysql_query ("select * from res_fase_final2 as r, apuesta_fase_final2 as a where r.partido like 'c%' and a.partido like 'c%' and a.clave='$datos_participante->clave' and ((((r.equipo1=$octavos_acertados->equipo1) or (r.equipo2=$octavos_acertados->equipo1)) and ((a.equipo1=$octavos_acertados->equipo1) or (a.equipo2=$octavos_acertados->equipo1))) or (((r.equipo1=$octavos_acertados->equipo2) or (r.equipo2=$octavos_acertados->equipo2)) and ((a.equipo1=$octavos_acertados->equipo2) or (a.equipo2=$octavos_acertados->equipo2))));");
			$n = mysql_num_rows ($consulta_ganador_octavos_acertados);
			if ($n == 1) {
				$puntos = $puntos + 10;
				$c++;
			}
		}
 		print "<td>$c</td>";
		
		// 3 puntos por cada enfrentamiento de octavos de final que se acierte y que NO este en el orden adecuado
		$consulta_octavos_no_acertados = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido!=r.partido and a.partido like 'o%' and r.partido like 'o%' and ((a.equipo1=r.equipo2 and a.equipo2=r.equipo1) or (a.equipo1=r.equipo1 and a.equipo2=r.equipo2)) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_octavos_no_acertados);
		$puntos = $puntos + ($n * 3);
		print "<td>$n</td>";

    // Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
    while ($octavos_no_acertados = mysql_fetch_object ($consulta_octavos_no_acertados)) {
      mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$octavos_no_acertados->apuesta_fase_final2_id;");
    }		
		mysql_query ("update participante set puntos_octavos=$puntos where clave='$datos_participante->clave'");
		print "<td>$puntos</td></tr>";
		flush();
	}
  print "</table>";

	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


/////////////////////
function mysql_actualiza_puntos_cuartos () {
/// EXACTAMENTE IGUAL AL DE OCTAVOS
  $conexion = mysql_conectar_bbdd ();

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");

	print "<a href=index.php>[ Principal ]</a>";

  print "<table align=0 border=1>";
  print "<tr><td><b>Id Porra</b></td><td><b>Nombre Participante</b></td><td><center><b>Enfrentamientos<br>Acertados</b></center></td><td><center><b>Pases a Cuartos<br>Acertados</b></center></td><td><center><b>Vencedores y Pase<br>Acertados</b></center></td><td><b>Pases Desordenados<br>Acertados</b></td><td><center><b>Puntos</b></center></td></tr>";    

	while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
    print "<tr><td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td>";
		$puntos = 0;

		// 12 puntos por cada enfrentamiento de cuartos de final que se acierte en el orden adecuado
		$consulta_enfrentamiento_cuartos = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido=r.partido and a.partido like 'c%' and (a.equipo1=r.equipo1 and a.equipo2=r.equipo2) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_enfrentamiento_cuartos);
		// Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
		while ($enfrentamiento_cuartos = mysql_fetch_object($consulta_enfrentamiento_cuartos)) {
			mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$enfrentamiento_cuartos->apuesta_fase_final2_id;");
		}
		$puntos = $puntos + ($n * 12);
		print "<td>$n</td>";

		// 8 puntos por cada equipo que pase a semifinales, habiéndolo pronosticado.
		$consulta_pase_a_semifinales = mysql_query ("select * from apuesta_fase_final as a, res_fase_final2 as r where a.partido like 's%' and r.partido like 's%' and a.clave='$datos_participante->clave' and (r.equipo1=a.equipo or r.equipo2=a.equipo);");
		$n = @mysql_num_rows ($consulta_pase_a_semifinales);
		$puntos = $puntos + ($n * 8);
		print "<td>$n</td>";
		
		// 15 puntos por acertar el vencedor de una eliminatoria de cuartos de final, cuyos equipos se hayan averiguado
		$consulta_cuartos_acertados = mysql_query ("select * from apuesta_fase_final2 where clave='$datos_participante->clave' and acertado=1 and partido like 'c%';");
    $c = 0;
		while ($cuartos_acertados = mysql_fetch_object ($consulta_cuartos_acertados)) {
			$consulta_ganador_cuartos_acertados = mysql_query ("select * from res_fase_final2 as r, apuesta_fase_final2 as a where r.partido like 's%' and a.partido like 's%' and a.clave='$datos_participante->clave' and ((((r.equipo1=$cuartos_acertados->equipo1) or (r.equipo2=$cuartos_acertados->equipo1)) and ((a.equipo1=$cuartos_acertados->equipo1) or (a.equipo2=$cuartos_acertados->equipo1))) or (((r.equipo1=$cuartos_acertados->equipo2) or (r.equipo2=$cuartos_acertados->equipo2)) and ((a.equipo1=$cuartos_acertados->equipo2) or (a.equipo2=$cuartos_acertados->equipo2))));");
			$n = mysql_num_rows ($consulta_ganador_cuartos_acertados);
			if ($n == 1) {
				$puntos = $puntos + 15;
				$c++;
			}
		}
 		print "<td>$c</td>";
		
		// 7 puntos por cada enfrentamiento de cuartos de final que se acierte y que NO este en el orden adecuado
		$consulta_cuartos_no_acertados = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido!=r.partido and a.partido like 'c%' and r.partido like 'c%' and ((a.equipo1=r.equipo2 and a.equipo2=r.equipo1) or (a.equipo1=r.equipo1 and a.equipo2=r.equipo2)) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_cuartos_no_acertados);
		$puntos = $puntos + ($n * 7);
		print "<td>$n</td>";

    // Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
    while ($cuartos_no_acertados = mysql_fetch_object ($consulta_cuartos_no_acertados)) {
      mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$cuartos_no_acertados->apuesta_fase_final2_id;");
    }		
		mysql_query ("update participante set puntos_cuartos=$puntos where clave='$datos_participante->clave'");
		print "<td>$puntos</td></tr>";
		flush();
	}
  print "</table>";

	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


/////////////////
function mysql_actualiza_puntos_semifinales () {
  $conexion = mysql_conectar_bbdd ();

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");

	print "<a href=index.php>[ Principal ]</a>";

  print "<table align=0 border=1>";
  print "<tr><td><b>Id Porra</b></td><td><b>Nombre Participante</b></td><td><center><b>Enfrentamientos<br>Acertados</b></center></td><td><center><b>Pases a la Final<br>Acertados</b></center></td><td><center><b>Vencedores y Pase<br>Acertados</b></center></td><td><b>Pases Desordenados<br>Acertados</b></td><td><center><b>Puntos</b></center></td></tr>";    

	while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
    print "<tr><td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td>";
		$puntos = 0;

		// 15 puntos por cada enfrentamiento de semifinales que se acierte en el orden adecuado
		$consulta_enfrentamiento_semifinales = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido=r.partido and a.partido like 's%' and (a.equipo1=r.equipo1 and a.equipo2=r.equipo2) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_enfrentamiento_semifinales);
		// Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
		while ($enfrentamiento_semifinales = mysql_fetch_object($consulta_enfrentamiento_semifinales)) {
			mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$enfrentamiento_semifinales->apuesta_fase_final2_id;");
		}
		$puntos = $puntos + ($n * 15);
		print "<td>$n</td>";

		// 12 puntos por cada equipo que pase a la final, habiéndolo pronosticado.
		$consulta_pase_a_semifinales = mysql_query ("select * from apuesta_fase_final as a, res_fase_final2 as r where a.partido like 'f%' and r.partido like 'f%' and a.clave='$datos_participante->clave' and (r.equipo1=a.equipo or r.equipo2=a.equipo);");
		$n = @mysql_num_rows ($consulta_pase_a_semifinales);
		$puntos = $puntos + ($n * 12);
		print "<td>$n</td>";

		// 20 puntos por acertar el vencedor de una eliminatoria de semifinales, cuyos equipos se hayan averiguado
		$consulta_semifinales_acertados = mysql_query ("select * from apuesta_fase_final2 where clave='$datos_participante->clave' and acertado=1 and partido like 's%';");
    $c = 0;
		while ($semifinales_acertados = mysql_fetch_object ($consulta_semifinales_acertados)) {
			$consulta_ganador_semifinales_acertados = mysql_query ("select * from res_fase_final2 as r, apuesta_fase_final2 as a where r.partido like 'f%' and a.partido like 'f%' and a.clave='$datos_participante->clave' and ((((r.equipo1=$semifinales_acertados->equipo1) or (r.equipo2=$semifinales_acertados->equipo1)) and ((a.equipo1=$semifinales_acertados->equipo1) or (a.equipo2=$semifinales_acertados->equipo1))) or (((r.equipo1=$semifinales_acertados->equipo2) or (r.equipo2=$semifinales_acertados->equipo2)) and ((a.equipo1=$semifinales_acertados->equipo2) or (a.equipo2=$semifinales_acertados->equipo2))));");
			$n = mysql_num_rows ($consulta_ganador_semifinales_acertados);
			if ($n == 1) {
				$puntos = $puntos + 20;
				$c++;
			}
		}
 		print "<td>$c</td>";

		// 12 puntos por cada enfrentamiento de semifinales que se acierte y que NO este en el orden adecuado
		$consulta_semifinales_no_acertados = mysql_query ("select * from apuesta_fase_final2 as a, res_fase_final2 as r where a.partido!=r.partido and a.partido like 's%' and r.partido like 's%' and ((a.equipo1=r.equipo2 and a.equipo2=r.equipo1) or (a.equipo1=r.equipo1 and a.equipo2=r.equipo2)) and a.clave='$datos_participante->clave';");
		$n = @mysql_num_rows ($consulta_semifinales_no_acertados);
		$puntos = $puntos + ($n * 12);
		print "<td>$n</td>";

    // Para poner a 1 el campo acertado en la tabla apuesta_fase_final2
    while ($semifinales_no_acertados = mysql_fetch_object ($consulta_semifinales_no_acertados)) {
      mysql_query ("update apuesta_fase_final2 set acertado=1 where apuesta_fase_final2_id=$semifinales_no_acertados->apuesta_fase_final2_id;");
    }		
		mysql_query ("update participante set puntos_semifinales=$puntos where clave='$datos_participante->clave'");
		print "<td>$puntos</td></tr>";
		flush();
	}
  print "</table>";

	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


/////////
function mysql_actualiza_puntos_final () {
  $conexion = mysql_conectar_bbdd ();

	$consulta_participante = mysql_query ("select * from participante where pagado=1;");

	print "<a href=index.php>[ Principal ]</a>";

  print "<table align=0 border=1>";
  print "<tr><td><b>Id Porra</b></td><td><b>Nombre Participante</b></td><td><center><b>Acertado<br>Resultado</b></center></td><td><center><b>Acertado<br>Campe&oacute;n</b></center></td><td><center><b>Puntos</b></center></td></tr>";    

	while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
    print "<tr><td><center>$datos_participante->participante_id</center></td><td>$datos_participante->nombre</td>";
		$puntos = 0;

    // 15 puntos por acertar el resultado de la final, aunque no se acierten los equipos finalistas. No se tiene en cuenta el orden del resultado
    $consulta_resultado = mysql_query ("select * from apuesta_final as a, res_final as r where ((a.goles1=r.goles1 and a.goles2=r.goles2) or (a.goles1=r.goles2 and a.goles2=r.goles1)) and a.clave='$datos_participante->clave';");
		if (mysql_num_rows ($consulta_resultado)) {
			$puntos = $puntos + 15;
		  print "<td>Si</td>";
		}
		else
		  print "<td>No</td>";

    // 25 puntos por acertar el campeón
		$consulta_campeon = mysql_query ("select * from apuesta_final as a, res_final as r where a.campeon=r.campeon and a.clave='$datos_participante->clave';");
		if (mysql_num_rows ($consulta_campeon) == 1) {
			$puntos = $puntos + 25;
		  print "<td>Si</td>";
		}
		else
		  print "<td>No</td>";

    print "<td>$puntos</td>";
    print "</tr>";

    mysql_query ("update participante set puntos_final=$puntos where clave='$datos_participante->clave';");
	}

  print "</table>";

	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


//////////
function mysql_buscar_porra ($busca) {
  $conexion = mysql_conectar_bbdd ();

  if ($busca == '')
    $datos_participante = '0';
  
  else {
    /* Mientras se da por finalizado el plazo de apuestas solo se puede consultar por clave */
//    $consulta_participante = mysql_query ("select * from participante where (clave='$busca');");
    /* Una vez finalizado el plazo de apuestas se podra consultar por ID de porra o por Nick */
    $consulta_participante = mysql_query ("select * from participante where (participante_id='$busca' or nick='$busca');");
    if (mysql_num_rows ($consulta_participante) == 0)
      $datos_participante = '1';

    else {
      $datos_participante = mysql_fetch_array ($consulta_participante);

    /* Comentado para que se puedan consultar tanto las porras pagadas como las no pagadas */
//      if ($datos_participante ['pagado'] == 0)
//        $datos_participante = '2';
    }
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $datos_participante;
}


//////////
function mysql_numero_visitas () {
  $conexion = mysql_conectar_bbdd ();
	mysql_query("insert into contador(contador) values(1);");
	$consulta_visitas = mysql_query ("select max(contador_id) from contador;");
	$visitas = mysql_fetch_array ($consulta_visitas);
  mysql_cerrar_bbdd ($conexion);
  return $visitas [0];
}


//////////
function mysql_imprimir_amistosos () {
  $conexion = mysql_conectar_bbdd ();
	$consulta = mysql_query ("select * from amistosos;");
//	print "Amistosos:&nbsp;&nbsp;&nbsp;";
	while ($datos = mysql_fetch_object($consulta)) {
    print "$datos->texto &nbsp;&nbsp;&nbsp;;&nbsp;&nbsp;&nbsp;";
  }
  mysql_cerrar_bbdd ($conexion);
}
  

function mysql_participantes () {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select count(*) from participante where pagado=1;");
  $participantes = mysql_fetch_array ($consulta);
  mysql_cerrar_bbdd ($conexion);
  return $participantes [0];
}  


function mysql_muestra_noticias () {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select * from noticias order by fecha desc, noticias_id desc limit 3;");

  $i = 0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
    $noticias [$i] = $linea;
    $i++;
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $noticias;
}


function mysql_consulta_top5 () {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select participante_id, nick, puesto,puntos+puntos_grupo+puntos_octavos+puntos_cuartos+puntos_semifinales+puntos_final as total from participante where pagado='1' order by total desc, puntos desc, participante_id limit 5;");

  $i = 0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
    $top5 [$i] = $linea;
    $i++;
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $top5;
}


function mysql_consulta_top_menos3 () {
  $conexion = mysql_conectar_bbdd ();
//  $consulta = mysql_query ("select participante_id, nick, puesto, puntos+puntos_grupo+puntos_octavos+puntos_cuartos+puntos_semifinales+puntos_final as total from participante where pagado='1' order by total asc, puntos asc, participante_id limit 3;");
  $consulta = mysql_query ("select participante_id, nick, puesto, total from (select *, puntos+puntos_grupo+puntos_octavos+puntos_cuartos+puntos_semifinales+puntos_final as total from participante where pagado='1' order by total asc, puntos asc, participante_id desc limit 3) as participante order by total desc, puntos desc, participante_id;");
  $i = 0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
    $top3 [$i] = $linea;
    $i++;
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $top3;
}


function mysql_apuestas_por_campeon () {
  $conexion = mysql_conectar_bbdd ();
	$consulta = mysql_query ("select count(f.clave) as cuenta, e.nombre from apuesta_final as f, participante as p, equipo as e where p.pagado=1 and p.clave=f.clave and e.equipo_id=f.campeon group by campeon order by cuenta desc;");
	$i = 0;
	while ($linea = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
    $datos [$i] = $linea;
    $i++;
	}
  mysql_cerrar_bbdd ($conexion);
  return $datos;
}


//////
function mysql_escribe_clasificacion_en_HTML () {
  $conexion = mysql_conectar_bbdd ();
  if (file_exists ("../clasif_ext.html"))
    copy ("../clasif_ext.html", "../clasificaciones/clasif_ext".date ("d-m-Y.H.i.s").".html");

  $fp = fopen("../clasif_ext.html", "w");

	$hoy = date("j/n/Y G:i",time());

	fwrite ($fp, "<html>\n");
 	fwrite ($fp, "<head>\n");
 	fwrite ($fp, "<title>Porra Mundial Sud&aacute;frica 2010</title>\n");
	fwrite ($fp, "<link href='estilo.css' type=text/css rel=stylesheet>\n");
	fwrite ($fp, "<body background=imagenes/fondo.jpg topmargin='0'>");
        
	fwrite ($fp, "<table cellspacing=0 cellpadding=0 width=100% align=center border=0>");
	fwrite ($fp, "<tr><td align=center><a href=http://es.fifa.com/worldcup/index.html><img src=./imagenes/logo_cabecera.gif border=0></a></td></tr>");                                                                                                                                                       
  fwrite ($fp, "</table>");

  fwrite ($fp, "<br><center><input type=button value='Crea tu Clasificaci&oacute;n Personalizada' onClick=\"location.href='clasificacion_parcial.php'\"></center><br><br>");

  fwrite ($fp, "<table cellspacing=0 cellpadding=0 width=100% align=center border=0>");
  fwrite ($fp, "<tr><td style='height: 50px'>&nbsp;</td></tr><tr><td>");

	fwrite($fp, "<center><h1>Clasificaci&oacute;n Provisional actualizada el $hoy</h1></center>\n");

  fwrite($fp, "<table align=center cellpadding=7 border=1>\n");
	fwrite($fp, "<tr><td width=10px>&nbsp;</td><td>Parcicipante</td><td><center><b>Total</b></center></td><td><center>Puntos<br>Quiniela</center></td><td><center>Puntos<br>Grupo</center></td><td><center>Puntos<br>Octavos</center></td><td><center>Puntos<br>Cuartos</center></td><td><center>Puntos<br>Semifinales</center></td><td><center>Puntos<br>Final</center></td><td><center>Puesto<br>Anterior</center></td><td>&nbsp;</td></tr>");

 	$i = 1;
	$color = "#FFFFFF";

  $consulta_porras = mysql_query ("select nick, clave, participante_id, pagado, nombre_tde, puntos, puntos_grupo, puntos_octavos, puntos_cuartos, puntos_semifinales, puntos_final, puesto, puntos+puntos_grupo+puntos_octavos+puntos_cuartos+puntos_semifinales+puntos_final as total from participante where pagado='1' order by total desc, puntos desc, puntos_grupo desc, puntos_cuartos desc, puntos_semifinales desc, puntos_final desc, participante_id;");
  $total_porras = @mysql_num_rows ($consulta_porras);

	while ($datos_porra = mysql_fetch_array ($consulta_porras, MYSQL_ASSOC)) {
    $puesto_anterior = $datos_porra['puesto'];
    // Seleccionamos el color del fondo de acuerdo al puesto
    if ($i == 1) {
      $bgcolor = "#CCFFFF";
    }
		else if ($i > 1 && $i < 4) {
      $bgcolor = "#CCFF99";
    }
    else if ($i >= 4 && $i < $total_porras) {
      $bgcolor = "#FFFFFF";
    }
    else if ($i == $total_porras){
      $bgcolor = "#FF9933";
    }

    // Elegimos el icono de "sube", "baja" o "mantiene"
    if ($datos_porra['puesto'] == NULL) {
      $imagen = "a_eq.gif";
      $datos_porra['puesto'] = '-';
    }
    else if ($i > $datos_porra['puesto']) {
      $imagen = "a_down.gif";		
    }
    else if ($i < $datos_porra['puesto']) {
      $imagen = "a_up.gif";		
    }
    else if ($i == $datos_porra['puesto']) {
      $imagen = "a_eq.gif";		
    }
    
    fwrite ($fp, "<tr bgcolor=$bgcolor><td><center>$i</center></td><td><a href=index.php?op=consultar_now&busca=".$datos_porra['participante_id'].">".$datos_porra['nick']."</a></td><td><center><b>".$datos_porra['total']."</b></center></td><td><center>".$datos_porra['puntos']."</center></td><td><center>".$datos_porra['puntos_grupo']."</center></td><td><center>".$datos_porra['puntos_octavos']."</center></td><td><center>".$datos_porra['puntos_cuartos']."</center></td><td><center>".$datos_porra['puntos_semifinales']."</center></td><td><center>".$datos_porra['puntos_final']."</center></td><td><center>".$datos_porra['puesto']."</center></td><td><center><img src=imagenes/$imagen></center></td></tr>\n");

    mysql_query ("update participante set puesto='$i',puesto_anterior=$puesto_anterior where participante_id=".$datos_porra['participante_id'].";");
		$i++;
  }

	fwrite ($fp, "</table>\n");
	fwrite ($fp, "<br><center><input type=button value='Volver' onClick=history.back()></center>\n");

	fclose ($fp);
	
	print "Hecho bien! el ";
	print date("j/n/Y G:i",time());

  print "<br>";
	print "<a href=index.php>[ Principal ]</a>";

  mysql_cerrar_bbdd ($conexion);
}


function mysql_porras_pendientes () {
  $conexion = mysql_conectar_bbdd ();
  $consulta = mysql_query ("select count(*) from participante where pagado='0';");
  $pendientes = mysql_fetch_array ($consulta);
  mysql_cerrar_bbdd ($conexion);
  return $pendientes [0];
}

function mysql_compara_cruces ($partido, $equipo) {
  $conexion = mysql_conectar_bbdd ();
  
  // La fase nos vendrá dada por el primer caracter del partido
  $fase = $partido[0];

  $consulta_previa = mysql_query ("select count(*) from res_fase_final where partido='$partido';");
  $existe = mysql_fetch_array ($consulta_previa);
  
  // Si el partido por el que pregunto está ya en la Base de Datos, le pongo color
  if ($existe[0] != 0) {
    $consulta = mysql_query ("select * from res_fase_final where equipo=$equipo and partido like '$fase%';");
    $datos = mysql_fetch_array ($consulta, MYSQL_ASSOC);

    if ($datos) {
      // Equipo colocado en su sitio
      if ($datos ['partido'] == $partido) {
        $resultado = "bgcolor=green";
      }
      // Equipo que está, pero no en su sitio
      else {
        $resultado = "bgcolor=orange";
      }
    }
    // Equipo que no está
    else
      $resultado = "bgcolor=red";
  }
  // Si no está en la Base de Datos, no le pongo color
  else {
    $resultado = "";
  }
  
  mysql_cerrar_bbdd ($conexion);
  return $resultado;
}

?>