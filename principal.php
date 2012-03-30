<?php

/***********************************************************************************************************/
/* print_error ($mensaje)                                                                                  */
/* Funcion que muestra un mensaje de error.                                                                */
/*   mensaje: Mensaje de error que vamos a mostrar.                                                        */
/*                                                                                                         */
/* No devuelve ningún parámetro.                                                                           */
/***********************************************************************************************************/
function print_error($mensaje) {
	print "<center><h1><b>ERROR</b></h1></center>";
	print "<P><h3><Center>$mensaje</center></h3>";
	print "<center><input type=button value='Volver' onClick=history.back()></center>";
}

/////////////
function cabecera () {
  print "<html>";
  print "<head>";
  print "<title>.: SuperPorra Mundial Sud&aacute;frica 2010 :.</title>";
  print "<link href='estilo.css' type=text/css rel=stylesheet>";
  print "<link href='imagenes/favicon.ico' rel='shortcut icon'>";

	print "<body background=imagenes/fondo.jpg topmargin='0'>"; 

	print "<table cellspacing=0 cellpadding=0 width=100% align=center border=0>";
	print "<tr><td align=center><a href=http://es.fifa.com/worldcup/index.html target='_blank'><img src=./imagenes/logo_cabecera.gif border=0></a></td></tr>";                                                                                                                                                       
	print "</table>";

//	print "<div id=contenido>";

  print "<table cellspacing=0 cellpadding=0 width=100% align=center border=0>";
  print "<tr><td style='height: 50px'>&nbsp;</td></tr><tr><td>";
}


//////////////
function foot () {
  print "</td></tr>";
  print "</table>";
//	print "</div>";
  print "<br>";
  print "<center style='font-size: 12px;'>Organizaci&oacute;n Porra del Mundial. O.P.M. 2010 &copy;</center>";
  print "<center style='font-size: 12px;'>Consultas a: <a href=mailto:consultas@porra2010.com>consultas@porra2010.com</a></center>";
	print "</body>";
	print "</html>";
}


//////////////
function ppal () {
  cabecera ();

//	print "<P><center><blink><h1> Ha comenzado la Gran Porra de TDE</h1></center></blink>";

	print "<table width=100% align=center border=0>";
  print "<tr>";

// ============================================================

  print "<td valign=top align=left width=190px>";

  // CONTADOR DE VISITAS
  $visitas = mysql_numero_visitas ();

  print "<table width=100% align=center cellpadding=0 border=0>";
  print "<tr>";
  print "<td align=left width=20%>";
  print "<p><h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nº Visitas: $visitas</h2>";
  print "</td>";
  print "</tr>";
  print "</table>";

  print "<br>";

  // MENÚ
  $BORDER_COLOR = "#E04444";
  $BACKGROUND = "#FFDCDC";
  $ACTIVE_BACKGROUND = "#FF6D6D";
  
  print "<div id=menu>";
  print "<table valign=top width=100% cellpadding=0 cellspacing=1 border=0 bgcolor=$BORDER_COLOR>";
  print "<caption class=titl><b>Informaci&oacute;n SuperPorra</b>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=grupos>Grupos</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=partidos_1fase>Partidos Primera Fase</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=instruc_apu>Instrucciones y FAQs</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=sistema_puntos>Sistema de puntuaci&oacute;n</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=consultar>Consultar Porra</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=../foro_porra/index.php target='_blank'>El Foro de la Porra</a></td></tr>";
  print "</table>";

  // BOTÓN TEMPORAL  
//  print "<tr><td>";
//  print "<table align=center valign=top width=100% cellpadding=0 cellspacing=1 border=0 bgcolor=$BORDER_COLOR>";
//  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=documentos/Total_Porras_Validadas.rar><div style='font-size: 8px'>Descarga de TODAS las Porras (PDF)</div></a></td><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=lista.php><div style='font-size: 8px'>Listado Definitivo de Apuestas Validadas</div></a></td></tr>";
//  print "</table>";
//  print "</td></tr>";
//  print "</table>";

  print "<br>";


/* BOTON DE APUESTAS. DESHABILITAR CUANDO FINALICE EL PERIODO DE APUESTAS
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=index.php?op=apuesta><div style='font-size: 20px'>APOSTAR</div><div style='font-size: 15px'>(HASTA LAS 17:00 HORAS)</div></a></td></tr>";
*/  
  print "<table valign=top width=100% cellpadding=0 cellspacing=1 border=0 bgcolor=$BORDER_COLOR>";
/* PARA HABILITAR CUANDO EMPIECE LA PORRA */ 
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=clasif_ext.html>Clasificacion Online</a></td></tr>";
  print "</table>";

  print "<br>";
    

  // REPARTO DINERO
  $participantes = mysql_participantes ();
  $p1 = $participantes * 5 * 0.5;
  $p2 = $participantes * 5 * 0.3;
  $p3 = $participantes * 5 * 0.18;
  $p4 = $participantes * 5 * 0.02;
  print "<table width=95% valign=top align=center cellpadding=0 border=0>";
  print "<tr><td><b>Nº Participantes</b></td><td><b>$participantes</b></td></tr>";
  print "<tr><td><div align=left>1er Puesto</div></td><td><div align=right>$p1 €</div></td></tr>";
  print "<tr><td><div align=left>2er Puesto</div></td><td><div align=right>$p2 €</div></td></tr>";
  print "<tr><td><div align=left>3er Puesto</div></td><td><div align=right>$p3 €</div></td></tr>";
  print "<tr><td><div align=left>Cuchara de palo</div></td><td><div align=right>$p4 €</div></td></tr>";
  print "</table>";

  print "<center>_________________________</center>";
  print "<br>";

  /* ESTO DEBERÍA BORRARSE AL COMENZAR LA PORRA
  $pendientes = mysql_porras_pendientes ();
  $p1 = $pendientes * 5 * 0.5;
  $p2 = $pendientes * 5 * 0.3;
  $p3 = $pendientes * 5 * 0.18;
  $p4 = $pendientes * 5 * 0.02;
  
  print "<table width=95% valign=top align=center cellpadding=0 border=0>";
  print "<tr><td><b>Pendientes de Pago</b></td><td><b>$pendientes</b></td></tr>";
  print "<tr><td><div align=left>1er Puesto</div></td><td><div align=right>$p1 €</div></td></tr>";
  print "<tr><td><div align=left>2er Puesto</div></td><td><div align=right>$p2 €</div></td></tr>";
  print "<tr><td><div align=left>3er Puesto</div></td><td><div align=right>$p3 €</div></td></tr>";
  print "<tr><td><div align=left>Cuchara de palo</div></td><td><div align=right>$p4 €</div></td></tr>";
  print "</table>";
  */

  // BOTÓN TEMPORAL  
  print "<div id=menu>";
  print "<table align=center valign=top width=100% cellpadding=0 cellspacing=1 border=0 bgcolor=$BORDER_COLOR>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=documentos/Total_Porras_Validadas.rar>Descarga de TODAS las Porras (PDF)</a></td></tr>";
  print "<tr><td bgcolor=$BACKGROUND onMouseOver=\"this.style.background='$ACTIVE_BACKGROUND';\" onMouseOut=\"this.style.background='$BACKGROUND';\"><a href=lista.php>Listado Definitivo de Apuestas Validadas</a></td></tr>";
  print "</table>";
  print "</div>";

  print "</td>";

// ============================================================

  print "<td valign=top>";


  /* PARA MOSTRAR ANTES DE EMPEZAR EL MUNDIAL; PARTIDOS AMISTOSOS 
  // MARQUESINA INFORMATIVA
  print "<table width=90% align=center cellpadding=0 border=0>";
  print "<tr><td>";
  print "<marquee behavior=scroll direction=left scrollamount=3 height=20 onmouseover='this.stop()' onmouseout='this.start()'>";
  print "Amistosos:&nbsp;&nbsp;";
  mysql_imprimir_amistosos ();
  print "</marquee></td></tr>";
  print "</table>";
  */

  // CUENTA ATRÁS
  /*$FICHERO = "./imagenes/cuenta_atras.swf";
  $ANCHO = "400";//"320";
  $ALTO = "94";//"75";
  
  print "<center>";
  print "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' width='$ANCHO' height='$ALTO'>";
  print "<param name='movie' value='$FICHERO' />";
  print "<param name='quality' value='high' />";
  print "<embed src='$FICHERO' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='$ANCHO' height='$ALTO'></embed>";
  print "</object>";
  print "</center>";

  print "<br><br>";
  */  

  // ESTADISTICAS
  /* ESTO DEBERÍA ESTAR DESHABILITADO ANTES DE EMPEZAR LA PORRA, Y SÓLO VALE PARA LA FASE DE QUINIELAS
  $conexion = mysql_conectar_bbdd ();
  $consulta_partidos_hoy = mysql_query ("select * from partidos_1fase where fecha=curdate() order by hora asc;");
  $consulta_partidos_manana = mysql_query ("select * from partidos_1fase where fecha=curdate()+1 order by hora asc;");
  mysql_cerrar_bbdd ($conexion);
  $hoy = date ("d/m/Y");
  $manana = date ("d/m/Y", strtotime ("+1 day"));

  print "<table align=center width=650px border=0><tr>";
  print "<td width=50% valign=top>";
  
  print "<div align=center style='font-size: 14px'><b>$hoy</b></div>";
  print "<table align=center width=90% border=1>";
  print "<tr><td>&nbsp;</td><td align=center><b>1</b></td><td align=center><b>X</b></td><td align=center><b>2</b></td></tr>";

  while ($datos_partidos_hoy = mysql_fetch_object ($consulta_partidos_hoy, MYSQL_ASSOC)) {
    $equipo1 = mysql_datos_de_equipo ($datos_partidos_hoy->equipo1);
    $equipo2 = mysql_datos_de_equipo ($datos_partidos_hoy->equipo2);
    $resultado = $datos_partidos_hoy->resultado;
    
    if ($resultado == '1') {
      $font_color = "<font color=white>";
      $BGROUND_1 = "bgcolor=green";
      $BGROUND_X = "bgcolor=red"; 
      $BGROUND_2 = "bgcolor=red";
    }
    else if ($resultado == 'X') {
      $font_color = "<font color=white>";
      $BGROUND_1 = "bgcolor=red";
      $BGROUND_X = "bgcolor=green"; 
      $BGROUND_2 = "bgcolor=red"; 
    }
    else if ($resultado == '2') {
      $font_color = "<font color=white>";
      $BGROUND_1 = "bgcolor=red";
      $BGROUND_X = "bgcolor=red"; 
      $BGROUND_2 = "bgcolor=green"; 
    }
    else {
      $font_color = "<font color=black>";
      $BGROUND_1 = "";
      $BGROUND_X = ""; 
      $BGROUND_2 = ""; 
    }

    $conexion = mysql_conectar_bbdd ();
    $apuestas_1 = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_hoy->partidos_1fase_id and resultado='1' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    $apuestas_X = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_hoy->partidos_1fase_id and resultado='X' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    $apuestas_2 = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_hoy->partidos_1fase_id and resultado='2' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    mysql_cerrar_bbdd ($conexion);

    print "<tr><td><b>".$equipo1['nombre']." - ".$equipo2['nombre']."</b><font style='font-size: 10px'>&nbsp;(".date ('H:i', strtotime ($datos_partidos_hoy->hora)).")</font></td><td align=center $BGROUND_1>$font_color$apuestas_1</font></td><td align=center $BGROUND_X>$font_color$apuestas_X</font></td><td align=center $BGROUND_2>$font_color$apuestas_2</font></td></tr>";
  }
  print "</table>";

  print "</td><td width=50% valign=top>";

  print "<div align=center style='font-size: 14px'><b>$manana</b></div>";
  print "<table align=center valign=top width=90% border=1>";
  print "<tr><td>&nbsp;</td><td align=center><b>1</b></td><td align=center><b>X</b></td><td align=center><b>2</b></td></tr>";

  while ($datos_partidos_manana = mysql_fetch_object ($consulta_partidos_manana, MYSQL_ASSOC)) {
    $equipo1 = mysql_datos_de_equipo ($datos_partidos_manana->equipo1);
    $equipo2 = mysql_datos_de_equipo ($datos_partidos_manana->equipo2);

    $conexion = mysql_conectar_bbdd ();
    $apuestas_1 = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_manana->partidos_1fase_id and resultado='1' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    $apuestas_X = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_manana->partidos_1fase_id and resultado='X' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    $apuestas_2 = mysql_num_rows (mysql_query ("select apuesta_1fase.partido,apuesta_1fase.resultado from apuesta_1fase,participante where apuesta_1fase.partido=$datos_partidos_manana->partidos_1fase_id and resultado='2' and apuesta_1fase.clave=participante.clave and participante.pagado='1';"));
    mysql_cerrar_bbdd ($conexion);

    print "<tr><td><b>".$equipo1['nombre']." - ".$equipo2['nombre']."</b><font style='font-size: 10px'>&nbsp;(".date ('H:i', strtotime ($datos_partidos_manana->hora)).")</font></td><td align=center>$apuestas_1</td><td align=center>$apuestas_X</td><td align=center>$apuestas_2</td></tr>";
  }
  print "</table>";

  print "</tr></table>";
  */

  /* ESTO DEBERÍA ESTAR DESHABILITADO ANTES DE EMPEZAR LA PORRA, Y SÓLO VALE PARA LA FASE DE CRUCES */
/*  $fase = "f";
  $partido1 = "f1";
  $partido2 = "f1";
  $partido3 = "f2";
  $partido4 = "f2";
  $equipo1 = mysql_datos_de_equipo (3);
  $equipo2 = mysql_datos_de_equipo (17);
  $equipo3 = mysql_datos_de_equipo (13);
  $equipo4 = mysql_datos_de_equipo (29);
  //$hoy = date ("d/m/Y");
  $hoy = "06/07/2010";
  $manana = "07/07/2010";
  $conexion = mysql_conectar_bbdd ();
  $apuestas_equipo1_pasa = mysql_num_rows (mysql_query ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo1['equipo_id']." and apuesta_fase_final.partido='$partido1' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo1_pasa_descolocado = mysql_num_rows (mysql_query  ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo1['equipo_id']." and apuesta_fase_final.partido like '$fase%' and apuesta_fase_final.partido!='$partido1' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo1_no_pasa = 511 - $apuestas_equipo1_pasa - $apuestas_equipo1_pasa_descolocado;
  $apuestas_equipo2_pasa = mysql_num_rows (mysql_query ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo2['equipo_id']." and apuesta_fase_final.partido='$partido2' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo2_pasa_descolocado = mysql_num_rows (mysql_query  ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo2['equipo_id']." and apuesta_fase_final.partido like '$fase%' and apuesta_fase_final.partido!='$partido2' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo2_no_pasa = 511 - $apuestas_equipo2_pasa - $apuestas_equipo2_pasa_descolocado;
  $apuestas_equipo3_pasa = mysql_num_rows (mysql_query ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo3['equipo_id']." and apuesta_fase_final.partido='$partido3' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo3_pasa_descolocado = mysql_num_rows (mysql_query  ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo3['equipo_id']." and apuesta_fase_final.partido like '$fase%' and apuesta_fase_final.partido!='$partido3' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo3_no_pasa = 511 - $apuestas_equipo3_pasa - $apuestas_equipo3_pasa_descolocado;
  $apuestas_equipo4_pasa = mysql_num_rows (mysql_query ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo4['equipo_id']." and apuesta_fase_final.partido='$partido4' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo4_pasa_descolocado = mysql_num_rows (mysql_query  ("select * from apuesta_fase_final,participante where apuesta_fase_final.equipo=".$equipo4['equipo_id']." and apuesta_fase_final.partido like '$fase%' and apuesta_fase_final.partido!='$partido4' and apuesta_fase_final.clave=participante.clave and participante.pagado='1';"));
  $apuestas_equipo4_no_pasa = 511 - $apuestas_equipo4_pasa - $apuestas_equipo4_pasa_descolocado;
  mysql_cerrar_bbdd ($conexion);
*/
  $equipo1 = mysql_datos_de_equipo (17);
  $equipo2 = mysql_datos_de_equipo (29);
  $hoy = "11/07/2010";
  $conexion = mysql_conectar_bbdd ();
  $campeon_1 = mysql_num_rows (mysql_query ("select * from apuesta_final,participante where apuesta_final.campeon=".$equipo1['equipo_id']." and apuesta_final.clave=participante.clave and participante.pagado='1'"));
  $campeon_2 = mysql_num_rows (mysql_query ("select * from apuesta_final,participante where apuesta_final.campeon=".$equipo2['equipo_id']." and apuesta_final.clave=participante.clave and participante.pagado='1'"));
  mysql_cerrar_bbdd ($conexion);

  print "<table align=center width=300px border=0><tr>";
  print "<td width=50% valign=top>";
  
  print "<table align=center width=90% border=1>";
  print "<tr><td colspan=4 align=center style='font-size: 14px'><b>$hoy</b></td></tr>";
  print "<tr><td><b>Equipo</b></td><td align=center><b>Apuestas por Campe&oacute;n</b></td></tr>";
  
  print "<tr><td><b>".$equipo1['nombre']."</b></td><td align=center>$campeon_1</td></tr>";
  print "<tr><td><b>".$equipo2['nombre']."</b></td><td align=center>$campeon_2</td></tr>";

  print "</table>";

  print "</td>";
  print "</tr></table>";
    
  print "<br>";

  
  print "<br>";

  // NOTICIAS
  $noticias = mysql_muestra_noticias ();
  $i = 0;
  print "<table width=600px align=\"center\" border=0>";
	  print "<div id=\"noticias\"";
  print "<caption class=titl>Noticias</caption>";

  if (!$noticias) {
    print "<tr><td></td></tr>";
  }
  else {
    foreach ($noticias as $noticia) {
      $fecha_formateada = strftime ('%d-%m-%Y', strtotime ($noticia['fecha']));

      if ($i == 0) {
        print "<tr height=12px><td bgcolor=#e04444 border=1><div style='font-size: 12px'><b>".$fecha_formateada." - ".$noticia['titulo']."<b></div></td></tr><tr><td></td></tr>";
  		  print "<tr><td valign=top><div style='font-size: 12px'>·&nbsp;&nbsp;".$noticia['texto']."</div></td></tr>";
      }
      else {
        print "<tr height=12px><td bgcolor=#e04444 border=1><b>".$fecha_formateada." - ".$noticia['titulo']."<b></td></tr><tr><td></td></tr>";
        print "<tr><td valign=top><i>·&nbsp;&nbsp;".$noticia['texto']."</i></td></tr>";	
      }
      $i++;
      print "<tr><td>&nbsp;</td></tr>";
    }
  }
    print "</div>";
	print "</table>";

  print "</td>";

  
// ============================================================

  print "<td valign=top align=right width=200px>";

  print "<table width=200px border=0>";

  // REDES SOCIALES
  print "<tr><td>";

  print "<table width=100% border=0>";
  print "<tr><td>Siguenos Tambi&eacute;n en:</td></tr>";
  print "<tr><td>";
  print "<a href=http://www.facebook.com/pages/SuperPorra-Mundial-2010/125354907490253 target='_blank'><img src=imagenes/facebook.png width=40 border=0></a>";
  print "&nbsp;&nbsp;";
//  print "<a href=><img src=imagenes/twitter.png width=40 border=0></a>";
  print "</td></tr>";
  print "</table>";

  
/* PARA HABILITAR CUANDO EMPIECE LA PORRA */ 
  // TOP 5 Y TOP -3
  $BACKGROUND = "#FF6D6D";
  
  $top5s = mysql_consulta_top5 ();
  $top3s = mysql_consulta_top_menos3 ();
   

  // TOP 5
  print "<tr><td>";

  print "<table width=100% border=0>";
  print "<tr><td align=center colspan=3 bgcolor=$BACKGROUND class=menu><b>Top Five</b></td></tr>";
  
  if (!$top5s) {
    // Si no hay ninguna porra, no hacemos nada
    print "</td></tr><tr><td>&nbsp;</td></tr>";
  }
  else {
    foreach ($top5s as $top5) {
      print "<tr><td>".$top5['puesto']."</td><td><a href=index.php?op=consultar_now&busca=".$top5['participante_id'].">".$top5['nick']."</a></td><td>".$top5['total']."</td></tr>";
    }
  }
  print "</table>";

  print "</td></tr><tr><td>&nbsp;</td></tr>";

  // TOP -3
  print "<tr><td>";

  print "<table width=100% border=0>";
  print "<tr><td align=center colspan=3 bgcolor=$BACKGROUND class=menu><b>Top Minus Three</b></td></tr>";
  
  if (!$top3s) {
    // Si no hay ninguna porra, no hacemos nada
    print "</td></tr><tr><td>&nbsp;</td></tr>";
  }
  else {
    foreach ($top3s as $top3) {
      print "<tr><td>".$top3['puesto']."</td><td><a href=index.php?op=consultar_now&busca=".$top3['participante_id'].">".$top3['nick']."</a></td><td>".$top3['total']."</td></tr>";
    }
  }
  print "</table>";

  print "</td></tr><tr><td>&nbsp;</td></tr>";


  // Estadísticas por ganador
  print "<tr><td>";

  $campeones = mysql_apuestas_por_campeon ();

	if ($campeones) {
    print "<table width=100% border=0>";
    print "<tr><td><b>Nº Apuestas por Campe&oacute;n</b></td></tr>";
    print "</table>";

  	print "<marquee behavior=scroll direction=up scrollamount=1 height=100 onmouseover='this.stop()' onmouseout='this.start()'>";

  	print "<table width=150 border=0>";
  	foreach ($campeones as $campeon) {
      print "<tr><td align=left>".$campeon['cuenta']."</td><td>".$campeon['nombre']."</td></tr>";
	  }
  	print "</table>";

  	print "</marquee>";

    print "</td></tr>";
    print "</table>";
  }


  print "</td>";
 
// ============================================================
  
  print "</tr></table>";

  foot ();
}



