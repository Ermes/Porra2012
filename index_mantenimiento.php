<?php
// index_bloqueo.php
include_once ("principal.php");
cabecera ();
  print "<br><br><br>";

	print "<table width=500px align=center border=0>";
  print "<caption class=titl>Informaci&oacute;n</caption>";

  print "<tr><td style='text-align: justify'>";
  print "<center><h2>Por mantenimiento, la porra estar&aacute inaccesible durante unos minutos.</h2></center>";
  print "<br><br>";
  print "<center><b><h2>Sentimos las molestias</h2></b></center>";
  print "</td></tr>";

  print "<tr><td>";
  print "<br><br><br>";
  print "</td></tr>";

  print "<tr><td>";
  // CUENTA ATRÁS
  $FICHERO = "./imagenes/cuenta_atras.swf";
  $ANCHO = "400";//"320";
  $ALTO = "94";//"75";

  print "<center>";
  print "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' width='$ANCHO' height='$ALTO'>";
  print "<param name='movie' value='$FICHERO' />";
  print "<param name='quality' value='high' />";
  print "<embed src='$FICHERO' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='$ANCHO' height='$ALTO'></embed>";
  print "</object>";
  print "</center>";

  print "</td></tr>";
  print "</table>";

foot ();
?>