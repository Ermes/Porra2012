<?php

/***********************************************************************************************************/
/* apuesta_form_inicial($clave)                                                                            */
/* Formulario inicial de apuestas, donde se toman los datos del apostante.                                 */
/*                                                                                                         */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function apuesta_form_inicial ($clave) {
  cabecera();

  print "<br><center><h1>SuperPorra</h1></center><br>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=apuesta_1>";
	print "<input type=hidden name=clave value=$clave>";

	print "<table width=500px align=center border=0>";
	print "<tr><td>Nick:</td><td><input type=input name=nick></td></tr>";
	print "<tr><td>Nombre y Apellidos Apostante:</td><td><input type=input name=nombre_apostante></td></tr>";
	print "<tr><td>Nombre y Apellidos Empleado TdE: (opcional)</td><td><input type=input name=nombre_tde></td></tr>";
	print "<tr><td>Telefono: </td><td><input type=input name=extension></td></tr>";
	print "<tr><td>Mail: ***</td><td><input type=input name=mail></td></tr>";
//	print "<center>El plazo de apuesta esta cerrado. Lo sentimos.</center>";
	print "</table>";

  print "<br>";
	print "<center><input type=submit value=Aceptar>&nbsp;&nbsp;<input type=reset value=Borrar>&nbsp;&nbsp;<input type=button value=Volver onClick=history.back()></center>";
  print "</form>";
	print "<center>*** esa direccion de mail sera a la que se enviara el comprobante de la porra, necesario para su cobro en caso de premio</center>";
	foot();
}


/***********************************************************************************************************/
/* apuesta_1form ($clave)                                                                                  */
/* Formulario de apuestas para la primera fase, en formato quiniela, y con opción de rellenar al azar.     */
/*                                                                                                         */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function apuesta_1form ($clave) {
	cabecera();

  $SALTO_COLUMNA = 2;
  $salto = 0;
  $grupos = mysql_obtener_grupos ();
	$signos = array ("1","X","2");

	print "<center><h2><b>Primera Fase</b></h2></center>";

	print "<center class=aviso>Para obtener otros resultados aleatorios pulse <i>Regenerar</i> (Actualmente en obras). En la ventana que aparezca pulse <i>Reintentar</i> o <i>ENTER</i></center>";
	print "<center class=aviso><b>Revise</b> y seleccione los signos que quiera modificar, y pulse <i>Aceptar</i></center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=apuesta_2>";
	print "<input type=hidden name=clave value=$clave>";

  print "<table align=center cellspacing=10 border=0><tr>";

	foreach($grupos as $grupo) {
    $partidos = mysql_partidos_primera_fase_grupo ($grupo);

    print "<td width=50%>";

    print "<table width=100% align=center border=1>";
    print "<tr><td colspan=3><b>Grupo $grupo</b></td></tr>";
    
    foreach ($partidos as $partido) {
      $equipo1 = mysql_datos_de_equipo ($partido['equipo1']);
      $equipo2 = mysql_datos_de_equipo ($partido['equipo2']);

      $azar = seleccion_aleatoria (sizeof ($signos));

      $select = "<select size=1 name=partido".$partido['partidos_1fase_id'].">";
      foreach ($signos as $signo) {
        if ($signo == $signos [$azar])
          $select .= "<option value=$signo selected>$signo</option>";
        else
          $select .= "<option value=$signo>$signo</option>";
      }
      $select .= "</select>";

  		printf ("<tr><td width=130px><img src=imagenes/%s.gif>&nbsp;&nbsp;%s</td><td width=130px><img src=imagenes/%s.gif>&nbsp;&nbsp;%s</td><td>%s</td></tr>", $equipo1['bandera'], $equipo1['nombre'], $equipo2['bandera'], $equipo2['nombre'], $select);
    }
    print "</table>";

    print "</td>";

 		$salto++;
		if (!($salto%$SALTO_COLUMNA))
 			print "</tr><tr>";
	}
	print "</table>";

  print "<br>";
	print "<center><input type=submit value=Aceptar>&nbsp;&nbsp;<input type=button value=Regenerar onClick=history.go(0)>&nbsp;&nbsp;<input type=button value='Pagina Principal' onClick=history.go(-2)>";
  print "</form>";

	foot();
}




/***********************************************************************************************************/
/* apuesta_2form ($clave)                                                                                  */
/* Formulario de apuestas para la segunda fase, indicando el orden de cada equipo dentro de su grupo.      */
/*                                                                                                         */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function apuesta_2form ($clave) {
	cabecera();

  $SALTO_COLUMNA = 4;
  $salto = 0;
  $grupos = mysql_obtener_grupos ();
	
	print "<center><h2><b>Puestos de los grupos</b></h2></center>";

	print "<center class=aviso>El orden en los equipos y sus respectivos puntos se han preestablecido en funcion de los resultados seleccionados anteriormente.</center>";
	print "<center class=aviso><b>Si los puntos no son correctos, pulse Actualizar Puntos</b>, reviselos y/o modifiquelos a su gusto y pulse <i>Aceptar</i></center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=apuesta_3>";
	print "<input type=hidden name=clave value=$clave>";

  print "<table style='height: 400px' align=center cellpadding=10 border=0><tr>";
	
	foreach($grupos as $grupo) {
		actualiza_puestos_grupos ($clave, $grupo);
    $posiciones = $puestos = mysql_puestos_grupo ($grupo, $clave);

    $equipos = mysql_equipos_en_un_grupo ($grupo);
    $dibuja_banderas = "";
    foreach ($equipos as $equipo) {
      $dibuja_banderas .= "&nbsp;<img src=imagenes/".$equipo ['bandera'].".gif>&nbsp;";
    }
    
		print "<td>";
		print "<table width=100% border=1>";
		print "<tr><td align=center width=20><b>$grupo</b></td><td align=center>$dibuja_banderas</td></tr>";

    foreach ($puestos as $puesto) {

      $select = "<select name=puesto".$puesto ['puesto'].$grupo." style='width: 150px;'>";

      foreach ($posiciones as $posicion) {
        $equipo = mysql_datos_de_equipo ($posicion ['id_equipo']);

        if ($posicion ['puesto'] == $puesto ['puesto'])
          $select .= "<option value=".$equipo ['equipo_id']." selected>".$equipo ['nombre']." ".$posicion ['puntos']."</option>";
        else
          $select .= "<option value=".$equipo ['equipo_id'].">".$equipo ['nombre']." ".$posicion ['puntos']."</option>";
      }
      
      $select .= "</select>";
      print "<tr><td>".$puesto ['puesto']."º</td><td align=center>$select</td></tr>";
    }
    print "</table>";

 		$salto++;
		if (!($salto%$SALTO_COLUMNA))
 			print "</tr><tr>";
  }
  print "</table>";

  print "<br>";
	print "<center><input type=submit value=Aceptar>&nbsp;&nbsp;<input type=button value='Actualizar puntos' onClick=history.go(0)>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
  print "</form>";

	foot();
}


/***********************************************************************************************************/
/* octavos_form ($clave)                                                                                   */
/* Dibuja el formulario de cruces de octavos de final .                                                    */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function octavos_form ($clave) {
	cabecera();

// Lado izquierdo
	$equipo_a1 = mysql_dime_equipo ($clave, "A", "1");
	$equipo_b2 = mysql_dime_equipo ($clave, "B", "2");

	$equipo_c1 = mysql_dime_equipo ($clave, "C", "1");
	$equipo_d2 = mysql_dime_equipo ($clave, "D", "2");

	$equipo_e1 = mysql_dime_equipo ($clave, "E", "1");
	$equipo_f2 = mysql_dime_equipo ($clave, "F", "2");

	$equipo_g1 = mysql_dime_equipo ($clave, "G", "1");
	$equipo_h2 = mysql_dime_equipo ($clave, "H", "2");

// Lado derecho
	$equipo_b1 = mysql_dime_equipo ($clave, "B", "1");
	$equipo_a2 = mysql_dime_equipo ($clave, "A", "2");

	$equipo_d1 = mysql_dime_equipo ($clave, "D", "1");
	$equipo_c2 = mysql_dime_equipo ($clave, "C", "2");

	$equipo_f1 = mysql_dime_equipo ($clave, "F", "1");
	$equipo_e2 = mysql_dime_equipo ($clave, "E", "2");

	$equipo_h1 = mysql_dime_equipo ($clave, "H", "1");
	$equipo_g2 = mysql_dime_equipo ($clave, "G", "2");

	print "<center><h2><b>Octavos de Final</b></h2></center>";

	print "<center class=aviso>Los ganadores de la eliminatoria se han puesto aleatoriamente. Revisalos, corrigelos como quieras y pulsa <i>Aceptar</i>.</center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=octavos>";
	print "<input type=hidden name=clave value=$clave>";

 	print "<table style='height: 400px' align=center border=0>";
  print "<tr><td>";
    dibuja_cruce (1, $equipo_a1, $equipo_b2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (5, $equipo_b1, $equipo_a2, "DERECHA");
  print "</td></tr><tr><td>";
    dibuja_cruce (2, $equipo_c1, $equipo_d2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (6, $equipo_d1, $equipo_c2, "DERECHA");
  print "</td></tr><tr><td>";
    dibuja_cruce (3, $equipo_e1, $equipo_f2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (7, $equipo_f1, $equipo_e2, "DERECHA");
  print "</td></tr><tr><td>";
    dibuja_cruce (4, $equipo_g1, $equipo_h2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (8, $equipo_h1, $equipo_g2, "DERECHA");
  print "</td></tr>";
  print "</table>";

  print "<br>";
	print "<center><input type=submit value='Aceptar'>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
  print "</form>";

	foot();
}


/***********************************************************************************************************/
/* cuartos_form ($clave)                                                                                   */
/* Dibuja el formulario de cruces de cuartos de final.                                                     */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function cuartos_form ($clave) {
	cabecera();

// Cruces que van por el lado izquierdo de la tabla
  $equipo_a1b2 = mysql_dime_ganador ($clave, "c1");
	$equipo_c1d2 = mysql_dime_ganador ($clave, "c2");

	$equipo_e1f2 = mysql_dime_ganador ($clave, "c3");
	$equipo_g1h2 = mysql_dime_ganador ($clave, "c4");

// Cruces que van por el lado derecho de la tabla
	$equipo_b1a2 = mysql_dime_ganador ($clave, "c5");
	$equipo_d1c2 = mysql_dime_ganador ($clave, "c6");

	$equipo_f1e2 = mysql_dime_ganador ($clave, "c7");
	$equipo_h1g2 = mysql_dime_ganador ($clave, "c8");

	print "<center><h2><b>Cuartos de Final</b></h2></center>";

	print "<center class=aviso>Los ganadores de la eliminatoria se han puesto aleatoriamente. Revisalos, corrigelos como quieras y pulsa <i>Aceptar</i>.</center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=cuartos>";
	print "<input type=hidden name=clave value=$clave>";

 	print "<table style='height: 400px' align=center border=0>";

// Primera fila de la tabla de cruces
  print "<tr><td>";
    dibuja_cruce (1, $equipo_a1b2, $equipo_c1d2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (3, $equipo_b1a2, $equipo_d1c2, "DERECHA");
  print "</td></tr>";

// Segunda fila de la tabla de cruces
  print "<tr><td>";
    dibuja_cruce (2, $equipo_e1f2, $equipo_g1h2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (4, $equipo_f1e2, $equipo_h1g2, "DERECHA");
  print "</td></tr>";

  print "</table>";

  print "<br>";
	print "<center><input type=submit value='Aceptar'>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
  print "</form>";

	foot();
}


/***********************************************************************************************************/
/* semifinal_form ($clave)                                                                                 */
/* Dibuja el formulario de cruces de semifinal.                                                            */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function semifinal_form ($clave) {
	cabecera();

// Cruces que van por el lado izquierdo de la tabla
	$semi1 = mysql_dime_ganador ($clave, "s1");
	$semi2 = mysql_dime_ganador ($clave, "s2");

// Cruces que van por el lado derecho de la tabla
	$semi3 = mysql_dime_ganador ($clave, "s3");
	$semi4 = mysql_dime_ganador ($clave, "s4");


	print "<center><h2><b>Cuartos de Final</b></h2></center>";

	print "<center class=aviso>Los ganadores de la eliminatoria se han puesto aleatoriamente. Revisalos, corrigelos como quieras y pulsa <i>Aceptar</i>.</center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=semifinal>";
	print "<input type=hidden name=clave value=$clave>";

 	print "<table style='height: 400px' align=center border=0>";

// Primera fila de la tabla de cruces
  print "<tr><td>";
    dibuja_cruce (1, $semi1, $semi2, "IZQUIERDA");
  print "</td><td width=50></td><td>";
    dibuja_cruce (2, $semi3, $semi4, "DERECHA");
  print "</td></tr>";

  print "</table>";

  print "<br>";
	print "<center><input type=submit value='Aceptar'>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
  print "</form>";

	foot();
}


/***********************************************************************************************************/
/* final_form ($clave)                                                                                     */
/* Dibuja el formulario de la final.                                                                       */
/*   clave: Hash de la porra que se está generando.                                                        */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function final_form ($clave) {
	cabecera();

	$final1 = mysql_dime_ganador ($clave, "f1");
	$equipo1 = mysql_datos_de_equipo ($final1);
	$final2 = mysql_dime_ganador ($clave, "f2");
  $equipo2 = mysql_datos_de_equipo ($final2);
  $equipos_id = array ($final1, $final2);
	
	print "<center><h2><b>Final</b></h2></center>";
	print "<center class=aviso>El ganador de la eliminatoria se ha puesto aleatoriamente.";
	print "<center><h2><b>¡¡¡ Comprueba que seleccionas el campe&oacute;n de la SuperPorra en el desplegable y pulsa <i>Aceptar</i> !!!</b></h2></center>";

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=finalisima>";
	print "<input type=hidden name=clave value=$clave>";

	print "<table align=center border=0><tr>";
	print "<td><table border=0><tr><td align=center>";
	print "<img src=\"imagenes/".$equipo1 ['bandera'].".gif\" width=40></td>";
	print "</tr><tr><td align=center><b>".$equipo1 ['nombre']."</b></td></tr></table></td>";

  $select = "<select name=campeon style=\"width: 150px;\">";
  foreach ($equipos_id as $equipo_id) {
  $equipo = mysql_datos_de_equipo ($equipo_id);

  if ($equipo_id == $equipo_id [$azar])
    $select .= "<option value=$equipo_id selected>".$equipo ['nombre']."</option>";
  else
    $select .= "<option value=$equipo_id>".$equipo ['nombre']."</option>";
  }
  $select .= "</select>";

	print "<td><input type=input size=2 maxlength=2 name=goles_f1></td>";
	print "<td>$select</td>";
	print "<td><input type=input size=2 maxlength=2 name=goles_f2></td>";

	print "<td><table border=0><tr><td align=center>";
	print "<img src=\"imagenes/".$equipo2 ['bandera'].".gif\" width=40></td>";
	print "</tr><tr><td align=center><b>".$equipo2 ['nombre']."</b></td></tr></table></td>";
	print "</tr></table>";

	print "</table>";

  print "<br>";
	print "<center><input type=submit value='Aceptar'>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
  print "</form>";

	foot();
}


/***********************************************************************************************************/
/* dibuja_cruce ($id_cruce, $equipo1, $equipo2, $posicion)                                                 */
/* Dibuja el cruce entre dos equipos, así como el select para elegir el ganador de dicho cruce.            */
/*   id_cruce: Identificativo del cruce para poder identificar la seleccion realizada.                     */
/*   equipo1_id: Id del equipo 1 del cruce (con toda su información).                                      */
/*   equipo2_id: Id del equipo 2 del cruce (con toda su información).                                      */
/*   posicion: Posición en que se va a dibujar el cruce:                                                   */
/*        'IZQUIERDA': El cruce se va a dibujar a la izquierda de la tabla de cruces.                      */
/*        'DERECHA': El cruce se va a dibujar a la derecha de la tabla de cruces.                          */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function dibuja_cruce ($id_cruce, $equipo1_id, $equipo2_id, $posicion) {
  $equipo1 = mysql_datos_de_equipo ($equipo1_id);
  $equipo2 = mysql_datos_de_equipo ($equipo2_id);

  $equipos_id = array ($equipo1_id, $equipo2_id);

  $azar = seleccion_aleatoria (sizeof ($equipos_id));

  $select = "<select name=semi$id_cruce style=\"width: 150px;\">";
  foreach ($equipos_id as $equipo_id) {
    $equipo = mysql_datos_de_equipo ($equipo_id);

    if ($equipo_id == $equipo_id [$azar])
      $select .= "<option value=$equipo_id selected>".$equipo ['nombre']."</option>";
    else
      $select .= "<option value=$equipo_id>".$equipo ['nombre']."</option>";
  }
  $select .= "</select>";

  if ($posicion == "IZQUIERDA") {
    print "<table align=center border=0>";
    print "<tr><td>";

    print "<table align=center width=150 border=1>";
    print "<tr><td><img src=imagenes/".$equipo1['bandera'].".gif>&nbsp;&nbsp;".$equipo1['nombre']."</td></tr>";
    print "<tr><td><img src=imagenes/".$equipo2['bandera'].".gif>&nbsp;&nbsp;".$equipo2['nombre']."</td></tr>";
    print "</table>";

    print "</td><td>";

//    print "<select name=semi2 style=\"width: 150px;\"><option>$equipo1->nombre</option><option>$equipo2->nombre</option></select>";    
    print "$select";

    print "</td></tr>";
    print "</table>";
  }
    
  else if ($posicion == "DERECHA") {
    print "<table align=center border=0>";
    print "<tr><td>";

//    print "<select name=semi2 style=\"width: 150px;\"><option>$equipo1->nombre</option><option>$equipo2->nombre</option></select>";    
    print "$select";

    print "</td><td>";

    print "<table align=center width=150 border=1>";
    print "<tr><td><img src=imagenes/".$equipo1['bandera'].".gif>&nbsp;&nbsp;".$equipo1['nombre']."</td></tr>";
    print "<tr><td><img src=imagenes/".$equipo2['bandera'].".gif>&nbsp;&nbsp;".$equipo2['nombre']."</td></tr>";
    print "</table>";

    print "</td></tr>";
    print "</table>";
  }
}


/***********************************************************************************************************/
/* seleccion_aleatoria ($modulo)                                                                           */
/* Elige un número aleatorio entre 0 y $modulo.                                                            */
/*                                                                                                         */
/*   modulo: Módulo de la cantidad de opciones.                                                            */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function seleccion_aleatoria ($modulo) {
  $azar = (rand(0,9999999));
  srand((double)microtime()*1000000);
  return $azar%$modulo;
}


/***********************************************************************************************************/
/* genera_clave ()                                                                                         */
/* Generamos una clave md5 de forma aleatoria, que nos servirá para identificar de forma única a cada      */
/*   porra.                                                                                                */
/*                                                                                                         */
/*   clave: Valor generado por la función para identificar a cada porra.                                   */
/***********************************************************************************************************/
function genera_clave () {
	$clave = md5(rand(0,9999999));
	srand((double)microtime()*1000000);
	
	return $clave;
}



/********************/
function confirma_porra_1fase ($clave) {
  $SALTO_COLUMNA = 4;
  $salto = 0;

  $grupos = mysql_obtener_grupos ();

//	print "<P><center><h3><b>Primera Fase</b></h3></center>";
	print "<br>";
  print "<center><h2><b>Primera Fase</b></h2></center>";

  print "<table align=center cellspacing=10 border=0><tr>";
	foreach($grupos as $grupo) {
    $partidos = mysql_partidos_primera_fase_grupo ($grupo);

    print "<td>";

    print "<table align=center border=1>";
    print "<tr><td colspan=3><b>Grupo $grupo</b></td></tr>";
    
    foreach ($partidos as $partido) {
      $equipo1 = mysql_datos_de_equipo ($partido['equipo1']);
      $equipo2 = mysql_datos_de_equipo ($partido['equipo2']);

      $resultado_real = mysql_resultado_real_primera_fase ($partido['partidos_1fase_id']);
      $resultado_porra = mysql_resultado_partido ($clave, $partido['partidos_1fase_id']);

      if ($resultado_real == NULL) {
        $bgcolor = "";
        $font_color = "";
      }
      else if ($resultado_real != $resultado_porra) {
        $bgcolor = "bgcolor=red";
        $font_color = "<font color=white>";
      }
      else if ($resultado_real == $resultado_porra) {
        $bgcolor = "bgcolor=green";
        $font_color = "<font color=white>";
      }
      
  		print "<tr><td width=100px><img src=imagenes/".$equipo1['bandera'].".gif>&nbsp;&nbsp;".$equipo1['nombre']."</td><td width=100px><img src=imagenes/".$equipo2['bandera'].".gif>&nbsp;&nbsp;".$equipo2['nombre']."</td><td align=center $bgcolor width=20px>$font_color".$resultado_porra."</font></td></tr>";
    }
    
    print "</table>";

    print "</td>";

 		$salto++;
		if (!($salto%$SALTO_COLUMNA))
 			print "</tr><tr>";
  }
  print "</tr></table>";
}


/********************/
function confirma_porra_grupos ($clave) {
  $SALTO_COLUMNA = 4;
  $salto = 0;
  $grupos = mysql_obtener_grupos ();

//	print "<P><center><h3><b>Clasificaci&oacute;n de grupos</b></h3></center>";
	print "<center><h2><b>Clasificaci&oacute;n de Grupos</b></h2></center>";

  print "<table align=center cellspacing=10 border=0><tr>";
	foreach($grupos as $grupo) {
    $puestos = mysql_puestos_grupo ($grupo, $clave);
    $equipos = mysql_equipos_en_un_grupo ($grupo);

    $dibuja_banderas = "";
    foreach ($equipos as $equipo) {
      $dibuja_banderas .= "&nbsp;<img src=imagenes/".$equipo ['bandera'].".gif>&nbsp;";
    }

		print "<td>";
		print "<table width=100% border=1>";
		print "<tr><td align=center width=20><b>$grupo</b></td><td align=center>$dibuja_banderas</td></tr>";

    foreach ($puestos as $puesto) {
/* CAMBIO2: ESTA MODIFICACIÓN VIENE DEL CAMBIO "CAMBIO1" EN mysql.php (buscar ese cambio en mysql.php) DEBE SER LA SEGUNDA ASIGNACIÓN */
      $equipo = mysql_datos_de_equipo ($puesto ['id_equipo']);
//      $equipo = mysql_datos_de_equipo ($puesto ['equipo']);
      print "<tr><td>".$puesto ['puesto']."º</td><td align=center>".$equipo['nombre']."</td></tr>";
    }
    print "</table>";

 		$salto++;
		if (!($salto%$SALTO_COLUMNA))
 			print "</tr><tr>";
  }
  print "</table>";
}

 
function confirma_porra_fase_final ($clave) {
// Lado izquierdo
	$equipo_a1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "A", "1"));
	$equipo_b2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "B", "2"));

	$equipo_c1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "C", "1"));
	$equipo_d2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "D", "2"));

	$equipo_e1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "E", "1"));
	$equipo_f2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "F", "2"));

	$equipo_g1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "G", "1"));
	$equipo_h2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "H", "2"));

// Lado derecho
	$equipo_b1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "B", "1"));
	$equipo_a2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "A", "2"));

	$equipo_d1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "D", "1"));
	$equipo_c2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "C", "2"));

	$equipo_f1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "F", "1"));
	$equipo_e2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "E", "2"));

	$equipo_h1 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "H", "1"));
	$equipo_g2 = mysql_datos_de_equipo (mysql_dime_equipo ($clave, "G", "2"));

// Cruces que van por el lado izquierdo de la tabla
  $equipo_a1b2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c1"));
	$equipo_c1d2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c2"));
	$equipo_e1f2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c3"));
	$equipo_g1h2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c4"));

// Cruces que van por el lado derecho de la tabla
	$equipo_b1a2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c5"));
	$equipo_d1c2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c6"));
	$equipo_f1e2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c7"));
	$equipo_h1g2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "c8"));

// Cruces que van por el lado izquierdo de la tabla
	$semifinal1 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "s1"));
	$semifinal2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "s2"));

// Cruces que van por el lado derecho de la tabla
	$semifinal3 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "s3"));
	$semifinal4 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "s4"));

	$final1 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "f1"));
	$final2 = mysql_datos_de_equipo (mysql_dime_ganador ($clave, "f2"));

  $resultado_final = mysql_dime_final ($clave);
  $campeon = mysql_datos_de_equipo ($resultado_final['campeon']);

//	print "<center><h3><b>Fase Final</b></h3></center>";
	print "<center><h2><b>Fase Final</b></h2></center>";

  print "<table align=center cellspacing=10 border=0>";
  print "<tr align=center><td>Octavos</td><td>Cuartos</td><td>Semifinal</td><td>&nbsp;</td><td>Semifinal</td><td>Cuartos</td><td>Octavos</td></tr>";
  print "<tr><td>";
// Equipos de octavos
$bgcolor1 = mysql_compara_cruces ("o1", $equipo_a1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o2", $equipo_b2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_a1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_b2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o3", $equipo_c1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o4", $equipo_d2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_c1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_d2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o5", $equipo_e1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o6", $equipo_f2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_e1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_f2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o7", $equipo_g1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o8", $equipo_h2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_g1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_h2['nombre']."</td></tr>";
print "</table>";
  print "</td><td>";
// Hueco para cuartos
$bgcolor1 = mysql_compara_cruces ("c1", $equipo_a1b2['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("c2", $equipo_c1d2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_a1b2['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_c1d2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("c3", $equipo_e1f2['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("c4", $equipo_g1h2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_e1f2['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_g1h2['nombre']."</td></tr>";
print "</table>";

  print "</td><td>";
// Hueco para semifinales
$bgcolor1 = mysql_compara_cruces ("s1", $semifinal1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("s2", $semifinal2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$semifinal1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$semifinal2['nombre']."</td></tr>";
print "</table>";


/*
$bgcolor1 = mysql_compara_cruces ("s1", $equipo_e1f2['equipo_id']);
print "<table width=100px align=center border=1>";
print "<tr><td $bgcolor1>".$semifinal1['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("s2", $equipo_e1f2['equipo_id']);
print "<table width=100px align=center border=1>";
print "<tr><td $bgcolor1>".$semifinal2['nombre']."</td></tr>";
print "</table>";
*/
  print "</td><td>";
// Hueco para final
print "<table width=20px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";
  print "</td><td>";
// Hueco para semifinales
$bgcolor1 = mysql_compara_cruces ("s3", $semifinal3['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("s4", $semifinal4['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$semifinal3['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$semifinal4['nombre']."</td></tr>";
print "</table>";

/*
print "<table width=100px align=center border=1>";
$bgcolor1 = mysql_compara_cruces ("s3", $equipo_e1f2['equipo_id']);
print "<tr><td $bgcolor1>".$semifinal3['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

print "<table width=100px align=center border=1>";
$bgcolor1 = mysql_compara_cruces ("s4", $equipo_e1f2['equipo_id']);
print "<tr><td $bgcolor1>".$semifinal4['nombre']."</td></tr>";
print "</table>";
*/
  print "</td><td>";
// Hueco para cuartos
$bgcolor1 = mysql_compara_cruces ("c5", $equipo_b1a2['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("c6", $equipo_d1c2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_b1a2['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_d1c2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("c7", $equipo_f1e2['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("c8", $equipo_h1g2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_f1e2['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_h1g2['nombre']."</td></tr>";
print "</table>";
  print "</td><td>";
// Equipos de octavos
$bgcolor1 = mysql_compara_cruces ("o9", $equipo_b1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o10", $equipo_a2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_b1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_a2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o11", $equipo_d1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o12", $equipo_c2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_d1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_c2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o13", $equipo_f1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o14", $equipo_e2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_f1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_e2['nombre']."</td></tr>";
print "</table>";

print "<table width=100px align=center border=0>";
print "<tr><td>&nbsp;</td></tr>";
print "</table>";

$bgcolor1 = mysql_compara_cruces ("o15", $equipo_h1['equipo_id']);
$bgcolor2 = mysql_compara_cruces ("o16", $equipo_g2['equipo_id']);
if ($bgcolor1 == $bgcolor2)
  $bgcolor_borde = $bgcolor1;
else
  $bgcolor_borde = "bgcolor=red";
print "<table $bgcolor_borde width=100px align=center cellspacing=5 border=1>";
print "<tr><td $bgcolor1>".$equipo_h1['nombre']."</td></tr>";
print "<tr><td $bgcolor2>".$equipo_g2['nombre']."</td></tr>";
print "</table>";
  print "</td>";
  print "</tr>";

  print "</table>";



//	print "<center><h3><b>Final</b></h3></center>";
	print "<center><h2><b>Final</b></h2></center>";

  print "<table align=center cellspacing=10 border=0>";
  print "<tr valign=top><td><img src=imagenes/".$final1['bandera'].".gif width=20px></td><td width=150px align=left><h1>".$final1['nombre']."</h1></td><td><h1>".$resultado_final['goles1']."</h1></td><td><h1>&nbsp;-&nbsp;</h1></td><td><h1>".$resultado_final['goles2']."</h1></td><td width=150px align=right><h1>".$final2['nombre']."</h1></td><td><img src=imagenes/".$final2['bandera'].".gif width=20px></td></tr>";
  print "</table>";


//	print "<center><h3><b>Campe&oacute;n</b></h3></center>";
	print "<center><h2><b>Campe&oacute;n</b></h2></center>";

  print "<table align=center cellspacing=10 border=0>";
  print "<tr><td align=center><img src=imagenes/".$campeon['bandera'].".gif width=40px></td>";
  print "<tr><td align=center><h1>".$campeon['nombre']."</h1></td></tr>";
  print "</table>";
}


/*******************************/
function confirma_datos_porra ($clave) {
  cabecera();
	print "<center><h1><b>Confirmaci&oacute;n de datos</b></h1></center>";
	
  print "<center class=nota>Confirme los datos ahora o pulsa <i>Volver</i>.</center>";
  print "<center class=nota>Una vez confirmados los datos <b>¡¡ No podr&aacute;s dar marcha atr&aacute;s para modificarlos !!!</b> (Tendr&aacute;s que introducir una nueva porra entera).</center>";
  print "<br>";
	
  confirma_porra_1fase ($clave);
	confirma_porra_grupos ($clave);	
	confirma_porra_fase_final ($clave);

  print "<br>";
	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=confirma_final>";
	print "<input type=hidden name=clave value=".$clave.">";
	print "<center><input type=submit value='Confirmar'>&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";
	print "</form>";

  // Si confirmamos, nos muestra directamente la porra en PDF en otra ventana, y muestra la pagina inicial en la actual, aunque tengamos activo el bloqueo de pop-ups
//	print "<center><input type=button value='Confirmar' onClick=\"window.open ('escribe_pdf.php?clave=".$_GET['clave']."'); window.location='index.php'; \">&nbsp;&nbsp;<input type=button value='Volver' onClick=history.go(-1)></center>";

	foot();
}

/*******************************/
function porra_confirmada ($clave) {
  cabecera();
  $datos_porra = mysql_obten_datos_porra ($clave);

	print "<center><h1><b>Apuesta Confirmada</b></h1></center>";

  print "<center class=nota>Estos son los datos de la apuesta realizada:</center>";
  print "<br>";
  print "<table align=center border=1>";
  print "<tr><td>C&oacute;digo de Verificaci&oacute;n:</td><td>".$datos_porra ['clave']."</td></tr>";
  print "<tr><td>C&oacute;digo de Porra:</td><td>".$datos_porra ['participante_id']."</td></tr>";
  print "<tr><td>Nick:</td><td>".$datos_porra ['nick']."</td></tr>";
  print "<tr><td>Participante:</td><td>".$datos_porra ['nombre']."</td></tr>";
  print "<tr><td>Empleado TdE:</td><td>".$datos_porra ['nombre_tde']."</td></tr>";
  print "<tr><td>Tel&eacute;fono:</td><td>".$datos_porra ['extension']."</td></tr>";
  print "<tr><td>Correo Electr&oacute;nico:</td><td>".$datos_porra ['mail']."</td></tr>";
  print "</table>";

  print "<br>";
  print "<center class=nota>Puedes imprimir o guardar tu apuesta pulsando en <i>Apuesta en PDF</i>, o volver a la p&aacute;gina inicial pulsando en <i>P&aacute;gina Inicial</i></center>";
  print "<br><br>";


  // Si confirmamos, nos muestra directamente la porra en PDF en otra ventana, y muestra la pagina inicial en la actual, aunque tengamos activo el bloqueo de pop-ups
	print "<center><input type=button value='Apuesta en PDF' onClick=\"window.open ('escribe_pdf.php?clave=".$_GET['clave']."')\">&nbsp;&nbsp;<input type=button value='P&aacute;gina Inicial' onClick=\"window.location='index.php'\"></center>";

	foot();



}
?>