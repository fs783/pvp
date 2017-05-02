<?php
session_start();

/*
*		FabioS 
*		Controller Ajax
*		begin: 18/04/2017 - 17:08
*
*		last revision:	  
*/	
require_once 'main.pvp.php';

// require('Mobile_Detect.php');

// cerco il file di Wordpress per includere tutti i suoi metodi
// require( '../../../../wp-load.php' );

	$pvp = new PVP(); // istanzio la classe


	$step = $_POST['step'];
	$valore = $_POST['valore'];
	
	
			function main($step, $valore)	{
				
				global $pvp;
				
				switch ($step) {
					
					case 'calcolaTotale':
 					echo $pvp->calcoloTotale();			
					break;
					
					case 'home':
 					echo $pvp->inizioConfigurazione($valore);			
					break;
					
					case '1':
					echo $pvp->step1_telaio($valore);
					break; 
					
					case '1-altezza':
					echo $pvp->step1_dimensione($valore);
					break;
					
					case '1-dimensione':
					echo $pvp->step1_tamponamento($valore);
					break;
					
					case '1-tamponamento':
					echo $pvp->step1_optional($valore);
					break;
					
					case '2':
					echo $pvp->step2_dimensione($valore);
					break;
					
					case '2-insert':
					echo $pvp->step2_insert($valore);
					break;
					
					case '3':
					echo $pvp->step3_dimensione($valore);
					break;
					
					case '3-insert':
					echo $pvp->step3_insert($valore);
					break;
					 
					case '4':
					echo $pvp->step4_dimensione($valore);
					break;
					
					case '4-extra':
					echo $pvp->step4_extra($valore);
					break;
					
					case '4-dimensioni':
					echo $pvp->step4_extra2($valore);
					break;
					
					case '4-final':
					echo $pvp->step4_final($valore);
					break;

					  
				    default:
				    echo 'nessun metodo valido è stato trovato per l\'opzione: ' . $valore . ' nello step: ' . $step;
										
				}	//end switch		
				
				
				
			} //end main



			//eseguo la funzione main
			main($step, $valore);


?>