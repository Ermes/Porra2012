<?php
require("../fpdf.php");
include_once("../mysql.php");
include_once("../principal.php");

class PDF extends FPDF
{
  function Footer()
  {
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
  }
}


//$porras = array ('0ebc43b7ceff3394192f4f0c7d93b8d6','cde401d023b1c43b8f6ab37c4f87d891','86e30a68782f2bbb20697cc7755467cc','2cd6afecab6cf4f86b40d7b9ded667e0','36a0af9260b4c14921a828d4e6c256b3','afe6c39f0c7dfe322825973d6c9631fb');
$c = mysql_conectar_bbdd ();
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id<100 order by participante_id;");
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>99 and participante_id<200 order by participante_id;");
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>199 and participante_id<300 order by participante_id;");
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>299 and participante_id<400 order by participante_id;");
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>399 and participante_id<500 order by participante_id;");
//$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>499 and participante_id<600 order by participante_id;");
$consulta_participante = mysql_query ("select clave from participante where pagado=1 and participante_id>599 order by participante_id;");
while ($datos_participante = mysql_fetch_object ($consulta_participante)) {
  genera_PDFs ($datos_participante->clave, $impresa);
}
//mysql_cerrar_bbdd ($c);

print "<br>¡¡FINALIZADO!!";



/******************************/
function genera_PDFs ($clave, $impresa) {

//$clave = $_GET['clave'];
//$impresa = $_GET['impresa'];

//if ((mysql_confirma_porra ($clave)) && ($impresa != "organizacion")) {
//  print_error ("Esta porra ya est&aacute; en nuestra base de datos<br>Debe rellenar el formulario de nuevo para asignar nuevo n&uacute;mero de porra ya que esta ya no es valida<br><a href='index.php'>[ Inicio ]</a>");
//  return;

//}
//else {

$pdf = new PDF();
$pdf->SetDisplayMode (fullpage, single);
$pdf->AliasNbPages();
$pdf->SetTextColor (192, 59, 14);
$pdf->SetDrawColor (192, 59, 14);


//Primera página
$pdf->AddPage();

$grupos = mysql_obtener_grupos ();
$datos_porra = mysql_obten_datos_porra ($clave);

//PDF_escribe_cabecera ($pdf, $datos_porra, $impresa);
// Cabecera
$pdf->Image ('../imagenes/logo_cabecera.gif',10 ,10 , 190, '', 'GIF');
$pdf->Image ('../imagenes/logo_gota_agua.jpg',10 ,60 , 190, '', 'JPG');


// Título
$pdf->SetFont('Arial','',14);
$pdf->SetXY (10, 42);
$pdf->Cell (190, 10, "Apuesta Final", 0, 0, 'C');

$pdf->SetFont('Arial','',10);
$pdf->SetXY (10, 48);

if ($impresa == "organizacion")
$pdf->Cell (190, 10, "Porra impresa por la organización.", 0, 0, 'C');
else
$pdf->Cell (190, 10, "Esta es tu apuesta. Conservela para recordar sus resultados.", 0, 0, 'C');

// Datos del apostante y de la porra
$pdf->SetXY (10, 60);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Código de Verificación:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['clave'], 0, 0, 'L');

$pdf->SetXY (10, 65);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Código de Porra:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['participante_id'], 0, 0, 'L');

$pdf->SetXY (10, 70);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Nick:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nick'], 0, 0, 'L');

$pdf->SetXY (10, 75);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Participante:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nombre'], 0, 0, 'L');

$pdf->SetXY (10, 80);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Empleado TdE:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nombre_tde'], 0, 0, 'L');

$pdf->SetXY (10, 85);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Teléfono:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['extension'], 0, 0, 'L');

$pdf->SetXY (10, 90);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Correo Electrónico:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['mail'], 0, 0, 'L');

//$pdf->SetXY (130, 60);
//$pdf->SetFont('Arial','B', 8);
//$pdf->Cell (70, 5, "Firma aquí:", 1, 0, 'C');
//$pdf->SetXY (130, 65);
//$pdf->Cell (70, 30, "", 1, 0, 'C');

$MARGEN_Y = 110;
$MARGEN_X = 10;

$ANCHO_COLUMNA = 48;
$ANCHO_FILA = 45;

// Fase de Grupos
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, 100);
$pdf->Cell (190, 10, "Quiniela de Fase de Grupos", 0, 0, 'C');

 $SALTO_COLUMNA = 4;
 $salto = 0;

 $x = $MARGEN_X;
 
	foreach($grupos as $grupo) {
    $partidos = mysql_partidos_primera_fase_grupo ($grupo);

    if ($salto < $SALTO_COLUMNA)
      $y = $MARGEN_Y;
    else {
      $y = $MARGEN_Y + $ANCHO_FILA;
      if ($salto == $SALTO_COLUMNA)
        $x = $MARGEN_X;
    }

    $pdf->SetXY ($x, $y);
    $pdf->SetFont('Arial','B', 10);
    $pdf->Cell (45, 10, "Grupo $grupo:", 1, 0, 'L');

    $y = $y + 10;
    
    foreach ($partidos as $partido) {
      $equipo1 = mysql_datos_de_equipo ($partido['equipo1']);
      $equipo2 = mysql_datos_de_equipo ($partido['equipo2']);

      $resultado = mysql_resultado_partido ($clave, $partido['partidos_1fase_id']);

      $pdf->SetXY ($x, $y);
      $pdf->SetFont('Arial','', 8);

      $pdf->Cell (20, 5, html_entity_decode ($equipo1 ['nombre']), 1, 0, 'C');
      $pdf->Cell (20, 5, html_entity_decode ($equipo2 ['nombre']), 1, 0, 'C');
      $pdf->Cell (5, 5, $resultado, 1, 0, 'C');
      
      $y = $y + 5;
    }
    $x = $x + $ANCHO_COLUMNA;
    $salto++;
}

// Clasificación de Grupos
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, 200);
$pdf->Cell (190, 10, "Clasificación de Grupos", 0, 0, 'C');

$MARGEN_Y = 210;
$MARGEN_X = 47;

$ANCHO_COLUMNA = 30;
$ANCHO_FILA = 30;

 $SALTO_COLUMNA = 4;
 $salto = 0;

 $x = $MARGEN_X;
 
  foreach ($grupos as $grupo) {
    $puestos = mysql_puestos_grupo ($grupo, $clave);

    if ($salto < $SALTO_COLUMNA)
      $y = $MARGEN_Y;
    else {
      $y = $MARGEN_Y + $ANCHO_FILA;
      if ($salto == $SALTO_COLUMNA)
        $x = $MARGEN_X;
    }
    
    $pdf->SetXY ($x, $y);
    $pdf->SetFont('Arial','B', 8);
    $pdf->Cell (25, 7, "Grupo $grupo:", 1, 0, 'L');

    $y = $y + 7;
    foreach ($puestos as $puesto) {
      $equipo = mysql_datos_de_equipo ($puesto ['id_equipo']);
      
      $pdf->SetXY ($x, $y);
      $pdf->SetFont('Arial','', 8);
      $pdf->Cell (5, 5, $puesto ['puesto']."º", 1, 0, 'C');
      $pdf->Cell (20, 5, html_entity_decode ($equipo ['nombre']), 1, 0, 'L');

      $y = $y + 5;
    }
    $x = $x + $ANCHO_COLUMNA;
    $salto++;
  }









// SEGUNDA PAGINA

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

$pdf->AddPage();

//PDF_escribe_cabecera ($pdf, $datos_porra, $impresa);
// Cabecera
$pdf->Image ('../imagenes/logo_cabecera.gif',10 ,10 , 190, '', 'GIF');
$pdf->Image ('../imagenes/logo_gota_agua.jpg',10 ,60 , 190, '', 'JPG');


// Título
$pdf->SetFont('Arial','',14);
$pdf->SetXY (10, 42);
$pdf->Cell (190, 10, "Apuesta Final", 0, 0, 'C');

$pdf->SetFont('Arial','',10);
$pdf->SetXY (10, 48);

if ($impresa == "organizacion")
$pdf->Cell (190, 10, "Porra impresa por la organización.", 0, 0, 'C');
else
$pdf->Cell (190, 10, "Esta es tu apuesta. Conservela para recordar sus resultados.", 0, 0, 'C');

// Datos del apostante y de la porra
$pdf->SetXY (10, 60);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Código de Verificación:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['clave'], 0, 0, 'L');

$pdf->SetXY (10, 65);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Código de Porra:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['participante_id'], 0, 0, 'L');

$pdf->SetXY (10, 70);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Nick:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nick'], 0, 0, 'L');

$pdf->SetXY (10, 75);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Participante:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nombre'], 0, 0, 'L');

$pdf->SetXY (10, 80);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Empleado TDE:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['nombre_tde'], 0, 0, 'L');

$pdf->SetXY (10, 85);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Teléfono:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['extension'], 0, 0, 'L');

$pdf->SetXY (10, 90);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Correo Electrónico:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (80, 5, $datos_porra ['mail'], 0, 0, 'L');

//$pdf->SetXY (130, 60);
//$pdf->SetFont('Arial','B', 8);
//$pdf->Cell (70, 5, "Firma aquí:", 1, 0, 'C');
//$pdf->SetXY (130, 65);
//$pdf->Cell (70, 30, "", 1, 0, 'C');

// CRUCES
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, 100);
$pdf->Cell (190, 10, "Cruces", 0, 0, 'C');


$MARGEN_Y = 105;

 $pdf->SetFont('Arial','',10);
// IZQUIERDA
// Octavos Izquierda
$pdf->SetXY (10, $MARGEN_Y);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, html_entity_decode ($equipo_a1 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 5, 38, $MARGEN_Y + 5);
$pdf->SetXY (10, $MARGEN_Y + 12);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, html_entity_decode ($equipo_b2 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 17, 38, $MARGEN_Y + 17);

$pdf->SetXY (10, $MARGEN_Y + 30);
$pdf->Cell (25, 10, html_entity_decode ($equipo_c1 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 35, 38, $MARGEN_Y + 35);
$pdf->SetXY (10, $MARGEN_Y + 42);
$pdf->Cell (25, 10, html_entity_decode ($equipo_d2 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 47, 38, $MARGEN_Y + 47);

$pdf->SetXY (10, $MARGEN_Y + 60);
$pdf->Cell (25, 10, html_entity_decode ($equipo_e1 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 65, 38, $MARGEN_Y + 65);
$pdf->SetXY (10, $MARGEN_Y + 72);
$pdf->Cell (25, 10, html_entity_decode ($equipo_f2 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 77, 38, $MARGEN_Y + 77);

$pdf->SetXY (10, $MARGEN_Y + 90);
$pdf->Cell (25, 10, html_entity_decode ($equipo_g1 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 95, 38, $MARGEN_Y + 95);
$pdf->SetXY (10, $MARGEN_Y + 102);
$pdf->Cell (25, 10, html_entity_decode ($equipo_h2 ['nombre']), 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 107, 38, $MARGEN_Y + 107);


// Cuartos Izquierda
$pdf->Line (38, $MARGEN_Y + 20, 40, $MARGEN_Y + 20);
$pdf->Line (38, $MARGEN_Y + 5, 38, $MARGEN_Y + 20);
$pdf->SetXY (40, $MARGEN_Y + 15);
$pdf->Cell (25, 10, html_entity_decode ($equipo_a1b2 ['nombre']), 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 20, 68, $MARGEN_Y + 20);
$pdf->Line (38, $MARGEN_Y + 32, 40, $MARGEN_Y + 32);
$pdf->Line (38, $MARGEN_Y + 47, 38, $MARGEN_Y + 32);
$pdf->SetXY (40, $MARGEN_Y + 27);
$pdf->Cell (25, 10, html_entity_decode ($equipo_c1d2 ['nombre']), 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 32, 68, $MARGEN_Y + 32);

$pdf->Line (38, $MARGEN_Y + 80, 40, $MARGEN_Y + 80);
$pdf->Line (38, $MARGEN_Y + 65, 38, $MARGEN_Y + 80);
$pdf->SetXY (40, $MARGEN_Y + 75);
$pdf->Cell (25, 10, html_entity_decode ($equipo_e1f2 ['nombre']), 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 80, 68, $MARGEN_Y + 80);
$pdf->Line (38, $MARGEN_Y + 92, 40, $MARGEN_Y + 92);
$pdf->Line (38, $MARGEN_Y + 107, 38, $MARGEN_Y + 92);
$pdf->SetXY (40, $MARGEN_Y + 87);
$pdf->Cell (25, 10, html_entity_decode ($equipo_g1h2 ['nombre']), 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 92, 68, $MARGEN_Y + 92);


// Semifinal Izquierda
$pdf->Line (68, $MARGEN_Y + 50, 70, $MARGEN_Y + 50);
$pdf->Line (68, $MARGEN_Y + 20, 68, $MARGEN_Y + 50);
$pdf->SetXY (70, $MARGEN_Y + 45);
$pdf->Cell (25, 10, html_entity_decode ($semifinal1 ['nombre']), 1, 0, 'C');
$pdf->Line (95, $MARGEN_Y + 50, 98, $MARGEN_Y + 50);
$pdf->Line (68, $MARGEN_Y + 62, 70, $MARGEN_Y + 62);
$pdf->Line (68, $MARGEN_Y + 92, 68, $MARGEN_Y + 62);
$pdf->SetXY (70, $MARGEN_Y + 57);
$pdf->Cell (25, 10, html_entity_decode ($semifinal2 ['nombre']), 1, 0, 'C');
$pdf->Line (95, $MARGEN_Y + 62, 98, $MARGEN_Y + 62);




// DERECHA
// Semifinal Derecha
$pdf->Line (140, $MARGEN_Y + 50, 142, $MARGEN_Y + 50);
$pdf->Line (142, $MARGEN_Y + 20, 142, $MARGEN_Y + 50);
$pdf->SetXY (115, $MARGEN_Y + 45);
$pdf->Cell (25, 10, html_entity_decode ($semifinal3 ['nombre']), 1, 0, 'C');
$pdf->Line (112, $MARGEN_Y + 50, 115, $MARGEN_Y + 50);
$pdf->Line (140, $MARGEN_Y + 62, 142, $MARGEN_Y + 62);
$pdf->Line (142, $MARGEN_Y + 92, 142, $MARGEN_Y + 62);
$pdf->SetXY (115, $MARGEN_Y + 57);
$pdf->Cell (25, 10, html_entity_decode ($semifinal4 ['nombre']), 1, 0, 'C');
$pdf->Line (112, $MARGEN_Y + 62, 115, $MARGEN_Y + 62);


// Cuartos Derecha
$pdf->Line (170, $MARGEN_Y + 20, 172, $MARGEN_Y + 20);
$pdf->Line (172, $MARGEN_Y + 5, 172, $MARGEN_Y + 20);
$pdf->SetXY (145, $MARGEN_Y + 15);
$pdf->Cell (25, 10, html_entity_decode ($equipo_b1a2 ['nombre']), 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 20, 145, $MARGEN_Y + 20);
$pdf->Line (170, $MARGEN_Y + 32, 172, $MARGEN_Y + 32);
$pdf->Line (172, $MARGEN_Y + 47, 172, $MARGEN_Y + 32);
$pdf->SetXY (145, $MARGEN_Y + 27);
$pdf->Cell (25, 10, html_entity_decode ($equipo_d1c2 ['nombre']), 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 32, 145, $MARGEN_Y + 32);

$pdf->Line (170, $MARGEN_Y + 80, 172, $MARGEN_Y + 80);
$pdf->Line (172, $MARGEN_Y + 65, 172, $MARGEN_Y + 80);
$pdf->SetXY (145, $MARGEN_Y + 75);
$pdf->Cell (25, 10, html_entity_decode ($equipo_f1e2 ['nombre']), 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 80, 145, $MARGEN_Y + 80);
$pdf->Line (170, $MARGEN_Y + 92, 172, $MARGEN_Y + 92);
$pdf->Line (172, $MARGEN_Y + 107, 172, $MARGEN_Y + 92);
$pdf->SetXY (145, $MARGEN_Y + 87);
$pdf->Cell (25, 10, html_entity_decode ($equipo_h1g2 ['nombre']), 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 92, 145, $MARGEN_Y + 92);


// Octavos Derecha
$pdf->SetXY (175, $MARGEN_Y);
$pdf->Cell (25, 10, html_entity_decode ($equipo_b1 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 5, 175, $MARGEN_Y + 5);
$pdf->SetXY (175, $MARGEN_Y + 12);
$pdf->Cell (25, 10, html_entity_decode ($equipo_a2 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 17, 175, $MARGEN_Y + 17);

$pdf->SetXY (175, $MARGEN_Y + 30);
$pdf->Cell (25, 10, html_entity_decode ($equipo_d1 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 35, 175, $MARGEN_Y + 35);
$pdf->SetXY (175, $MARGEN_Y + 42);
$pdf->Cell (25, 10, html_entity_decode ($equipo_c2 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 47, 175, $MARGEN_Y + 47);

$pdf->SetXY (175, $MARGEN_Y + 60);
$pdf->Cell (25, 10, html_entity_decode ($equipo_f1 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 65, 175, $MARGEN_Y + 65);
$pdf->SetXY (175, $MARGEN_Y + 72);
$pdf->Cell (25, 10, html_entity_decode ($equipo_e2 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 77, 175, $MARGEN_Y + 77);

$pdf->SetXY (175, $MARGEN_Y + 90);
$pdf->Cell (25, 10, html_entity_decode ($equipo_h1 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 95, 175, $MARGEN_Y + 95);
$pdf->SetXY (175, $MARGEN_Y + 102);
$pdf->Cell (25, 10, html_entity_decode ($equipo_g2 ['nombre']), 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 107, 175, $MARGEN_Y + 107);



// Final
$pdf->Line (98, $MARGEN_Y + 50, 98, $MARGEN_Y + 120);
$pdf->Line (75, $MARGEN_Y + 120, 98, $MARGEN_Y + 120);
$pdf->Line (75, $MARGEN_Y + 120, 75, $MARGEN_Y + 125);
$pdf->SetXY (60, $MARGEN_Y + 125);
$pdf->Cell (30, 10, html_entity_decode ($final1 ['nombre']), 1, 0, 'C');
$pdf->Cell (10, 10, html_entity_decode ($resultado_final ['goles1']), 1, 0, 'C');

$pdf->SetXY (100, $MARGEN_Y + 125);
$pdf->Cell (10, 10, "-", 0, 0, 'C');

$pdf->Line (112, $MARGEN_Y + 50, 112, $MARGEN_Y + 120);
$pdf->Line (112, $MARGEN_Y + 120, 135, $MARGEN_Y + 120);
$pdf->Line (135, $MARGEN_Y + 120, 135, $MARGEN_Y + 125);
$pdf->SetXY (110, $MARGEN_Y + 125);
$pdf->Cell (10, 10, html_entity_decode ($resultado_final ['goles2']), 1, 0, 'C');
$pdf->Cell (30, 10, html_entity_decode ($final2 ['nombre']), 1, 0, 'C');


// Campeón
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, $MARGEN_Y + 140);
$pdf->Cell (190, 10, "Campeón", 0, 0, 'C');

$pdf->Image ("../imagenes/".html_entity_decode ($campeon ['bandera']).".gif", 100 ,$MARGEN_Y + 150 , '10', '', 'GIF');
$pdf->SetFont('Arial','',20);
$pdf->SetXY (90, $MARGEN_Y + 160);
$pdf->Cell (30, 10, html_entity_decode ($campeon ['nombre']), 0, 0, 'C');




// Resguardo
//$tamaño = array ('210', '81');
//$pdf->AddPage('', $tamaño);
$pdf->AddPage();

$MARGEN = 0;
for ($i = 1; $i <= 2; $i++) {
$pdf->Image ('../imagenes/logo_gota_agua.jpg', 150, 10 + $MARGEN, 50, '', 'JPG');

$pdf->SetXY (10, 10 + $MARGEN);
$pdf->SetFont('Arial','BU',18);
$pdf->Cell (100, 5, html_entity_decode ("Resguardo de pago de la SuperPorra 2010"), 0, 0, 'L');

$pdf->SetXY (15, 25 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Código de Verificación:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['clave'], 0, 0, 'L');

$pdf->SetXY (15, 30 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Código de Porra:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['participante_id'], 0, 0, 'L');

$pdf->SetXY (15, 35 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Nick:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['nick'], 0, 0, 'L');

$pdf->SetXY (15, 40 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Participante:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['nombre'], 0, 0, 'L');

$pdf->SetXY (15, 45 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Empleado TDE:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['nombre_tde'], 0, 0, 'L');

$pdf->SetXY (15, 50 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Teléfono:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['extension'], 0, 0, 'L');

$pdf->SetXY (15, 55 + $MARGEN);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell (50, 5, "Correo Electrónico:", 0, 0, 'L');
$pdf->SetFont('Arial','', 12);
$pdf->Cell (80, 5, $datos_porra ['mail'], 0, 0, 'L');

$pdf->Line (15, 70 + $MARGEN, 195, 70 + $MARGEN);
$pdf->SetXY (15, 70 + $MARGEN);
$pdf->SetFont('Arial','B', 12);

if ($i == 1)
$pdf->Cell (50, 5, "Ejemplar para el Apostante", 0, 0, 'L');
else
$pdf->Cell (50, 5, "Ejemplar para la Organización", 0, 0, 'L');

$pdf->Line (15, 75 + $MARGEN, 195, 75 + $MARGEN);
$pdf->Line (0, 85 + $MARGEN, 210, 85 + $MARGEN);

$MARGEN = 85;
}
$j = 4 - strlen ($datos_porra ['participante_id']);
for ($i = 1; $i <= $j; $i++)
  $ceros .= '0';
$pdf->Output("../documentos/Porras_PDF/".$ceros.$datos_porra ['participante_id'].".pdf", F);

print "Generado fichero ".$ceros.$datos_porra ['participante_id'].".pdf con la porra ".$datos_porra ['participante_id']."<br>";

}
?>
