<?php

//include "./actualiza_partidos.php";
//include "./puntos_grupos.php";
//include "./transpasa.php";
//include "../consultar.php";
//include "../confirma_def.php";
//include "../escribe_clasif_ext.php";

///////////////
include_once ("../mysql.php");
include_once ("../principal.php");


/////////////////////////////////
function principal () {
	cabecera();
  $grupos = mysql_obtener_grupos ();

print "<table align=center border=0>";

print "<tr valign=top><td>";
  // Formulario de Noticias
 	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value='confirma_noticia'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td colspan=2><center><h3>Noticias</h3></center></td></tr>";
  print "<tr><td>Fecha (dd-mm-aaaa):</td><td><input type=input name=fecha></td></tr>";
  print "<tr><td>Titulo:</td><td><input type=input name=titulo></td></tr>";
  print "<tr><td>Texto:</td><td><input type=input name=texto></td></tr>";
  print "<tr><td colspan=2>&nbsp;</td></tr>";
  print "<tr><td colspan=2><center><input type=submit value=Aceptar></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";


print "</td><td>";
  // Formulario de Partidos Amistosos
 	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value='confirma_amistoso'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td colspan=2><center><h3>Amistosos</h3></center></td></tr>";
  print "<tr><td>Texto:</td><td><input type=input name=texto></td></tr>";
  print "<tr><td colspan=2>&nbsp;</td></tr>";
  print "<tr><td colspan=2><center><input type=submit value=Aceptar></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";

print "</td></tr>";
print "<tr valign=top><td>";
  // Formulario de Porra para Imprimir
 	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value='imprimir_porra'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td colspan=2><center><h3>Imprimir Porra</h3></center></td></tr>";
  print "<tr><td>N&uacute;mero de Porra a Imprimir:</td><td><input type=input name=nporra></td></tr>";
  print "<tr><td colspan=2>&nbsp;</td></tr>";
  print "<tr><td colspan=2><center><input type=submit value=Aceptar></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";

print "</td><td>";
  // Formulario para Pagar
 	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value='confirma'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td colspan=2><center><h3>Pagar Porra</h3></center></td></tr>";
  print "<tr><td>N&uacute;mero de Porra a Pagar:</td><td><input type=input name=nporra></td></tr>";
  print "<tr><td colspan=2>&nbsp;</td></tr>";
  print "<tr><td colspan=2><center><input type=submit value=Aceptar></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";

print "</td></tr>";
print "<tr valign=top><td>";

  // Formulario Actualiza Resultados de Partidos
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center width=100% border=0>";
  print "<tr><td colspan=2><center><h3>Actualizar Resultados de Partidos</h3></center></td></tr>";
  print "<tr><td>";

  print "<form action=index.php method=GET>";
  print "<input type=hidden name=op value=actualiza_fecha>";
  print "<table align=center border=0>";
  print "<tr><td>fecha (dd-mm-aaaa): </td><td><input type=input name=fecha></td><td><input type=submit value=OK></td></tr>";
  print "</table>";
  print "</form>";

  print "</td></tr><tr><td>";

  $partidos = mysql_partidos_en_fecha ();
  if ($partidos == 0)
    print "<center><b>No se ha introducido ninguna fecha.</b></center>";
  else {
    print "<table align=center border=0>";
    print "<tr><td colspan=5><center><b>Partidos del d&iacute;a ".strftime ('%d-%m-%Y', strtotime ($partidos [0]['fecha']))."</b></center></td></tr>";
    foreach ($partidos as $partido) {
      $equipo1 = mysql_datos_de_equipo ($partido ['equipo1']);
      $equipo2 = mysql_datos_de_equipo ($partido ['equipo2']);

      if (!$partido ['resultado']) {
        print "<form action=index.php method=GET>";
        print "<input type=hidden name=partido value=".$partido ['partidos_1fase_id'].">";
        print "<input type=hidden name=op value=actualiza_partido>";
        print "<tr><td>".$equipo1 ['nombre']."</td><td align=center>-</td><td>".$equipo2 ['nombre']."</td><td>,&nbsp;Grupo: ".$partido ['grupo']."</td><td>&nbsp;&nbsp;<input type=input size=2 name=resultado maxlength=1>&nbsp;&nbsp;<input type=submit value=Aceptar></td></tr>";
        print "</form>";
      }
      else
        print "<tr><td>".$equipo1 ['nombre']."</td><td> - </td><td>".$equipo2 ['nombre']."</td><td>,&nbsp;Grupo: ".$partido ['grupo']."</td><td>&nbsp;&nbsp;-&nbsp;&nbsp;".$partido ['resultado']."</td></tr>";
    }
    print "</table>";
  }
  print "</td></tr>"; //<tr><td>&nbsp;</td></tr>";

  print "</table>";
  print "</td></tr>";
  print "</table>";


  // Formulario Actualizacion Puestos de Grupos
  $select = "<select name=grupo style='width: 150px;'>";
  foreach ($grupos as $grupo)
    $select .= "<option value=$grupo>Grupo $grupo</option>";
 	print "<form action=index.php method=GET>";
	print "<input type=hidden name=op value='apuesta_2'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td colspan=2><center><h3>Actualiza puestos de los Grupos</h3></center></td></tr>";
  print "<tr><td><center>$select</td><td><input type=submit value='Resultado Grupos'></center></td></tr>";
  print "<tr><td colspan=2>&nbsp;</td></tr>";
	print "<tr><td colspan=2><center><a href='index.php?op=traspasa_apuestas'>Transpasa a Fase Final</a></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";

print "</td><td>";

  // Formulario Calcular Puntos
 	print "<form action=index.php method=GET>";
//	print "<input type=hidden name=op value='actualiza_clasif'>";
  print "<table align=center width=400px border=1>";
  print "<tr><td>";
  print "<table align=center border=0>";
  print "<tr><td><center><h3>== Calcula puntos ==</h3></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_quiniela'>Actualizar Puntos Quiniela</a></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_por_grupo'>Actualizar Puntos por Grupo</a></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_octavos'>Actualizar Puntos de Octavos</a></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_cuartos'>Actualizar Puntos de Cuartos</a></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_semifinales'>Actualizar Puntos de Semifinales</a></center></td></tr>";
  print "<tr><td><center><a href='index.php?op=actualiza_puntos_final'>Actualizar Puntos de Final</a></center></td></tr>";
  print "<tr><td>&nbsp;</td></tr>";
  print "<tr><td><center><input type=submit value=Aceptar></center></td></tr>";
  print "</table>";
  print "</td></tr>";
  print "</table>";
  print "</form>";

  // Actualizar fichero de clasificacion
  print "<table align=center width=400px border=1>";
  print "<tr><td style='height: 60px'><center><a href='index.php?op=actualiza_clasif'>Actualizar Fichero de Clasificaci&oacute;n</a></center></td></tr>";
  print "</table>";

print "</td></tr>";
print "</table>";
}

////////////////////////////////////////
function confirma_noticia ($fecha, $titulo, $texto) {
	cabecera();
  mysql_confirma_noticia ($fecha, $titulo, $texto);

 	print "<center><h2>Noticia Confirmada:</h2></center>";
 	print "<br><center><b>".strftime('%Y-%m-%d', strtotime ($fecha))." - ".htmlentities ($titulo)."</b></center>";
 	print "<center>".htmlentities ($texto)."</center><br>";
  print "<center><input type=button value='P&aacute;gina Principal' onClick=\"window.location='index.php'\"></center>";
}


////////////////////////////////////////
function confirma_amistoso($texto) {
	cabecera();
  mysql_confirma_amistoso ($texto);
  
 	print "<center><h2>Amistoso Confirmado:</h2></center>";
 	print "<center>".htmlentities ($texto)."</center><br>";
  print "<center><input type=button value='P&aacute;gina Principal' onClick=\"window.location='index.php'\"></center>";
}


////////////////////////////////////////
function imprimir_porra ($nporra) {
	if ($nporra == '')
  	print_error ("Debe introducir un dato a buscar");
  
	if (($nporra != '') && (!is_numeric ($nporra))) 
		print_error("El n&uacute;mero de porra debe ser num&eacute;rico.");
  
  else {
    $datos = mysql_busca_porra ($nporra);
    // Abre una nueva ventana del explorador con la porra en PDF
    print "<script>window.open ('../escribe_pdf.php?clave=$datos->clave&impresa=organizacion');</script>";
    principal ();
  }
}


////////////////////////////////////////
function confirma_pagar_porra ($porra) {
  $datos = mysql_confirma_pagar_porra ($porra);

	if ($datos == 2)
		print_error ("El número de porra tiene que ser un número.");

	else if ($datos == 3)
		print_error ("Porra Erronea.");

  else if ($datos == 4) {
		print_error ("La porra ya esta marcada como pagada en la BD.");
// 	  print "<center><input type=button value='P&aacute;gina Principal' onClick=\"window.location='index.php'\"></center>";
  }

  else {
    cabecera ();
    print "<center><h2><b>Confirmar Datos</h2></b></center>";
    print "<form action=index.php method=GET>";
    print "<input type=hidden name=op value=pagado>";
    print "<input type=hidden name=clave value=".$datos->clave.">";

    print "<table align=center border=0>";
    print "<tr><td>Nombre:</td><td>".$datos->nombre."</td></tr>";
    print "<tr><td>Correo Electr&oacute;nico:</td><td>".$datos->mail."</td></tr>";
    print "<tr><td>Extension:</td><td>".$datos->extension."</td></tr>";
    print "<tr><td>Empleado TDE:</td><td>".$datos->nombre_tde."</td></tr>";
    print "<tr><td>Clave:</td><td>".$datos->clave."</td></tr>";
    print "<tr><td colspan=2>&nbsp;</td></tr>";
    
    print "<tr><td>¿Qui&eacuten gestiona el pago?</td><td>
             <select name=gestor>
               <option value=JuanLu>JuanLu</option>
               <option value=Manolo>Manolo</option>
               <option value=Oscar>Oscar</option>
               <option value=PeLuSa>PeLuSa</option>
             </selec></td></tr>";

    print "<tr><td colspan=2>&nbsp;</td></tr>";
    print "<tr><td colspan=2><center><input type=submit value=Pagar></center></td></tr>";
    print "</table>";
    
    print "</form>";
  }
}

/////////////////////
function envia_correo ($datos) {
$codigo_confirmacion_pago = crypt ($datos->clave);
$destinatario = "$datos->nombre <$datos->mail>"; 
$asunto = "Confirmación de pago de la Porra con ID $datos->participante_id"; 
$cuerpo = " 
<html> 
<head> 
   <title>Confirmación de pago de la Porra con ID $datos->participante_id</title> 
</head> 
<body> 
<h3>¡Hola $datos->nombre!</h3><br>
Confirmarte que hemos recibido el pago de tu porra con ID $datos->participante_id, y que ha quedado validada para participar en la SuperPorra 2010.<br><br>
Te enviamos los datos de esta porra para que los conserves y puedas consultar los resultados durante el transcurso del mundial:<br><br>
<table align=center border=0>
<tr><td><b>C&oacute;digo de Confirmaci&oacute;n de Pago:</b></td><td>$codigo_confirmacion_pago</td></tr>
<tr><td><b>C&oacute;digo de Verificaci&oacute;n:</b></td><td>$datos->clave</td></tr>
<tr><td><b>C&oacute;digo de Porra:</b></td><td>$datos->participante_id</td></tr>
<tr><td><b>Nick:</b></td><td>$datos->nick</td></tr>
<tr><td><b>Participante:</b></td><td>$datos->nombre</td></tr>
<tr><td><b>Empleado TdE:</b></td><td>$datos->nombre_tde</td></tr>
<tr><td><b>Tel&eacute;fono:</b></td><td>$datos->extension</td></tr>
<tr><td><b>Correo Electr&oacute;nico:</b></td><td>$datos->mail</td></tr>
</table> 
<br><br>
Esperemos que sigas participando con nosotros en esta y otras porras.<br><br>
Saludos y muchas gracias:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O.P.M.
</body> 
</html> 
"; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Organizacion Porra del Mundial <opm.porra2010@gmail.com>\r\n"; 

//direcciones que recibirán copia oculta 
$headers .= "Bcc: Organizacion Porra del Mundial <opm.porra2010@gmail.com>\r\n"; 

mail ($destinatario, $asunto, $cuerpo, $headers); 
}

/////////////////////
function pagar_porra ($clave, $gestor) {

	if (strlen ($clave) != 32 )
		print_error("Porra no V&aacute;lida.");

  $datos = mysql_pagar_porra ($clave, $gestor);
	
	if ($datos == 3)
		print_error ("Porra Erronea.");

  else if ($datos == 4)
		print_error ("Error al actualizar la porra.");

  else if ($datos->pagado == 1)
    print_error ("La porra n&uacute;mero $datos->participante_id est&aacute; marcada como pagada.");

  else {
	  cabecera();
	  
//	  $codigo_pago = crypt ($clave);
	  
	  print "<center><h2>La porra ha sido pagada con &eacute;xito.</h2></center>";
//	  print "<center>Su c&oacute;digo de pago es: $codigo_pago</center>";

    envia_correo ($datos);
    print "<center>Se ha enviado un correo de confirmaci&oacute;n a $datos->mail</center>";
 	  print "<center><input type=button value='P&aacute;gina Principal' onClick=\"window.location='index.php'\"></center>";
 	}
}


/////////////////////
function actualiza_resultado_partido ($partido, $resultado) {
  if (mysql_actualiza_resultado ($partido, $resultado))
    print_error ("Resultado <i>$resultado</i> no valido");
   else {
	    cabecera();
	    print "<h1><center>Resultado actualizado</center></h1>";
  	  print "<center><input type=button value='P&aacute;gina Principal' onClick=\"window.location='index.php'\"></center>";
  }
}


/////////////////////
function actualiza_2form ($grupo) {
  $puesto = 1;
  $equipos = mysql_equipos_en_un_grupo ($grupo);  

  $dibuja_banderas = "";
  $select = "";
  foreach ($equipos as $equipo) {
    $dibuja_banderas .= "&nbsp;<img src=../imagenes/".$equipo ['bandera'].".gif>&nbsp;";
    $select .= "<option value=".$equipo ['equipo_id'].">".$equipo ['nombre']."</option>";
  }

	cabecera();

	print "<form action=index.php method=GET>";
	print "<input type=hidden name=grupo value=$grupo>";
	print "<input type=hidden name=op value=resultado_2>";
  print "<table align=center border=0>";
  print "<tr><td>";

	print "<table border=1>";
	print "<tr><td align=center width=20><b>$grupo</b></td><td align=center>$dibuja_banderas</td></tr>";

  foreach ($equipos as $equipo) {
    print "<tr><td align=center>".$puesto."º</td><td><select name='puesto".$puesto."' style='width: 150px;'>$select</select></td></tr>";
    $puesto++;
  }
  print "</table>";

  print "</td></tr><tr><td>";
	print "<center><input type=submit value=Aceptar>&nbsp;<input type=reset value=Borrar></center>";
  print "</td></tr>";
	print "</form>";

	foot();
}










/*
function actualizar_puntos() {
	$con=mysql_connect("","porra","sup3rp0rr&");
	mysql_select_db("eurocopa",$con);
	$res=mysql_query("select * from participante where pagado=1;");


	cabecera();
	print "<center>";
	print "<P>Participante&nbsp;&nbsp;Partidos acertados&nbsp;&nbsp;Puntos";
	while($data=mysql_fetch_object($res)) {
		// $res_partido=mysql_query("select * from partidos_1fase where resultado!='NULL'");
		$puntos=0;
		// while ($data_1fase=mysql_fetch_object($res_partido)) {
			// $res_apuesta=mysql_query("select * from apuesta_1fase where partido=$data_1fase->partidos_1fase_id and clave='$data->clave';");
			// $apuesta=mysql_fetch_object($res_apuesta);
			// if ($data_1fase->resultado==$apuesta->resultado) {
			//	$puntos=$puntos+5;
			//}
		// }
		$res_partido=mysql_query("select count(*) from partidos_1fase as p, apuesta_1fase as a where p.partidos_1fase_id=a.partido and p.resultado!='NULL' and p.resultado=a.resultado and a.clave='$data->clave';");
		$num_partidos=mysql_fetch_array($res_partido);
		$puntos=5*$num_partidos[0];
		print "<BR>$data->nombre&nbsp;&nbsp;$num_partidos[0]&nbsp;&nbsp;$puntos</td></tr>";
		mysql_query("update participante set puntos=$puntos where clave='$data->clave';");
		flush();
		mysql_free_result($res_partido);
	}
	print "</center>";
	mysql_free_result($res);
	print "<center>Hecho!</center>";
	print "<center><a href=index.php>Principal</a></center>";
}
*/




$op = $_GET['op'];
switch($op) {

	case confirma_noticia:
       confirma_noticia ($_GET['fecha'], $_GET['titulo'], $_GET['texto']);
			break;
	
	case confirma_amistoso:
       confirma_amistoso ($_GET['texto']);
			break;			

	case confirma: 
       confirma_pagar_porra ($_GET['nporra']); 
			break;

	case imprimir_porra: 
			 imprimir_porra ($_GET['nporra']);
			break;

	case pagado: 
       pagar_porra ($_GET['clave'], $_GET['gestor']);
		  break;

	case actualiza_fecha:
       mysql_actualiza_fecha ($_GET['fecha']);
       principal();
			break;

	case actualiza_grupo:
       mysql_puntos_por_grupo ($_GET['grupo']);
      break;

	case actualiza_partido:
       actualiza_resultado_partido ($_GET['partido'], $_GET['resultado']);
			break;

	case apuesta_2:	// Colocamos el orden de los equipos dentro de su grupo
			 actualiza_2form ($_GET['grupo']);
			break;

  case resultado_2: // Actualizamos el orden de los equipos en su grupo.
      if (mysql_inserta_resultado_grupos ($_GET['puesto1'], $_GET['puesto2'], $_GET['puesto3'], $_GET['puesto4'], $_GET['grupo']))
        print_error ("Error en Grupo ".$_GET['grupo'].": ¡¡ No se pueden repetir equipos !!");
      else
        principal();
      break;

	case actualiza_clasif: mysql_escribe_clasificacion_en_HTML ();
				break;


/*
	case actualizar_puntos: actualizar_puntos();
				break;
	case actualiza_form: actualiza_form();
				break;
*/




  case actualiza_puntos_quiniela:
       mysql_actualiza_puntos_quiniela ();
      break;

  case actualiza_puntos_por_grupo:
//       $grupos = mysql_obtener_grupos ();
//       foreach ($grupos as $grupo)
//         mysql_actualiza_puntos_por_grupo ($grupo);
         mysql_actualiza_puntos_por_grupo ();
      break;

	case traspasa_apuestas:
			 mysql_traspasa_apuestas_fase_final ();
			break;
			
  case actualiza_puntos_octavos:
       mysql_actualiza_puntos_octavos ();
      break;

  case actualiza_puntos_cuartos:
       mysql_actualiza_puntos_cuartos ();
      break;

  case actualiza_puntos_semifinales:
       mysql_actualiza_puntos_semifinales ();
      break;

  case actualiza_puntos_final:
       mysql_actualiza_puntos_final ();
      break;

	default: principal();
				 break;
}

?>