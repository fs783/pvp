<?php
/************************************
Tipologia: Calsse Main PVP
Autore: Fabio Simi
Data inizio: 18/04/2017 - 16:54
Ultima modifica:  04/05/2017 - 03:18
**************************************/

class PVP {
	
/*------------------------------------------------------------------------------*/
/*						 CONFIGURAZIONE INIZIALE					     	*/
/*----------------------------------------------------------------------------*/

		
		public $riepilogo = 'riepilogo_configuratore';
		
		public $modello = ''; //verrà settato al primo utilizzo

		public $id_sessione = ''; 
		
		protected $host = 'localhost'; 
		
		public $dbtable = 'digitalfollowers_pools';
		
		public $url = 'http://pools.digitalfollowers.net/files';
		
		//protected $dbuser = 'c0pools';
		
		//protected $dbpasswd = 'osT0@2dPNd';
		
		//public $dbtable = 'c0poolsdb';
		
		// public $db_prefix = 'asasdqw22';
		
		// public $table = 'catalogo_pools';
		
		
		/*DEV*/
		
		protected $dbuser = 'digitalfollowers_pools';
		
		protected $dbpasswd = '7Ig~@cmJNRkp';
		
		// non includo nessuna tabella perché è variabile
		
		
		/* /END DEV */

		
		public $debug = 0; // valere booleano 0 = disattivato, 1 = attivato
	
	
	
	public function db_connect()	{
		
		// la tabella di connessione è variabile e dunque verrà passata come argomento del metodo
	           	

		$con = new mysqli($this->host, $this->dbuser, $this->dbpasswd, $this->dbtable);
		$con->set_charset("utf8");

			if (mysqli_connect_errno($con))
			  
			  {
			  echo "ERRORE: " . mysqli_connect_error();
			  }
			  
// 			 else{echo 'CONNESSIONE AVVENUTA CON SUCCESSO!';}
			
			  return $con;

	}  //END db_connect
	
	
	
			public function inizioConfigurazione($nome)	{
				
				$db = $this->db_connect($this->riepilogo);
				
				$token =  uniqid('pvp_'); // id univoco assegnato alla singola richiesta (cosa da veri professionisti!)
								
				$q = $db->query("INSERT INTO $this->riepilogo (step, modello, id_sessione, data) VALUES ('home', '$nome', '$token', NOW())");
				
				/* free result set */
				$db->close();
						
				$_SESSION["step1"]["token_sessione"] = $token;
				$_SESSION["step1"]["modello"] = $nome;
				
				if($q == true)
				{
				
				return true;

				}
				
			}  //end inizioConfigurazione
			
			
			public function step1()	{
				
				$db = $this->db_connect($this->riepilogo);
				
				$modello = $_SESSION["step1"]['modello'];
/*
				$token = $_SESSION["token_sessione"];
									
				$q = $db->query("SELECT valore FROM $this->riepilogo WHERE id_sessione = '$this->id_sessione'");
							
			    $obj = $q->fetch_object();
			    
			    $valore = $obj->valore; 
*///seleziono la tipologia di piscina scelta
			    
			    //seleziono l'altezza in base alla scelta
			    
   				$q = $db->query("select distinct altezza from configuratore_$modello WHERE step=1");
   				
   				
   				echo '
   				<select id="select_step_1" class="selectpicker show-tick">
   				<option>Seleziona Altezza</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->altezza.'">' .$obj->altezza . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();
								
			} // end step1
			
			public function step1_telaio($valore){
				
				$db = $this->db_connect($this->riepilogo);
				
				$modello = $_SESSION["step1"]['modello'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
							
				//INSERISCO IL RECORD SUL DATABASE
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='1' ");
							
				$row = $q->num_rows;
				
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza) VALUES ('1', '$modello', '$token', NOW(), '$valore') ");
					
					$_SESSION["step1"]['altezza'] = $valore;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET altezza='$valore' WHERE id_sessione = '$token' and step = '1' ");

					$_SESSION["step1"]['altezza'] = $valore;
				}
				
				//SELEZIONO I TELAI
   				$q = $db->query("select distinct scelta_primaria from configuratore_$modello WHERE step=1 AND altezza ='$valore' ");
   				
   				
  				
   				if ($modello == 'design')
   				{
   				 echo '
   				<br />
   				<h3>TELAIO</H3>
   				<select id="select_step_1_telaio" class="telaio show-tick">
   				<option>Seleziona Telaio</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
			    
			    } else {
				    
				 echo '
				 <br />
   				 <h3>DIMENSIONE</H3>
   				<select id="select_step_1_dimensione" class="dimensione show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 

				    
			    }
				
				/* free result set */
				$db->close();

//    				echo '<script href="../js/ajaxCall.js"></script>';
				
			} //end step1_telaio
			
			public function step1_dimensione($valore)
			{
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$token = $_SESSION["step1"]["token_sessione"];
					
				//AGGIORNO IL RECORD SUL DATABASE GIÀ PRESENTE				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '1' ");
				$_SESSION["step1"]['telaio'] = $valore;

		
				//SELEZIONO LA DIMENSIONE
				$q = $db->query("select distinct scelta_secondaria from configuratore_$modello WHERE step=1 AND scelta_primaria = '$valore' ");
				
				 echo '
				 <br />
   				 <h3>DIMENSIONE</H3>
   				<select id="select_step_1_dimensione" class="dimensione show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();
				
				
			} //end step1_dimensione
			
			
			public function step1_tamponamento($valore)
			{
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$altezza = $_SESSION["step1"]['altezza'];
				
				$telaio = @$_SESSION["step1"]['telaio'];
				
				
				$_SESSION["step1"]['dimensione'] = $valore;
					
				//AGGIORNO IL RECORD SUL DATABASE GIÀ PRESENTE				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$valore' WHERE id_sessione = '$token' and step = '1' ");
				
		
				// INSERISCO IL PREZZO IN BASE ALLA SCELTA	
		
					if ($modello == 'design')
					{
					
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_secondaria='$valore' AND step = '1' AND altezza = '$altezza' AND scelta_primaria = '$telaio')WHERE step=1 AND scelta_secondaria = '$valore' AND id_sessione='$token' ");
					
					} else 	{
						
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '1' AND altezza = '$altezza')WHERE step=1 AND scelta_secondaria = '$valore' AND id_sessione='$token' ");
								
					}
					
				
				 echo '
				 <br />
   				 <h3>TAMPONAMENTO DI FONDO</H3>
   				<select id="select_step_1_tamponamento" class="tamponamento show-tick">
   				<option>Seleziona Tamponamento</option>
   				<option value="si">SÌ</option>
   				<option value="no">NO</option>
   				';
				 
			    echo '</select>'; 
				
				/* free result set */
				$db->close();
				
				
			} //end step1_tamponamento
			
			
			public function step1_optional($scelta)
			{
				
				$db = $this->db_connect();

				$modello = $_SESSION["step1"]['modello'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$altezza = $_SESSION["step1"]['altezza'];
				
				$telaio = $_SESSION["step1"]['telaio'];
				
				$dimensione = $_SESSION["step1"]['dimensione'];
				
				
				//AGGIORNO IL RECORD SUL DATABASE GIÀ PRESENTE		
				
				if ($scelta == 'no')
				{

					$q = $db->query("UPDATE $this->riepilogo SET scelta_opzionale='0.00' WHERE step='1' AND id_sessione='$token'");	
					
					$db->close();
				
					return 'scelta_ok';
					
				} else	{
				
								
				if ($modello == 'design')
				{
						
				$q = $db->query("UPDATE $this->riepilogo SET scelta_opzionale=(SELECT scelta_opzionale FROM configuratore_$modello WHERE scelta_secondaria='$dimensione' AND step = '1' AND altezza = '$altezza' AND scelta_primaria = '$telaio') WHERE step=1 AND scelta_secondaria = '$dimensione' AND id_sessione='$token' ");

				
				} else 	{
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_opzionale=(SELECT scelta_opzionale FROM configuratore_$modello WHERE scelta_primaria='$dimensione' AND step = '1' AND altezza = '$altezza') WHERE step=1 AND scelta_secondaria = '$dimensione' AND id_sessione='$token' ");
								
				}
				
				$db->close();
				
				return 'scelta_ok';

			}


								
				
			}
			
			public function step2(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step=2 AND altezza ='$altezza' ");
   				
   				
   				echo '
   				<select id="select_step_2" class="step2-init show-tick">
   				<option>Seleziona Rivestimento Esterno</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();

						
				
			} //end step2
			
			
			public function step2_dimensione($scelta_primaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='2' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('2', '$modello', '$token', NOW(), '$altezza', '$scelta_primaria')");
					
					$_SESSION["step2"]['rivestimento'] = $scelta_primaria;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$scelta_primaria' WHERE id_sessione = '$token' and step = '2' ");

					$_SESSION["step2"]['rivestimento'] = $scelta_primaria;
				}


				//mostro seconda select
				
				//SELEZIONO I TELAI
   				$q = $db->query("select scelta_secondaria from configuratore_$modello WHERE step='2' AND altezza ='$altezza' AND scelta_primaria ='$scelta_primaria' ");
   				
				
				 echo '
   				<br />
   				<h3>DIMENSIONE</H3>
   				<select id="select_step_2_dimensione" class="dimensione-2 show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 

				
				
			} //end step2_dimensione



			public function step2_insert($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step2"]['rivestimento'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '2' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step2']['dimensione'] = $scelta_secondaria;
				
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '2' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step=2 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
				
											
				
			}
			
			
		public function step3(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step=3 ");
   				
   				
   				echo '
   				<select id="select_step_3" class="step3-init show-tick">
   				<option>Seleziona Membrana Interna</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step3
			
			
			
			public function step3_dimensione($scelta_primaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='3' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('3', '$modello', '$token', NOW(), '$altezza', '$scelta_primaria')");
					
					$_SESSION["step3"]['scelta_primaria'] = $scelta_primaria;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$scelta_primaria' WHERE id_sessione = '$token' and step = '3' ");

					$_SESSION["step3"]['scelta_primaria'] = $scelta_primaria;
					
				}

				//mostro seconda select
				
				$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';
				
												
				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='3' AND altezza ='$altezza' AND scelta_primaria ='$scelta_primaria' ");
   				
				
				 echo '
   				<br />
   				<h3>DIMENSIONE</H3>
   				<select id="step_3_dimensione" class="dimensione-3 show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 

				
				
			} //end step3_dimensione

			
			
			public function step3_insert($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step3"]['scelta_primaria'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '3' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step3']['dimensione'] = $scelta_secondaria;
				
			$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';		
					
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '3' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step=3 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
				
				return 'step_ok';
														
			}

			
		
			public function step4(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='4' ");
   				
   				
   				
   				echo '
   				<select id="select_step_4" class="step4-init show-tick">
   				<option>Seleziona Dotazioni</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step4


			public function step4_dimensione($scelta_primaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='4' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('4', '$modello', '$token', NOW(), '$altezza', '$scelta_primaria')");
					
					$_SESSION["step4"]['scelta_primaria'] = $scelta_primaria;
												
					//aggiorno il prezzo
										
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '4'), scelta_secondaria=(SELECT scelta_secondaria FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '4') WHERE step=4 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
					
									
				}
								
				else {
					
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$scelta_primaria' WHERE id_sessione = '$token' and step = '4' ");
								
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '4'), scelta_secondaria=(SELECT scelta_secondaria FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '4') WHERE step=4 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");

					$_SESSION["step4"]['scelta_primaria'] = $scelta_primaria;
					
				}

				return $this->step4_optional();																		
				
			} //end step4_dimensione
			
			
			public function step4_extra($valore)	{
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				 				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='4' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('4', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step4"]['scelta_primaria'] = $valore;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '4' ");

					$_SESSION["step4"]['scelta_primaria'] = $valore;
				}


				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='4' AND scelta_primaria ='$valore' ");
				
				
				 echo '
   				<br />
   				<h3>DIMENSIONI</H3>
   				<select id="step_4_dotazioni" class="dotazioni-4 show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
			
			}
			
			
			public function step4_extra2($scelta_secondaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step4"]['scelta_primaria'];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='4' ");
																	

				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND scelta_secondaria ='$scelta_secondaria' AND step = '4'), scelta_secondaria='$scelta_secondaria' WHERE step=4 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");

					$_SESSION["step4"]['scelta_secondaria'] = $scelta_secondaria;
				
				return $this->step4_optional();																		
				
			} //end step4_dimensione
			
			
		
			
			public function step4_optional()	{
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='4s' ");
				
				
				 echo '
   				<br />
   				<h3>OPTIONAL</H3>
   				<select id="step_4_optional" class="optional-4 show-tick">
   				<option>Seleziona Optional</option>
   				<option value="nessuno">Nessuno</option>;
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
   				echo '<option value="skimmer_bocchetta">Skimmer + Bocchetta</option>';
			    echo '</select>'; 

			}


			public function step4_final($valore){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='4s' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('4s', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step4s"]['scelta_primaria'] = $valore;
												
					//aggiorno il prezzo
										
	if ($valore == 'skimmer_bocchetta')
								{
								
								$q = $db->query("UPDATE $this->riepilogo SET prezzo='682.80' WHERE step='4s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
			
								} else {
											
								$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '4s') WHERE step='4s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
		
							}
									
				}
								
				else {
					
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '4s' ");
							if ($valore == 'skimmer_bocchetta')
							{
							
							$q = $db->query("UPDATE $this->riepilogo SET prezzo='682.80' WHERE step='4s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
		
							} else {
										
							$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '4s') WHERE step='4s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
							}
					
					$_SESSION["step4s"]['scelta_primaria'] = $valore;
					
				}

				return 'step_ok';
				
				
			}//end step4_final
			
			
		public function step5(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='5' ");
   				
   				
   				
   				echo '
   				<select id="select_step_5" class="step5-init show-tick">
   				<option>Seleziona Filtrazione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step5
			
			
		public function step5_marca($valore)	{
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				 				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='5' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('5', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step5"]['scelta_primaria'] = $valore;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '5' ");

					$_SESSION["step5"]['scelta_primaria'] = $valore;
				}


				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='5' AND scelta_primaria ='$valore' ");
				
				
				 echo '
   				<br />
   				<h3>MARCA</H3>
   				<select id="step_5_marca" class="marca-5 show-tick">
   				<option>Seleziona Marca</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
			
			} //end step5_marca
			
			
			public function step5_marca_insert($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step5"]['scelta_primaria'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '5' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step5']['marca'] = $scelta_secondaria;
				
			$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';		
					
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '5' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step=5 AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
				
					return $this->step5_optional();																		

			}

			 
		public function step5_optional()	{
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='5s' ");
				
				
				 echo '
   				<br />
   				<h3>OPTIONAL</H3>
   				<select id="step_5_optional" class="optional-5 show-tick">
   				<option>Seleziona Optional</option>
   				<option value="nessuno">Nessuno</option>;
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    echo '</select>'; 

			}

		
		public function step5_final($valore){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='5s' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('5s', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step5s"]['scelta_primaria'] = $valore;
												
					//aggiorno il prezzo
										
	if ($valore == 'nessuno')
								{
								
								$q = $db->query("UPDATE $this->riepilogo SET prezzo='0.00' WHERE step='5s' ");
			
								} else {
											
								$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '5s') WHERE step='5s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
		
							}
									
				}
								
				else {
					
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '4s' ");
							if ($valore == 'nessuno')
							{
							
							$q = $db->query("UPDATE $this->riepilogo SET prezzo='0.00' WHERE step='5s' ");
		
							} else {
										
							$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '5s') WHERE step='5s' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
							}
					
					$_SESSION["step5s"]['scelta_primaria'] = $valore;
					
				}

				return 'step_ok';
				
				
			}//end step5_final
			
			
			
			public function step6(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='6' ");
   				
   				
   				
   				echo '
   				<select id="select_step_6" class="step6-init show-tick">
   				<option>Seleziona Illuminazione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step6
			
		
		
		public function step6_led($valore)	{
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				 				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='6' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('6', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step6"]['scelta_primaria'] = $valore;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '6' ");

					$_SESSION["step6"]['scelta_primaria'] = $valore;
				}


				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='6' AND scelta_primaria ='$valore' ");
				
				
				 echo '
   				<br />
   				<h3>NUMERO</H3>
   				<select id="step_6_led" class="numero-6 show-tick">
   				<option>Seleziona quantità</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
			
			} //end step5_marca

		
		
			public function step6_final($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step6"]['scelta_primaria'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '6' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step6']['numero'] = $scelta_secondaria;
				
			$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';		
					
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '6' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step='6' AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
				
				return 'step_ok';
														
			}
		
		
		
		public function step7(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='7' ");
   				
   				
   				
   				echo '
   				<select id="select_step_7" class="step7-init show-tick">
   				<option>Seleziona bordo</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step7


			public function step7_dimensione($scelta_primaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='7' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('7', '$modello', '$token', NOW(), '$altezza', '$scelta_primaria')");
					
					$_SESSION["step7"]['scelta_primaria'] = $scelta_primaria;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$scelta_primaria' WHERE id_sessione = '$token' and step = '7' ");

					$_SESSION["step7"]['scelta_primaria'] = $scelta_primaria;
					
				}

				//mostro seconda select
				
				$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';
				
												
				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='7' AND altezza ='$altezza' AND scelta_primaria ='$scelta_primaria' ");
   				
				
				 echo '
   				<br />
   				<h3>DIMENSIONE</H3>
   				<select id="step_7_dimensione" class="bordo-7 show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 

				
				
			} //end step7_dimensione

		
		public function step7_insert($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step7"]['scelta_primaria'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '7' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step7']['dimensione'] = $scelta_secondaria;
				
			$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';		
					
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '7' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step='7' AND scelta_primaria = '$scelta_primaria' AND id_sessione='$token' ");
				
				return 'step_ok';
														
			}
		
		
		
			public function step8(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='8' ");
   				
   				
   				
   				echo '
   				<select id="select_step_8" class="step8-init show-tick">
   				<option>Seleziona scalette</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step8
			
			
			public function step8_insert($valore)	{
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				 				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='8' ");
							
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('8', '$modello', '$token', NOW(), '$altezza', '$valore')");
					
					$_SESSION["step8"]['scelta_primaria'] = $valore;
					
					//INSERISCO IL PREZZO
					
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '8' AND scelta_secondaria LIKE '%$altezza%') WHERE step='8' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
					
					return 'step_ok';					
					
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$valore' WHERE id_sessione = '$token' and step = '8' ");

					$_SESSION["step8"]['scelta_primaria'] = $valore;
					
					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$valore' AND step = '8' AND scelta_secondaria LIKE '%$altezza%') WHERE step='8' AND scelta_primaria = '$valore' AND id_sessione='$token' ");
					
					return 'step_ok';
				}
						
			} //end step8_insert


		
		public function step9(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT DISTINCT scelta_primaria FROM configuratore_$modello WHERE step='9' ");
   				
   				
   				
   				echo '
   				<select id="select_step_9" class="step9-init show-tick">
   				<option>Seleziona Marca</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_primaria.'">' .$obj->scelta_primaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step9
			
			
		public function step9_dimensione($scelta_primaria){
				
				
				//INSERISCO IL VALORE PRECEDENTE E SELEZIONO I NUOVI
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='9' ");
							
				$row = $q->num_rows;
				
				$scelta_primaria = addslashes($scelta_primaria);
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('9', '$modello', '$token', NOW(), '$altezza', '$scelta_primaria')");
					
					$_SESSION["step9"]['scelta_primaria'] = $scelta_primaria;
				}
								
				else {
					$q = $db->query("UPDATE $this->riepilogo SET scelta_primaria='$scelta_primaria' WHERE id_sessione = '$token' and step = '9' ");

					$_SESSION["step9"]['scelta_primaria'] = $scelta_primaria;
					
				}

				//mostro seconda select
				
				$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';
				
												
				//SELEZIONO I TELAI
   				$q = $db->query("select DISTINCT scelta_secondaria from configuratore_$modello WHERE step='9' AND altezza ='$altezza' AND scelta_primaria ='$scelta_primaria' ");
   				  				
				
				 echo '
   				<br />
   				<h3>DIMENSIONE</H3>
   				<select id="step_9_dimensione" class="pulizia-9 show-tick">
   				<option>Seleziona Dimensione</option>
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 

				
				
			} //end step9_dimensione


		public function step9_insert($scelta_secondaria){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
				
				$scelta_primaria = $_SESSION["step9"]['scelta_primaria'];
				
				//AGGIORNO SEMPRE IL RECORD ESISTENTE
				
				$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria='$scelta_secondaria' WHERE id_sessione = '$token' and step = '9' AND scelta_primaria='$scelta_primaria' ");
				
				$_SESSION['step9']['dimensione'] = $scelta_secondaria;
				
			$altezza = ($modello == 'sport' and $scelta_primaria == 'liner') ? $altezza : 'tutte';		
					
				// inserisco il prezzo				
				$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='$scelta_primaria' AND step = '9' AND altezza = '$altezza' AND scelta_secondaria='$scelta_secondaria') WHERE step='9' AND scelta_primaria='$scelta_primaria' AND id_sessione='$token' ");
				
				return 'step_ok';
														
			}

		
		
				public function step10(){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];
							
				// ESEGUO UN CONTROLLO DI INTEGRITA' TRA SESSIONE E DATABASE
				
				$q = $db->query("SELECT id_sessione, altezza FROM $this->riepilogo WHERE id_sessione='$token' AND altezza='$altezza' ");

				$row = $q->num_rows;
				
				if ($row == '0'){
					
					echo '<div class="alert alert-danger" role="alert">ERRORE NEL CONTROLLO DEI DATI. INIZIARE UNA NUOVA CONFIGURAZIONE!</div>';
					
					exit();
				} 
				
				// FINE CONTROLLO
				
				
			//	$altezza = ($modello == 'sport') ? $altezza : 'tutte';
				
				$q = $db->query("SELECT scelta_secondaria FROM configuratore_$modello WHERE step='10' AND scelta_primaria='AngelEye' ");
   				   				
   				
   				echo '
   				<select id="select_step_10" class="step10-init show-tick">
   				<option>Seleziona Telecamere</option>
   				<option value="nessuno">Nessuna</option>;
   				';
				
			    while ($obj = $q->fetch_object()) {
			        echo '<option value="'.$obj->scelta_secondaria.'">' .$obj->scelta_secondaria . '</option>';
			    }		   
			    
			    echo '</select>'; 
				
				/* free result set */
				$db->close();


				
			} //end step10
		
		
		public function step10_option($valore){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='10' ");
							
				$row = $q->num_rows;
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria, scelta_secondaria) VALUES ('10', '$modello', '$token', NOW(), '$altezza', 'AngelEye' , '$valore')");
					
					$_SESSION["step10"]['scelta_secondaria'] = $valore;
												
					//aggiorno il prezzo
										
	if ($valore == 'nessuno')
								{
								
								$q = $db->query("UPDATE $this->riepilogo SET prezzo='0.00' WHERE step='10' ");
			
								} else {
											
								$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='AngelEye' AND scelta_secondaria='$valore' AND step = '10') WHERE step='10' AND scelta_primaria = 'AngelEye' AND scelta_secondaria='$valore'AND id_sessione='$token' ");
								
	
							}
									
				}
								
				else {
					
					$q = $db->query("UPDATE $this->riepilogo SET scelta_secondaria ='$valore' WHERE id_sessione = '$token' and step = '10' ");
					
							if ($valore == 'nessuno')
							{
							
							$q = $db->query("UPDATE $this->riepilogo SET prezzo='0.00' WHERE step='10' ");
		
							} else {
										
								$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='AngelEye' AND scelta_secondaria='$valore' AND step = '10') WHERE step='10' AND scelta_primaria = 'AngelEye' AND scelta_secondaria='$valore' AND id_sessione='$token' ");
															
							}
					
					$_SESSION["step10"]['scelta_secondaria'] = $valore;
					
				}
			
				
				 echo '
   				<br />
   				<h3>NUOTO CONTROCORRENTE</H3>
   				<select id="step_10_optional" class="optional-10 show-tick">
   				<option value="seleziona">Seleziona</option>
   				<option value="si">SÌ</option>
   				<option value="no">NO</option>
   				</select>
   				'; 

			}

				
		public function step10_final($valore){
				
				$db = $this->db_connect();
				
				$modello = $_SESSION["step1"]['modello'];
			
				$altezza = $_SESSION["step1"]['altezza'];
			
				$token = $_SESSION["step1"]["token_sessione"];

				$q = $db->query("SELECT step, id_sessione, altezza FROM $this->riepilogo WHERE  id_sessione = '$token' and step='10s' ");
				
										
				$row = $q->num_rows;
				
														
				if ($row == 0)
				{
					$q = $db->query("INSERT into $this->riepilogo (step, modello, id_sessione, data, altezza, scelta_primaria) VALUES ('10s', '$modello', '$token', NOW(), '$altezza', 'Nuoto controcorrente')");
												
					$_SESSION["step10s"]['scelta_primaria'] = $valore;
												
					//aggiorno il prezzo
										
					if ($valore == 'no')
					
					{
					
 					$q = $db->query("UPDATE $this->riepilogo SET prezzo='0.00' WHERE step='10s' ");
			
					} else{
					

					$q = $db->query("UPDATE $this->riepilogo SET prezzo=(SELECT prezzo FROM configuratore_$modello WHERE scelta_primaria='nuoto controcorrente' AND step = '10s') WHERE step='10s' AND scelta_primaria = 'nuoto controcorrente' AND id_sessione='$token' ");

								
					}
				
				}
				
				
					$_SESSION["step5s"]['scelta_primaria'] = $valore;
					
				return 'scelta_ok';

			//	return 'step_ok';
				
				
			}//end step5_final


			public function loginCheck($codice)	{
				

				$db = $this->db_connect();
				

				$q = $db->query("SELECT * FROM pvp_autorizzati WHERE sap_code='$codice' ");
			
				
				$row = $q->num_rows;
				
				if ($row == 0)
				{
				
				return 'utente_ko';
				
				} else	{
				
				setcookie("checkLoginPVP", 1, time() + (86400 * 365), "/");  /* expire in 1 anno */

				return 'utente_ok';
								
				}
						
				
			}  //end loginCheck
			
			
							
			
			public function inviaPreventivo($email)	{
				
				$db = $this->db_connect();
				
				$token = $_SESSION["step1"]["token_sessione"];

				$q = $db->query("SELECT * FROM $this->riepilogo WHERE id_sessione='$token' ");
				
				
				$mailtxt = '
				<style type="text/css">
				
				td {
					padding-right: 23px;
					min-width:140px;
				}
				
				</style>
				
				';
				$mailtxt .= '<table>';
				$mailtxt .= '<tr style="text-align:left;"><th>COMPONENTI</th><th>TIPOLOGIA</th><th>INFO EXTRA</th><th>PREZZO</th></tr>';
				$mailtxt .= '<tr style="height:20px;\"></tr>';

				 
				   while ($obj = $q->fetch_object()) {
				   
				   $step = $obj->step;

				   if ($step == 'home')
				   continue;
				   else if ($step == '1')
				   $step= 'STRUTTURA:';
				   else if($step == '2')
				   $step = 'RIVESTIMENTO ESTERNO:';
				   else if ($step == '3')
				   $step= 'MEMBRANA INTERNA:';
				   else if ($step == '4')
				   $step= 'DOTAZIONI CIRCOLAZIONE:';
				   else if ($step == '4s')
				   $step= 'OPTIONAL CIRCOLAZIONE:';
				   else if ($step == '5')
				   $step= 'FILTRAZIONE:';
				     else if ($step == '5s')
				   $step= 'OPTIONAL FILTRAZIONE:';
				     else if ($step == '6')
				   $step= 'ILLUMINAZIONE:';
				     else if ($step == '7')
				   $step= 'BORDO:';
				     else if ($step == '8')
				   $step= 'SCALETTA:';
				     else if ($step == '9')
				   $step= 'PULIZIA:';
				     else if ($step == '10')
				   $step= 'OPTIONAL:';
				     else if ($step == '10s')
				   $step= 'NUOTO CONTROCORRENTE:';
				   else
				   $step = '';
				   
				   	$mailtxt .= '<tr>';
				   	$mailtxt .= '<td><strong>'.$step.'</strong></td><td>'.$obj->scelta_primaria.'</td><td>'.$obj->scelta_secondaria.'</td><td>&euro; ' .number_format($obj->prezzo, 2, ',', '.').'</td>';
				   $mailtxt .=  '</tr>';

			    }		   
				$mailtxt .= '<tr style="height:30px;\"></tr>';
				$mailtxt .= '<tr><td colspan = "3"><strong>TOTALE</strong></td><td style="color:red;text-align:left;font-size:14px;"><strong>'.$this->calcoloTotale().'</strong></td></tr>';
								
				$mailtxt .= '</table>';
				
				
				 $this-> sendMailToCustomer($mailtxt, $email);
				
			} //end invia preventivo
			
			
			public function sendMailToCustomer($corpo_msg, $email){
				
				require '../lib/PHPMailer/PHPMailerAutoload.php';

				$mail = new PHPMailer;
				
				//$mail->SMTPDebug = 3;                               // Enable verbose debug output
				
				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'john.uswebhost.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'pvp@digitalfollowers.net';                 // SMTP username
				$mail->Password = 'E~UeT#9BA?by';                           // SMTP password
				$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 465;                                    // TCP port to connect to
				$mail->CharSet = 'UTF-8';
				
				$mail->setFrom('pvp@digitalfollowers.net', 'Preventivo PVP');
		
				$mail->addAddress($email); //tassativamente ordini.web@pools.it
											
				
					$mail->isHTML(true);                                  // Set email format to HTML
					
					$mail->Subject = '[Preventivo] PVP ';
					$mail->Body    = "Buongiorno, <br><br />
					testo da concordare
					<br /><br/>
			
			Ragione Sociale: <strong>Ditta  S.r.l.</strong><br />
			
			<h3>Riepilogo della sua piscina</h3>
			
			<br />		
							
			$corpo_msg
			
			<br /><br />		
			
			Cordiali saluti.
				
			";

			//INVIO MAIL 
			// TODO: CICLO DI CONTROLLO SUL CORRETTO INVIO O MENO
			
			 if(!$mail->Send()) {
			    echo $mail->ErrorInfo;
			 } else {
			    echo "msg_ok";
			 }

				
			}
			
			public function nuovaConfigurazione(){
				
				// distruggo la sessione attuale
				session_unset();
				session_destroy();
				
				echo 'sessione_distrutta';
							
			}
			
			public function checkValidLogin()	{
					

					if (isset($_COOKIE['checkLoginPVP'])) {
					
					header('Location: ../files/home.php');
				
					exit;
							
					}

					
					
				}

			
		
			public function calcoloTotale()	{
				
				$db = $this->db_connect();
				
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT scelta_opzionale FROM riepilogo_configuratore WHERE id_sessione='$token' and step='1' ");
				
				
				$obj = $q->fetch_object();
									
														
				if ($obj->scelta_opzionale === NULL){
					
					$sum = 'SUM(prezzo)';
				
				} else	{
				
					$sum = '(SUM(prezzo)+SUM(scelta_opzionale))';
				
				}
								
				$q = $db->query("SELECT $sum AS totale FROM riepilogo_configuratore WHERE id_sessione='$token' ");
				
				
			    $obj = $q->fetch_object();

				
				return '&euro; ' . number_format($obj->totale, 2, ',', '.');

			}  //end calcolo
	
		
	
	
}  // end class PVP


?>