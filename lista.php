<?php
include_once("./mysql.php");
include_once("./principal.php");

cabecera ();

print "<center><h2><b>Listado Definitivo de Apuestas Validadas</b></h2></center>";

print "<table align=center width=700px border=1>";
print "<tr><td><b>ID Porra</b></td><td><b>Nombre</b></td><td><b>Nick</b></td></tr>";

$conexion = mysql_conectar_bbdd ();
$consulta_participante = mysql_query ("select * from participante where pagado=1;");
while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
  print "<tr><td>".$datos_participante->participante_id."</td><td>".$datos_participante->nombre."</td><td>".$datos_participante->nick."</td></tr>";
}
print "</table>";

print "<br>";
print "<center><input type=button value='Volver' onClick=\"location.href='index.php'\"></center>";

mysql_cerrar_bbdd ($conexion);

foot ();
?>