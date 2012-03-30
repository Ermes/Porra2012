<?php

/***********************************************************************************************************/
/* grupos ()                                                                                               */
/* Funcion que muestra en una tabla los equipos por grupo.                                                 */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function grupos () {
  cabecera ();
  $SALTO_COLUMNA = 4;
	$salto = 0;
  
//  print "<div id=centrado>";

  $grupos = mysql_obtener_grupos ();

  print "<br><center><h1>Equipos por Grupo</h1></center><br>";

  print "<table align=center cellspacing=10 border=0>";
	print "<tr>";

	foreach ($grupos as $grupo) {
    $equipos = mysql_equipos_en_un_grupo ($grupo);
    
    print "<td><table border=1>";
    print "<tr><td colspan=2 width=130px><b>Grupo $grupo</b></td>";
		print "</tr>";

    foreach ($equipos as $equipo)
      print "<tr><td align=center><img src=imagenes/".$equipo ['bandera'].".gif></td><td> ".$equipo['nombre']."</td></tr>";
    
    print "</table></td>";

   $salto++;

		if (!($salto%$SALTO_COLUMNA))
			print "</tr><tr>";
	}
	print "</tr>";
	print "</table>";

  print "<br>";
	print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";

//	print "</div>";

  foot();
}


/***********************************************************************************************************/
/* partidos_1fase ()                                                                                       */
/* Funcion que muestra todos los partidos de primera fase, con su fecha, hora y resultado si ya se ha      */
/*     jugado.                                                                                             */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function partidos_1fase () {
	cabecera ();

  $grupos = mysql_obtener_grupos ();

//	print "<div id=centrado>";

  print "<br><center><h1>Partidos de la Fase de Grupos</h1></center><br>";

  print "<table width=600px align=center border=0>";
	print "<tr>";
	foreach($grupos as $grupo) {
		print "<td><table border=0>";

    $datos = mysql_partidos_primera_fase_grupo ($grupo);

		print "<tr><td width=30%><b><h2>Grupo $grupo</h2></b></td><td width=30%></td><td align=center width=18%><b>Fecha</b></td><td><b>Hora</b></td><td align=center width=6%><b>Resultado</b></td>";
		print "</tr>";
		
		foreach ($datos as $dato) {
      $equipo1 = mysql_datos_de_equipo ($dato['equipo1']);
      $equipo2 = mysql_datos_de_equipo ($dato['equipo2']);
      
			$hora_formateada = strftime("%H:%M", strtotime($dato['hora']));
			$fecha_normalizada = strftime("%d-%m-%Y", strtotime($dato['fecha']));
      
      printf ("<tr><td><img src=\"imagenes/%s.gif\"> %s</td><td><img src=\"imagenes/%s.gif\"> %s</td><td align=center><b>%s</b></td><td>%s</td>", $equipo1['bandera'], $equipo1['nombre'], $equipo2['bandera'], $equipo2['nombre'], $fecha_normalizada, $hora_formateada);

      if ($dato['resultado'] != NULL)
        printf ("<td align=center><b>%s</b></td></tr>", $dato['resultado']);
    }
		
		print "</table></td>";
			print "</tr><tr><td><br></td></tr><tr>";

	}
	print "</tr>";
	print "</table>";

  print "<br>";
	print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";
	
//	print "</div>";

  foot ();
}


/***********************************************************************************************************/
/* instruc_apu ()                                                                                          */
/* Funcion que muestra las instrucciones de la porra.                                                      */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function instruc_apu () {
	cabecera();

//	print "<div id=centrado>";

  print "<br><center><h1>Realización de apuestas</h1></center><br>";

	print "<h3>";
  print "<table width=600px align=center border=0>";
  print "<tr><td>&nbsp;</td></tr><tr><td>";
	print "Podeis bajaros los documentos con las normas y los formularios para rellenar las apuestas en los siguientes enlaces:";
	print "<br><br><img src=imagenes/pdf.gif border=0>&nbsp;&nbsp;<a href=documentos/Mundial_2010-Normas.pdf target='_blank'>Normas de la Porra del Mundial 2010 en formato PDF</a>";
	print "<br><br><img src=imagenes/pdf.gif border=0>&nbsp;&nbsp;<a href=documentos/porra_blanco.php target='_blank'>Formulario de apuestas en formato PDF</a>";
	print "<br><br><img src=imagenes/pdf.gif border=0>&nbsp;&nbsp;<a href=documentos/Total_Porras_Validadas.rar>Descarga de TODAS las Porras en formato PDF</a>";
	print "<br><br><a href=../lista.php>Listado Definitivo de Apuestas Validadas</a>";
	print "<br><br><a href=http://www.porra2010.com/foro_porra/viewtopic.php?f=5&t=2 target='_blank'>Consulta las Preguntas m&aacute;s frecuentes en nuestro foro</a>";
  print "<br><br><br><br><center><a href=http://www.adobe.com/go/getreader_es target='_blank'><img src=imagenes/get_adobe_reader.png border=0></a></center>";
	print "</td></tr>";
	print "</table>";
  print "</h3>";

  print "<br>";
	print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";

//	print "</div>";
	foot();
}


/***********************************************************************************************************/
/* sistema_puntos ()                                                                                          */
/* Funcion que muestra las instrucciones de la porra.                                                      */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function sistema_puntos () {
	cabecera();

  print "<br><center><h1>¿C&oacute;mo se obtienen puntos en la SuperPorra 2010?</h1></center><br>";

	print "<h3>";
  print "<table width=700px align=center border=0>";
  print "<tr><td>&nbsp;</td></tr>";
  print "<tr><td style='text-align: justify;'>";
  print "Aqu&iacute; se describe el mecanismo de obtenci&oacute;n de puntos para aquellos que deseen seguir su propia puntuaci&oacute;n. Este ser&aacute; el sistema de puntuaci&oacute;n que se utilizar&aacute; y que entendemos es conveniente detallar como se puntuar&aacute; a cada participante para tranquilidad de todos.";
  print "<ul>";
  print "<li><b>3 puntos</b> por cada partido de la primera fase cuyo pron&oacute;stico sea acertado. (Si, por ejemplo, se ha puesto Espa&ntilde;a-Suiza como 'X' y el resultado es Espa&ntilde;a 2 – Suiza 2, ya se habr&aacute;n sumado los 3 primeros puntos)."; 
  print "<li><b>5 puntos</b> por acertar los dos primeros de cada Grupo en el orden adecuado"; 
  print "<li><b>3 puntos</b> por acertar los dos primeros de cada Grupo, pero en el orden inverso.";
  print "<li><b>4 puntos</b> por cada equipo cuya posici&oacute;n final se acierte dentro de su Grupo."; 
  print "<li><b>2 puntos</b> por cada equipo que se pronostique que pasar&aacute; la primera Fase y lo consiga."; 

  print "<center>________________________________________________________________________________</center><br>";

  print "<li><b>5 puntos</b> por cada enfrentamiento de Octavos de Final que se acierte en el orden adecuado."; 
  print "<li><b>3 puntos</b> por cada enfrentamiento de Octavos de Final que se acierte y que <u>no</u> est&eacute; en el orden adecuado."; 
  print "<li><b>10 puntos</b> por acertar el vencedor de una eliminatoria de Octavos de Final cuyos equipos se hayan averiguado.";
  print "<li><b>5 puntos</b> por cada equipo que pase a Cuartos de Final, habi&eacute;ndolo pronosticado."; 

  print "<center>________________________________________________________________________________</center><br>";

  print "<li><b>12 puntos</b> por cada enfrentamiento de Cuartos de Final que se acierte en el orden adecuado."; 
  print "<li><b>7 puntos</b> por cada enfrentamiento de Cuartos de Final que se acierte y que <u>no</u> est&eacute; en el orden adecuado."; 
  print "<li><b>15 puntos</b> por acertar el vencedor de una eliminatoria de Cuartos de Final cuyos equipos se hayan averiguado.";
  print "<li><b>8 puntos</b> por cada equipo que pase a Semifinales, habi&eacute;ndolo pronosticado."; 

  print "<center>________________________________________________________________________________</center><br>";

  print "<li><b>15 puntos</b> por cada Semifinal que se acierte en el orden adecuado."; 
  print "<li><b>12 puntos</b> por cada Semifinal que se acierte y que <u>no</u> est&eacute; en el orden adecuado."; 
  print "<li><b>20 puntos</b> por acertar el vencedor de una Semifinal cuyos equipos se hayan averiguado.";
  print "<li><b>12 puntos</b> por cada equipo que pase a la Final, habi&eacute;ndolo pronosticado."; 

  print "<center>________________________________________________________________________________</center><br>";

  print "<li><b>15 puntos</b> si se acierta el resultado de la Final aunque no se acierten los equipos finalistas. No se tendr&aacute; en cuenta el orden del resultado (Por ejemplo, si se dice que la Final acabar&aacute; Polonia 1 - Italia 0 y el resultado es 1-0, aunque los finalistas sean otros, se obtienen 15 puntos m&aacute;s, asumi&eacute;ndose como resultado v&aacute;lido tanto 1-0 como 0-1 a la hora de puntuar)."; 
  print "<li><b>25 puntos</b> si se acierta el Campe&oacute;n de la COPA MUNDIAL DE LA FIFA SUDAFRICA 2010."; 
  print "</ul>";

  print "<br>";
  print "<u><b>NOTA:</b></u> Recordar que para el resultado de la Final se considerar&aacute; el que se d&eacute; al final de la pr&oacute;rroga, si es que &eacute;sta fuese necesaria.  En caso de que se crea que se decidir&aacute; por penaltis, se deber&aacute; indicar como resultado de la Final, aquel que se crea que se dar&aacute; al final de la pr&oacute;rroga e indicar que equipo vencer&aacute; a los penaltis.";
  print "<br><br>";

  print "En caso de empate a puntos, se considerar&aacute;n los siguientes criterios:";
  print "<br>";

  print "<ol>";
  print "<li>N&uacute;mero de partidos acertados en la Primera Fase del Mundial 2010.";
  print "<li>N&uacute;mero de equipos colocados en el orden correcto seg&uacute;n la clasificaci&oacute;n final de la Primera Fase del Mundial 2010.";
  print "<li>N&uacute;mero de equipos acertados en Octavos de Final.";
  print "<li>N&uacute;mero de equipos acertados en Cuartos de Final.";
  print "<li>N&uacute;mero de equipos acertados en Semifinales.";
  print "<li>Equipos acertados en la Final.";
  print "</ol>";

  print "En caso improbable que aun as&iacute; se empatara a puntos, se compartir&aacute; puesto y premio.";

 	print "</td></tr>";
	print "</table>";
  print "</h3>";

  print "<br>";
	print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";

	foot();
}


/***********************************************************************************************************/
/* consultar_form ()                                                                                       */
/* Funcion que muestra el formulario para hacer una consulta de porra.                                     */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function consultar_form () {
	cabecera();

  print "<br><center><h1>Consultar Porra</h1></center><br>";

/* PARA HABILITAR CUANDO EMPIECE LA PORRA */  
  print "<center>Puedes consultar por el n&uacute;mero de porra o por el nick indicado.</center>";


/* PARA HABILITAR ANTES DEL COMIENZO DE LA PORRA
  print "<center>Hasta el comienzo del Mundial, sólo podras consultar por tu Porra introduciendo su <b>C&oacute;digo de Validaci&oacute;n</b>.</center>";
*/  
	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value=consultar_now>";

  print "<table align=center border=0>";
  print "<tr><td align=center>B&uacute;squeda:</td><td><input type=input name=busca>&nbsp;&nbsp;<input type=submit value=Aceptar></td></tr>";
	print "<tr><td colspan=2>&nbsp;</td></tr>";
  print "</table>";

  print "<br>";
  print "<center><input type=reset value=Borrar>&nbsp;&nbsp;<input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";
  print "</form>";
	foot();
}


/***********************************************************************************************************/
/* consultar_now ($busca)                                                                                  */
/* Funcion que muestra el formulario para hacer una consulta de porra.                                     */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function consultar_now ($busca) {
  $datos_porra = mysql_buscar_porra ($busca);

  if ($datos_porra == '0')
    print_error ("Debe introducir un dato a buscar.");

  else if ($datos_porra == '1')
    print_error ("No se ha encontrado la porra.");

  else if ($datos_porra == '2')
    print_error ("Su porra no ha sido validada a&uacute;n por La Organizaci&oacute;n.");

  else {
		cabecera();

    $puntos_totales = $datos_porra['puntos'] + $datos_porra['puntos_grupo'] + $datos_porra['puntos_octavos'] + $datos_porra['puntos_cuartos'] + $datos_porra['puntos_semifinales'] + $datos_porra['final'];
    $clave = $datos_porra['clave']; 

    print "<table align=center border=0>";
		print "<tr><td><b>Nick:</b></td><td>".$datos_porra['nick']."</td></tr>";
		print "<tr><td><b>Participante:</b></td><td>".$datos_porra['nombre']."</td></tr>";
		print "<tr><td><b>Empleado TDE:</b></td><td>".$datos_porra['nombre_tde']."</td></tr>";
		print "<tr><td><b>N&uacute;mero de porra:</b></td><td>".$datos_porra['participante_id']."</td></tr>";
		print "<tr><td><b>Puntos grupos:</b></td><td>".$datos_porra['puntos_grupo']."</td></tr>";
		print "<tr><td><b>Puntos Totales:</b></td><td>$puntos_totales</td></tr>";
		print "</table>";
		print "<br>";
    
    if ($datos_porra['pagado'] == '1')
      print "<center><h1>Apuesta Validada</h1></center>";
    else
      print "<center><h1>Apuesta <b>NO</b> Validada</h1></center>";
    
		print "<br><br>";

	  confirma_porra_1fase ($datos_porra['clave']);
	  confirma_porra_grupos ($datos_porra['clave']);	
	  confirma_porra_fase_final ($datos_porra['clave']);

    print "<center><input type=button value='Volver' onClick=history.back()>&nbsp;&nbsp;<input type=button value='Imprimir' onClick=\"location.href='escribe_pdf.php?clave=$clave'\"></center>";
    
    foot();
  }
}


?>
