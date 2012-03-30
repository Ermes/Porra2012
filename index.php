<?php
include_once("./principal.php");
include_once("./mysql.php");
include_once("./menu.php");
include_once("./apuestas.php");


$opcion = $_GET['op'];
switch($opcion) {
/******************************************************************************/
/******************************* Menu de Grupos *******************************/
/******************************************************************************/
	case grupos:
       grupos ();
		  break;

/******************************************************************************/
/************************ Menu de Partidos Primera Fase ***********************/
/******************************************************************************/
	case partidos_1fase:
       partidos_1fase();
			break;

/******************************************************************************/
/**************************** Menu de Instrucciones ***************************/
/******************************************************************************/
	case instruc_apu:
       instruc_apu();
			break;

/******************************************************************************/
/************************** Menu de Sistema de Puntos *************************/
/******************************************************************************/
	case sistema_puntos:
       sistema_puntos ();
			break;

/******************************************************************************/
/*************************** Menu Consulta de Porra ***************************/
/******************************************************************************/
	case consultar:
       consultar_form ();
			break;

/******************************************************************************/
/****************************** Menu de Apuestas ******************************/
/******************************************************************************/

/***************************** Formulario Inicial *****************************/
	case apuesta:
	     $clave = genera_clave ();
       apuesta_form_inicial ($clave);    
			break;

/*************************** Formulario Primera Fase **************************/
	case apuesta_1: 
       $error = mysql_inserta_apostante ($_GET['nick'], $_GET['nombre_apostante'], $_GET['nombre_tde'], $_GET['extension'], $_GET['mail'], $_GET['clave']);

       if ($error)
         print_error ($error);

       else 
         apuesta_1form ($_GET['clave']);
       
      break;

/************************ Formulario Puestos por Grupo ************************/
	case apuesta_2:

       $grupos = mysql_obtener_grupos ();
       $i = 1;
       foreach ($grupos as $grupo) { 
			   mysql_inserta_1fase ($_GET['partido'.$i], $_GET['partido'.($i + 1)], $_GET['partido'.($i + 2)], $_GET['partido'.($i + 3)], $_GET['partido'.($i + 4)], $_GET['partido'.($i + 5)], $grupo, $_GET['clave']);
         $i = $i + 6;
       }

			 apuesta_2form ($_GET['clave']);
			break;

/************************ Formulario Cruces de Octavos ************************/
	case apuesta_3: 
       $grupos = mysql_obtener_grupos ();

       foreach ($grupos as $grupo) {
         if (mysql_inserta_grupos ($_GET['clave'], $_GET['puesto1'.$grupo], $_GET['puesto2'.$grupo], $_GET['puesto3'.$grupo], $_GET['puesto4'.$grupo], $grupo)) {
           print_error ("¡¡Equipo duplicado en el Grupo ".$grupo."!!<br>No se pueden repetir equipos, reviselos");
           return;
         }
       }
       mysql_inserta_octavos ($_GET['clave'], $_GET['puesto1A'], $_GET['puesto2B'], $_GET['puesto1C'], $_GET['puesto2D'], $_GET['puesto1E'], $_GET['puesto2F'], $_GET['puesto1G'], $_GET['puesto2H'], $_GET['puesto1B'], $_GET['puesto2A'], $_GET['puesto1D'], $_GET['puesto2C'], $_GET['puesto1F'], $_GET['puesto2E'], $_GET['puesto1H'], $_GET['puesto2G']);	
			 octavos_form ($_GET['clave']);
			break;

/************************ Formulario Cruces de Cuartos ************************/
  case octavos:
			 mysql_inserta_cuartos ($_GET['clave'],$_GET['semi1'],$_GET['semi2'],$_GET['semi3'],$_GET['semi4'],$_GET['semi5'],$_GET['semi6'],$_GET['semi7'],$_GET['semi8']);
       cuartos_form ($_GET['clave']);
      break; 
		
/**************************** Formulario Semifinal ****************************/
	case cuartos:
			 mysql_inserta_semifinal ($_GET['clave'] ,$_GET['semi1'], $_GET['semi2'], $_GET['semi3'], $_GET['semi4']);
			 semifinal_form ($_GET['clave']);
			break;

/****************************** Formulario Final ******************************/
	case semifinal:
       mysql_inserta_final ($_GET['clave'], $_GET['semi1'], $_GET['semi2']);
			 final_form ($_GET['clave']);
			break;	

/***************************** Formulario Resumen *****************************/
	case finalisima: 
		   if (mysql_inserta_finalisima ($_GET['clave'], $_GET['campeon'], $_GET['goles_f1'], $_GET['goles_f2'])) {
			   print_error ("Debe introducir el resultado de la final");
			   return;
       }

       confirma_datos_porra ($_GET['clave']);
			break;

/******************************* Confirmar Porra ******************************/
	case confirma_final:
       if (mysql_confirma_porra ($_GET['clave'])) {
			   print_error ("Esta porra ya est&aacute; en nuestra base de datos<br>Debe rellenar el formulario de nuevo para asignar nuevo n&uacute;mero de porra ya que esta ya no es valida<br><a href='index.php'>[ Inicio ]</a>");
			   return;
       }
       else {
         // Abre una nueva ventana del explorador con la porra en PDF
//         print "<script>window.open ('./escribe_pdf.php?clave=".$_GET['clave']."');</script>";
         porra_confirmada ($_GET['clave']);
      
         // Abre la página inicial de la porra
//         error_pagina_bloqueada ($_GET['clave']);
//         ppal ();
       }
			break;
 
/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Muestra la clasificación total con los puntos obtenidos en cada una de las fases, 
//   así como el puesto anterior y un indicativo de si sube o si baja puestos.
// Este case no es necesario, ya que el link del menú llama a clasif_ext.html
	case clasificac_ion: clasif_ext.html;				
	//			break;


/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Consulta instantanea de una porra mediante la funcion consultar_now ().
	case consultar_now: consultar_now ($_GET['busca']);
			    break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Muestra un formulario con el número de porra, para consultar los puntos de dicha porra.
	case consulta_puntos: consulta_puntos_form();
				break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Consulta instantanea del número de puntos de una porra determinada.
//	case consulta_puntos_now: consulta_puntos($_GET["nporra"]);
//					break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Muestra la clasificación total con los puntos obtenidos en cada una de las fases.
// CREO QUE ESTA FUNCIÓN NO SE UTILIZA, YA QUE HA SIDO SUSTITUIDA POR clasificac_ion
//	case clasificacion_: clasificacion();
	//case clasificacion: ppal_actualizando();				
//				break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// Clasificación final en los grupos.
// NO SÉ SI SE UTILIZA ESTA FUNCIÓN.
//	case clasi_grupos: clasi_grupos();
//				break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
// NO SÉ SI SE UTILIZA ESTA FUNCIÓN.
//	case ppal2: require("principal2.php");
//			break;

/******************************************************************************/
/**************************** Clasificación OnLine ****************************/
/******************************************************************************/
  default: ppal();
			break;

}

?>
