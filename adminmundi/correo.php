<?php
include_once("../mysql.php");

if ($_POST['enviar'] == 1) {
$conexion = mysql_conectar_bbdd ();

  // Si enviamos a los que han pagado y a los que no
  if ($_POST['pagados'] && $_POST['no_pagados']) {
     print "pagados y no pagados<br><br>";
     $consulta = mysql_query ("select * from participante;");
  }

  // Si enviamos a los que han pagado
  else if ($_POST['pagados']) {
     print "solo pagados<br><br>";
     $consulta = mysql_query ("select * from participante where pagado='1';");
  }

  // Si enviamos a los que NO han pagado
  else if ($_POST['no_pagados']) {
     print "solo no pagados<br><br>";
     $consulta = mysql_query ("select * from participante where pagado='0';");
  }
     
  // Si enviamos a la gente de un fichero (en formato.............)
//  else if ($_POST['destino'] == 3)

  if ($consulta) {
  	while ($destino = mysql_fetch_array ($consulta, MYSQL_ASSOC)) {
  	  $destinatario = $destino ['nombre']." <".$destino ['mail'].">";
      enviar_correo ($destinatario, $_POST['asunto'], $_POST['cuerpo']);
    }
  }
  
  if ($_FILES['nombreFichero']['name'] != "" && $_FILES['nombreFichero']['size'] != 0){
    if (move_uploaded_file ($_FILES['nombreFichero']['tmp_name'], $_FILES['nombreFichero']['name'])) {
      $correosTemp =  file_get_contents ($_FILES['nombreFichero']['name']);
      $correos = explode (",", $correosTemp);
      for ($i=0; $i < count($correos); $i++) {
        enviar_correo ($correos[$i], $_POST['asunto'], $_POST['cuerpo']);
        sleep (10);
      }
    }
  }


  mysql_cerrar_bbdd ($conexion);

}
else
  formulario ();


function formulario () {
  print "<form enctype=\"multipart/form-data\" action=correo.php method=POST>";
  print "<input type=hidden name=enviar value=1>";
//  print "<input type=hidden name=destinatario value=fjgheras@gmail.com>";

  print "<table align=center border=1>";
  
  print "<tr><td>";
  print "<input type=checkbox name=pagados>&nbsp;Pagados";
  print "&nbsp;&nbsp;&nbsp;&nbsp;";
  print "<input type=checkbox name=no_pagados>&nbsp;No Pagados";
  print "&nbsp;&nbsp;&nbsp;&nbsp;";
  print "Cargar fichero:&nbsp;<input type=file name=nombreFichero>";
  print "</td></tr><tr><td>";
  print "Asunto:<br>";
  print "<input type=text name=asunto style='width: 700px' tabindex=1>";
  print "</td></tr><tr><td>";
  print "Cuerpo del mail:<br>";
  print "<textarea name=cuerpo rows=20 style='width: 700px; overflow: auto' tabindex=2></textarea>";
  print "</td></tr><tr><td>";
  print "<center><input type=submit value=Enviar>&nbsp;&nbsp;&nbsp;<input type=reset value=Borrar></center>";
  print "</td></tr>";
  print "</table>";

  print "</form>";
}

function enviar_correo ($destinatario, $asunto, $cuerpo) {
//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Organizacion Porra del Mundial 2010 <opm.porra2010@gmail.com>\r\n"; 

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: Consultas Porra 2010 <consultas@porra2010.com>\r\n"; 

//ruta del mensaje desde origen a destino 
//$headers .= "Return-path: holahola@desarrolloweb.com\r\n"; 

//direcciones que recibián copia 
//$headers .= "Cc: maria@desarrolloweb.com\r\n"; 

//direcciones que recibirán copia oculta 
//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 

mail ($destinatario, $asunto, nl2br ($cuerpo), $headers);
printf ("El correo se ha enviado correctamente a %s.<br>", $destinatario);
}

?>