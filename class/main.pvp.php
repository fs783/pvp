<?php
/************************************
Tipologia: Calsse Main PVP
Autore: Fabio Simi
Data inizio: 18/04/2017 - 16:54
Ultima modifica:  24/04/2017 - 16:34
**************************************/

class PVP {
	
/*------------------------------------------------------------------------------*/
/*						 CONFIGURAZIONE INIZIALE					     	*/
/*----------------------------------------------------------------------------*/

		
		public $riepilogo = 'riepilogo_configuratore';
		
		public $modello = ''; //verrà settato al primo utilizzo

		public $id_sessione = ''; 
		
		protected $host = 'localhost'; 
		
		public $dbtable = 'POOLS-CONFIGURATORE';
		
		public $url = 'http://localhost/pvp/pvp/files';
		
		//protected $dbuser = 'c0pools';
		
		//protected $dbpasswd = 'osT0@2dPNd';
		
		//public $dbtable = 'c0poolsdb';
		
		// public $db_prefix = 'asasdqw22';
		
		// public $table = 'catalogo_pools';
		
		
		/*DEV*/
		
		protected $dbuser = 'root';
		
		protected $dbpasswd = 'root';
		
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
					
					$q = $db->query("UPDATE $this->riepilogo SET scelta_opzionale='' WHERE step=1 AND id_sessione='$token'");	
					
					
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
		
		
			public function calcoloTotale()	{
				
				$db = $this->db_connect();
				
				$token = $_SESSION["step1"]["token_sessione"];
				
				
				$q = $db->query("SELECT scelta_opzionale FROM riepilogo_configuratore WHERE id_sessione='$token' ");
				
				
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