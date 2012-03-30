<?php

require("../fpdf.php");
include_once("../mysql.php");

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

//function imprime () {
$pdf=new PDF();
$pdf->SetDisplayMode (fullpage, single);
$pdf->AliasNbPages();
$pdf->SetTextColor (192, 59, 14);
$pdf->SetDrawColor (192, 59, 14);


//Primera página
$pdf->AddPage();


$grupos = mysql_obtener_grupos ();

$pdf->Image ('../imagenes/logo_cabecera.gif',10 ,10 , 190, '', 'GIF');
$pdf->Image ('../imagenes/logo_gota_agua.jpg',10 ,60 , 190, '', 'JPG');

// Título
$pdf->SetFont('Arial','',14);
$pdf->SetXY (10, 42);
$pdf->Cell (190, 10, "Apuesta Final", 0, 0, 'C');

$pdf->SetFont('Arial','',10);
$pdf->SetXY (10, 48);

$pdf->Cell (190, 10, "Rellena el borrador de tu apuesta para luego introducirla en la WEB.", 0, 0, 'C');

// Datos del apostante y de la porra
$pdf->SetXY (10, 70);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Nick:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 75);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Participante:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 80);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Empleado TdE:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 85);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Teléfono:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 90);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Correo Electrónico:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');


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

      $resultado = ""; // mysql_resultado_partido ($clave, $partido['partidos_1fase_id']);

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
//    $puestos = mysql_puestos_grupo ($grupo, $clave);

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
    
    $puestos = array ('1', '2', '3', '4');
    
    foreach ($puestos as $puesto) {
//      $equipo = mysql_datos_de_equipo ($puesto ['id_equipo']);
      
      $pdf->SetXY ($x, $y);
      $pdf->SetFont('Arial','', 8);
      $pdf->Cell (5, 5, $puesto."º", 1, 0, 'C');
      $pdf->Cell (20, 5, html_entity_decode ($equipo ['nombre']), 1, 0, 'L');

      $y = $y + 5;
    }
    $x = $x + $ANCHO_COLUMNA;
    $salto++;
  }



// SEGUNDA PAGINA
$pdf->AddPage();

$pdf->Image ('../imagenes/logo_cabecera.gif',10 ,10 , 190, '', 'GIF');
$pdf->Image ('../imagenes/logo_gota_agua.jpg',10 ,60 , 190, '', 'JPG');


// Datos del apostante y de la porra
$pdf->SetXY (10, 70);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Nick:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 75);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Participante:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 80);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Empleado TdE:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 85);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Teléfono:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');

$pdf->SetXY (10, 90);
$pdf->SetFont('Arial','B', 8);
$pdf->Cell (35, 5, "Correo Electrónico:", 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell (155, 5, "", 1, 0, 'L');


// CRUCES
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, 100);
$pdf->Cell (190, 10, "Cruces", 0, 0, 'C');


$MARGEN_Y = 105;

 $pdf->SetFont('Arial','',10);
// IZQUIERDA
// Octavos Izquierda
$pdf->SetXY (10, $MARGEN_Y);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10, "A1", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 5, 38, $MARGEN_Y + 5);

$pdf->SetXY (10, $MARGEN_Y + 12);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10, "B2", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 12);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 17, 38, $MARGEN_Y + 17);


$pdf->SetXY (10, $MARGEN_Y + 30);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10, "C1", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 30);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 35, 38, $MARGEN_Y + 35);

$pdf->SetXY (10, $MARGEN_Y + 42);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10, "D2", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 42);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10, "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 47, 38, $MARGEN_Y + 47);


$pdf->SetXY (10, $MARGEN_Y + 60);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "E1", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 60);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 65, 38, $MARGEN_Y + 65);

$pdf->SetXY (10, $MARGEN_Y + 72);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "F2", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 72);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 77, 38, $MARGEN_Y + 77);


$pdf->SetXY (10, $MARGEN_Y + 90);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "G1", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 90);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 95, 38, $MARGEN_Y + 95);

$pdf->SetXY (10, $MARGEN_Y + 102);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "H2", 1, 0, 'C');
$pdf->SetXY (10, $MARGEN_Y + 102);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (35, $MARGEN_Y + 107, 38, $MARGEN_Y + 107);


// Cuartos Izquierda
$pdf->Line (38, $MARGEN_Y + 20, 40, $MARGEN_Y + 20);
$pdf->Line (38, $MARGEN_Y + 5, 38, $MARGEN_Y + 20);
$pdf->SetXY (40, $MARGEN_Y + 15);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 20, 68, $MARGEN_Y + 20);
$pdf->Line (38, $MARGEN_Y + 32, 40, $MARGEN_Y + 32);
$pdf->Line (38, $MARGEN_Y + 47, 38, $MARGEN_Y + 32);
$pdf->SetXY (40, $MARGEN_Y + 27);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 32, 68, $MARGEN_Y + 32);

$pdf->Line (38, $MARGEN_Y + 80, 40, $MARGEN_Y + 80);
$pdf->Line (38, $MARGEN_Y + 65, 38, $MARGEN_Y + 80);
$pdf->SetXY (40, $MARGEN_Y + 75);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 80, 68, $MARGEN_Y + 80);
$pdf->Line (38, $MARGEN_Y + 92, 40, $MARGEN_Y + 92);
$pdf->Line (38, $MARGEN_Y + 107, 38, $MARGEN_Y + 92);
$pdf->SetXY (40, $MARGEN_Y + 87);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (65, $MARGEN_Y + 92, 68, $MARGEN_Y + 92);


// Semifinal Izquierda
$pdf->Line (68, $MARGEN_Y + 50, 70, $MARGEN_Y + 50);
$pdf->Line (68, $MARGEN_Y + 20, 68, $MARGEN_Y + 50);
$pdf->SetXY (70, $MARGEN_Y + 45);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (95, $MARGEN_Y + 50, 98, $MARGEN_Y + 50);
$pdf->Line (68, $MARGEN_Y + 62, 70, $MARGEN_Y + 62);
$pdf->Line (68, $MARGEN_Y + 92, 68, $MARGEN_Y + 62);
$pdf->SetXY (70, $MARGEN_Y + 57);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (95, $MARGEN_Y + 62, 98, $MARGEN_Y + 62);




// DERECHA
// Semifinal Derecha
$pdf->Line (140, $MARGEN_Y + 50, 142, $MARGEN_Y + 50);
$pdf->Line (142, $MARGEN_Y + 20, 142, $MARGEN_Y + 50);
$pdf->SetXY (115, $MARGEN_Y + 45);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (112, $MARGEN_Y + 50, 115, $MARGEN_Y + 50);
$pdf->Line (140, $MARGEN_Y + 62, 142, $MARGEN_Y + 62);
$pdf->Line (142, $MARGEN_Y + 92, 142, $MARGEN_Y + 62);
$pdf->SetXY (115, $MARGEN_Y + 57);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (112, $MARGEN_Y + 62, 115, $MARGEN_Y + 62);


// Cuartos Derecha
$pdf->Line (170, $MARGEN_Y + 20, 172, $MARGEN_Y + 20);
$pdf->Line (172, $MARGEN_Y + 5, 172, $MARGEN_Y + 20);
$pdf->SetXY (145, $MARGEN_Y + 15);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 20, 145, $MARGEN_Y + 20);
$pdf->Line (170, $MARGEN_Y + 32, 172, $MARGEN_Y + 32);
$pdf->Line (172, $MARGEN_Y + 47, 172, $MARGEN_Y + 32);
$pdf->SetXY (145, $MARGEN_Y + 27);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 32, 145, $MARGEN_Y + 32);

$pdf->Line (170, $MARGEN_Y + 80, 172, $MARGEN_Y + 80);
$pdf->Line (172, $MARGEN_Y + 65, 172, $MARGEN_Y + 80);
$pdf->SetXY (145, $MARGEN_Y + 75);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 80, 145, $MARGEN_Y + 80);
$pdf->Line (170, $MARGEN_Y + 92, 172, $MARGEN_Y + 92);
$pdf->Line (172, $MARGEN_Y + 107, 172, $MARGEN_Y + 92);
$pdf->SetXY (145, $MARGEN_Y + 87);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (142, $MARGEN_Y + 92, 145, $MARGEN_Y + 92);


// Octavos Derecha
$pdf->SetXY (175, $MARGEN_Y);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "B1", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 5, 175, $MARGEN_Y + 5);

$pdf->SetXY (175, $MARGEN_Y + 12);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "A2", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 12);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 17, 175, $MARGEN_Y + 17);


$pdf->SetXY (175, $MARGEN_Y + 30);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "D1", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 30);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 35, 175, $MARGEN_Y + 35);

$pdf->SetXY (175, $MARGEN_Y + 42);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "C2", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 42);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 47, 175, $MARGEN_Y + 47);


$pdf->SetXY (175, $MARGEN_Y + 60);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "F1", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 60);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 65, 175, $MARGEN_Y + 65);

$pdf->SetXY (175, $MARGEN_Y + 72);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "E2", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 72);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 77, 175, $MARGEN_Y + 77);


$pdf->SetXY (175, $MARGEN_Y + 90);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "H1", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 90);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 95, 175, $MARGEN_Y + 95);

$pdf->SetXY (175, $MARGEN_Y + 102);
$pdf->SetFont('Arial','B',20);
$pdf->SetTextColor (150, 150, 150);
$pdf->Cell (25, 10,  "G2", 1, 0, 'C');
$pdf->SetXY (175, $MARGEN_Y + 102);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor (192, 59, 14);
$pdf->Cell (25, 10,  "", 1, 0, 'C');
$pdf->Line (172, $MARGEN_Y + 107, 175, $MARGEN_Y + 107);



// Final
$pdf->Line (98, $MARGEN_Y + 50, 98, $MARGEN_Y + 120);
$pdf->Line (75, $MARGEN_Y + 120, 98, $MARGEN_Y + 120);
$pdf->Line (75, $MARGEN_Y + 120, 75, $MARGEN_Y + 125);
$pdf->SetXY (60, $MARGEN_Y + 125);
$pdf->Cell (30, 10,  "", 1, 0, 'C');
$pdf->Cell (10, 10,  "", 1, 0, 'C');

$pdf->SetXY (100, $MARGEN_Y + 125);
$pdf->Cell (10, 10, "-", 0, 0, 'C');

$pdf->Line (112, $MARGEN_Y + 50, 112, $MARGEN_Y + 120);
$pdf->Line (112, $MARGEN_Y + 120, 135, $MARGEN_Y + 120);
$pdf->Line (135, $MARGEN_Y + 120, 135, $MARGEN_Y + 125);
$pdf->SetXY (110, $MARGEN_Y + 125);
$pdf->Cell (10, 10,  "", 1, 0, 'C');
$pdf->Cell (30, 10,  "", 1, 0, 'C');


// Campeón
$pdf->SetFont('Arial','',12);
$pdf->SetXY (10, $MARGEN_Y + 140);
$pdf->Cell (190, 10, "Campeón", 0, 0, 'C');

$pdf->SetFont('Arial','',20);
$pdf->SetXY (80, $MARGEN_Y + 155);
$pdf->Cell (50, 15,  "", 1, 0, 'C');

$pdf->Output();

//}
?>
