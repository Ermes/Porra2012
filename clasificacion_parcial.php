<?php
include_once("./mysql.php");
include_once("./principal.php");

if (!$_GET['consulta']) {
cabecera ();

print "<center><h2><b>Clasificaci&oacute;n Personalizada</b></h2></center>";

print "<form action=clasificacion_parcial.php method=GET>";
print "<table align=center width=500px border=0>";
print "<tr><td align=center>Para realizar una consulta personalizada, introduce a continuaci&oacute;n todos los n&uacute;meros de porras que quieres consultar, separados por comas y pulsa <i>Aceptar</i>:</td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td align=center><input type=input name=consulta style='width: 450px'></td></tr>";
print "<tr><td>&nbsp;</td></tr>";
print "<tr><td><center><input type=submit value=Aceptar></center></td></tr>";

print "</form>";

foot ();
}

else {
cabecera ();
?>
<script language="JavaScript1.2" type="text/javascript">
function CreateBookmarkLink(url) {
  titulo="Clasificación Personalizada";
/*  url="http://porra2010.com/clasificacion_parcial.php?consulta=<?php $lista ?>"; */
  if (window.sidebar) { // Mozilla Firefox
    window.sidebar.addPanel(titulo, url,"");
  }
  else if( window.external ) { // IE
    window.external.AddFavorite( url, titulo);
  }
  else if(window.opera && window.print) { // Opera
  /**alert("Para agregar a tu pagina a sus favoritos presione control + D") }**/
  window.external.AddFavorite( url, titulo); }
}
</script>

<?php

$conexion = mysql_conectar_bbdd ();
$lista = str_replace (' ', '', $_GET['consulta']);
$porras = explode (',', $lista);

print "<center><h2><b>Clasificaci&oacute;n Personalizada</b></h2></center>";

print "<center>(Solamente se muestran las porras validadas por la Organizaci&oacute;n)</center>";

print "<table align=center width=700px border=1>";
print "<tr><td align=center><b>Puesto</b></td><td><b>Participante</b></td><td align=center><b>Total</b></td><td align=center>Puntos<br>Quiniela</td><td align=center>Puntos<br>Grupo</td><td align=center>Puntos<br>Octavos</td><td align=center>Puntos<br>Cuartos</td><td align=center>Puntos<br>Semifinales</td><td align=center>Puntos<br>Final</td><td align=center>Puesto<br>Anterior</td></tr>";

$i = 0;
$consulta = "";

foreach ($porras as $porra) {
$consulta .= "participante_id=$porra";
$i++;
if ($i < sizeof ($porras))
$consulta .= " or ";
}


$consulta_participante = mysql_query ("select * from participante where $consulta and pagado=1 order by puesto asc;");
while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
$puntos_total = $datos_participante->puntos+$datos_participante->puntos_grupo+$datos_participante->puntos_octavos+$datos_participante->puntos_cuartos+$datos_participante->puntos_semifinales+$datos_participante->puntos_final;
print "<tr><td align=center>".$datos_participante->puesto."</td><td><a href=index.php?op=consultar_now&busca=$datos_participante->participante_id>($datos_participante->participante_id)&nbsp;&nbsp;".$datos_participante->nick."</a></td><td align=center><b>$puntos_total</b></td><td align=center>".$datos_participante->puntos."</td><td align=center>".$datos_participante->puntos_grupo."</td><td align=center>".$datos_participante->puntos_octavos."</td><td align=center>".$datos_participante->puntos_cuartos."</td><td align=center>".$datos_participante->puntos_semifinales."</td><td align=center>".$datos_participante->puntos_final."</td><td align=center>".$datos_participante->puesto_anterior."</td></tr>";
}

print "</table>";

print "<br>";
print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\">&nbsp;&nbsp;&nbsp;<input type=button value='A&ntilde;adir esta consulta a Mis Favoritos' onClick=\"javascript:CreateBookmarkLink('http://porra2010.com/clasificacion_parcial.php?consulta=$lista')\"></center>";


mysql_cerrar_bbdd ($conexion);
foot ();
}

?>
