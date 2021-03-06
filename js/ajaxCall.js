	jQuery(document).ready(function(){
		
		
		/********************************************
			VARIABILI D'AMBIENTE
			
		*********************************************/	
		
			var urlCall = "http://pools.digitalfollowers.net/class/ajaxController.php";
			var urlRedirect = "http://pools.digitalfollowers.net/files";
			var urlBase = "http://pools.digitalfollowers.net";
			var currentLocation = window.location;
		

			$(function() {
			
				$('body').removeClass('fade-out');
			
			});	
			
			/*****************************************
				NOMI SELECT CON STILE
			*****************************************/
			
			$('.selectpicker').selectpicker();		
			$('.step_4_optional').selectpicker();		
			$('.telaio').selectpicker();
			$('.step2-init').selectpicker();
			$('.step3-init').selectpicker();
			$('.step4-init').selectpicker();
			$('.step5-init').selectpicker();
			$('.step6-init').selectpicker();
			$('.step7-init').selectpicker();
			$('.step8-init').selectpicker();
			$('.step9-init').selectpicker();
			$('.step10-init').selectpicker();
		
		
		$( ".exec" ).on('click tap', function(event) {
				
			event.stopPropagation();
				
			var tipo = $(this).data('nome');
			var step = $(this).data('step');
// 			var tipo =  $(this).data('tipo');
			
			
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + tipo + '&step=' +step,
				type: 'POST',
				//async: false,
			    dataType: 'text',

			    success: function(responseData, textStatus, jqXHR) {
					
			        if(responseData == 1)
			        {
				        location.href = urlRedirect+'/struttura.php';
			        }	
			  }, 
			      error: function (responseData, textStatus, errorThrown) {
							        	console.log(textStatus +' : '+ errorThrown);
				}
				
				});    //end ajax call

		});
		
		
				$('#login').click(function(){
				
				codice = $("#codice-sap").val();
				
				console.log(codice);
				
					$.ajax({
						 url: urlCall,
						 data: 'valore=' + codice + '&step=login' ,
						 type: 'POST',
						//async: false,
						dataType: 'text',
							    
						success: function(responseData, textStatus, jqXHR) {
						
						if (responseData == 'utente_ko'){
							$("#login-fail").hide();
							$("#login-fail").fadeIn(1200).delay(3000).fadeOut(1200); 
							 
							} else	{
								
							location.href = urlRedirect+'/home.php';

							}			
						
						}, 
						error: function (responseData, textStatus, errorThrown) {
							alert(textStatus +' : '+ errorThrown);
						}
								
					});    //end ajax call
				
				
				});
		
		//STEP 1
	
		$('#select_step_1').on('change', function(){

			$("#telaio").show();
			
			var altezza = $(this).find("option:selected").val();
				
			
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + altezza + '&step=1',
				type: 'POST',
				//async: false,
			    dataType: 'text',
       
			    success: function(responseData, textStatus, jqXHR) {
					
			        $("#telaio").html(responseData);
					$('.telaio').selectpicker("refresh");
					$('.dimensione').selectpicker("refresh");
		
			  }, 
			      error: function (responseData, textStatus, errorThrown) {
							        	console.log(textStatus +' : '+ errorThrown);
				}
				
				});    //end ajax call


		  });
		  
		  

		  //TELAIO  -- MANIPOLAZIONE DEL DOM
		
		$(document).on("change", "#select_step_1_telaio", function () {
			
			var tipo_telaio = $(this).find("option:selected").val();
			       
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + tipo_telaio + '&step=1-altezza',
				type: 'POST',
				//async: false,
			    dataType: 'text',

			    success: function(responseData, textStatus, jqXHR) {
					
			        $("#dimensione").html(responseData);
					$('.dimensione').selectpicker("refresh");			
			  }, 
			      error: function (responseData, textStatus, errorThrown) {
							        	console.log(textStatus +' : '+ errorThrown);
				}
				
				});    //end ajax call
        
        });  // end step1_telaio
        
        
        $(document).on("change", "#select_step_1_dimensione", function () {
	        
	        	var dimensione_telaio = $(this).find("option:selected").val();
			
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + dimensione_telaio + '&step=1-dimensione',
				type: 'POST',
				//async: false,
			    dataType: 'text',

			    success: function(responseData, textStatus, jqXHR) {
					
			        $("#tamponamento").html(responseData);
					$('.tamponamento').selectpicker("refresh");			
			  }, 
			      error: function (responseData, textStatus, errorThrown) {
							        	console.log(textStatus +' : '+ errorThrown);
				}
				
				});    //end ajax call
        
        });  // end step1_dimensione

	        
	        //TAMPONAMENTO
            
            $(document).on("change", "#select_step_1_tamponamento", function () {
				
			var tamponamento_fondo = $(this).find("option:selected").val();
			
			console.log(tamponamento_fondo);
						
				
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + tamponamento_fondo + '&step=1-tamponamento',
				type: 'POST',
				//async: false,
			    dataType: 'text',
	       
			    success: function(responseData, textStatus, jqXHR) {
					
					if (responseData == 'scelta_ok')
					{
					
					location.href = urlRedirect+'/rivestimento.php';

					}

			  }, 
			      error: function (responseData, textStatus, errorThrown) {
							        	console.log(textStatus +' : '+ errorThrown);
				}
				
				});    //end ajax call				
		
			});
		
		
		/*		INIZIO STEP 2		*/
	
		
		 $(document).on("change", "#select_step_2", function () {
	        
	        var valore = $(this).find("option:selected").val();
			
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + valore + '&step=2',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
							
				    $("#rivestimento").html(responseData);
					$('.dimensione-2').selectpicker("refresh");
				}, 
				error: function (responseData, textStatus, errorThrown) {
					alert(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step2

		
		$(document).on("change", "#select_step_2_dimensione", function () {
	        
	        var valore = $(this).find("option:selected").val();

							
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + valore + '&step=2-insert',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				calcolaTotale();

			    location.href = urlRedirect+'/membrana.php';
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step1_dimensione
        
        
        
        //STEP 3
        
        $(document).on("change", "#select_step_3", function () {
	        
	        var valore = $(this).find("option:selected").val();
	        
	        console.log(valore);
			
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + valore + '&step=3',
				 type: 'POST',
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
							
				    $("#dimensione-3").html(responseData);
					$('.dimensione-3').selectpicker("refresh");
				}, 
				error: function (responseData, textStatus, errorThrown) {
					alert(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step3_dimensione

        
        $(document).on("change", "#step_3_dimensione", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + valore + '&step=3-insert',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				calcolaTotale();

				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/dotazioni.php';

					}

				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step3_dimensione

        
        /*STEP4*/
        
        $(document).on("change", "#select_step_4", function () {
	        
	        var valore = $(this).find("option:selected").val();
			
			calcolaTotale();
			
		
			if (valore == '2 skimmer + 2 bocchette + 1 scarico ø 50 (2 con T) + troppo pieno'){
				step = '4-extra';
			} else{
				step = '4';
			}
			
					
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=' +step,
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

					if (step == '4-extra')	{	

				    $("#dotazioni").html(responseData);
					$('.dotazioni-4').selectpicker("refresh");

					} else {

				    $("#optional").html(responseData);
					$('.optional-4').selectpicker("refresh");

					}
				}, 
				error: function (responseData, textStatus, errorThrown) {
					alert(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step4


		$(document).on("change", "#step_4_dotazioni", function () {
	        
	        var valore = $(this).find("option:selected").val();

			console.log(valore);
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=4-dimensioni',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				calcolaTotale();

				//abilitare optional
				
				    $("#optional").html(responseData);
					$('.optional-4').selectpicker("refresh");
								
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step4_dotazioni

       
       
       		$(document).on("change", "#step_4_optional", function () {
	        
	        var valore = $(this).find("option:selected").val();

								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=4-final',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				//abilitare optional
				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/filtrazione.php';

					}

								
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step4_dotazioni

        
        
        /*STEP 5*/
        
         
        $(document).on("change", "#select_step_5", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=5',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				    $("#marca").html(responseData);
					$('.marca-5').selectpicker("refresh");
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step5
        
       
        $(document).on("change", "#step_5_marca", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=5-marca',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
					calcolaTotale();
	
				    $("#optional").html(responseData);
					$('.optional-5').selectpicker("refresh");
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step5
        
        
        $(document).on("change", "#step_5_optional", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=5-final',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
	
				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/illuminazione.php';

					}

				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step5


			/*			STEP6			*/
			
	 $(document).on("change", "#select_step_6", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=6',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				    $("#numero-led").html(responseData);
					$('.numero-6').selectpicker("refresh");
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step6


		$(document).on("change", "#step_6_led", function () {
	        
	        var valore = $(this).find("option:selected").val();

			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=6-numero-led',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/bordo.php';

					}

				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step5


        /*			STEP 7		*/
        
        $(document).on("change", "#select_step_7", function () {
	        
	        var valore = $(this).find("option:selected").val();
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=7',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				    $("#dimensione-bordo").html(responseData);
					$('.bordo-7').selectpicker("refresh");
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step7
       
        
        
         $(document).on("change", "#step_7_dimensione", function () {
	        
	        var valore = $(this).find("option:selected").val();
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + valore + '&step=7-insert',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
					
				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/scalette.php';

					}
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step7_dimensione



		/*			STEP8			*/
		
		 $(document).on("change", "#select_step_8", function () {
	        
	        var valore = $(this).find("option:selected").val();
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=8',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/pulizia.php';

					}
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step5
        
        
        
        /*			STEP9 		*/
        
         $(document).on("change", "#select_step_9", function () {
	        
	        var valore = $(this).find("option:selected").val();
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=9',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				    $("#pulizia").html(responseData);
					$('.pulizia-9').selectpicker("refresh");
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step9


        $(document).on("change", "#step_9_dimensione", function () {
	        
	        var valore = $(this).find("option:selected").val();
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=9-insert',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				if (responseData == 'step_ok')
					{
					
					location.href = urlRedirect+'/optional.php';

					}
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step9

        
        $(document).on("change", "#select_step_10", function () {
	        
	        var valore = $(this).find("option:selected").val();
	        								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=10',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {

				    $("#optional").html(responseData);
					$('.optional-10').selectpicker("refresh");
					
					calcolaTotale();
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end step10
        
        
        
        $(document).on("change", "#step_10_optional", function () {
	        
	        var valore = $(this).find("option:selected").val();
										
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(valore) + '&step=10-insert',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
				
				if (responseData == 'scelta_ok')
					{
					
					location.href = urlRedirect+'/riepilogo.php';

					}
				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
			
			        
        });  // end step9


        
        $(document).on("click", "#invia-preventivo", function () {
	        
	        var email = $("#email-cliente").val();
	                
			// DEVO INSERIRE UN RICHIAMO ALLA FUNZIONE DI AGGIORNAMENTO PREZZI
								
			$.ajax({
				 url: urlCall,
				 data: 'valore=' + encodeURIComponent(email) + '&step=invia-preventivo',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
				
				if (responseData == 'msg_ok'){
					
				$("#msg-ok").hide();
				$("#msg-ok").fadeIn(1200).delay(3000).fadeOut(1200); 

				} else{
				
				$("#fail-email-msg").html(responseData);	
				$("#msg-fail").hide();
				$("#msg-fail").fadeIn(1200).delay(3000).fadeOut(1200); 
					
				}
				    				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end invia-email
        
        
        

        
        
        
        //FOOTER FUNCTION
        
        $("#torna-indietro").click(function(event){
	    
	    	event.preventDefault();
			history.back(1);
       
        });
        
        
         $("#salta-opzione").click(function(event){
	    
	    	event.preventDefault();
					
		//	var url = currentLocation.split(urlRedirect);
			
			 var url = currentLocation.toString().split(urlRedirect);
			 
			 var cleanUrl = url.toString().replace('/','').replace(',','').replace('#','').replace('.php','');
			 
					 switch(cleanUrl)	{
						 
						 case "rivestimento":
						 location.href = urlRedirect+'/membrana.php';
						 break;
						 
						 case "membrana":
						 location.href = urlRedirect+'/dotazioni.php';
						 break;
						 
						 case "dotazioni":
						 location.href = urlRedirect+'/filtrazione.php';
						 break;
						 
						 case "filtrazione":
						 location.href = urlRedirect+'/illuminazione.php';
						 break;
						 
						 case "illuminazione":
						 location.href = urlRedirect+'/bordo.php';
						 break;
						 
						  case "bordo":
						 location.href = urlRedirect+'/scalette.php';
						 break;
						 
						 case "scalette":
						 location.href = urlRedirect+'/pulizia.php';
						 break;
						 
						 case "pulizia":
						 location.href = urlRedirect+'/optional.php';
						 break;
						 
						 case "optional":
						 location.href = urlRedirect+'/riepilogo.php';
						 break;
						 						 
						 default:
						 alert('NON È POSSIBILE SALTARE QUESTO STEP');			 
						 
					 } // end switch
 
         });
	

	
	
		$(document).on("click", "#nuova-configurazione", function () {
	        
								
			$.ajax({
				 url: urlCall,
				 data: 'step=nuova-configurazione',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
					
					if(responseData == 'sessione_distrutta')	{
						
										location.href = urlBase;

					}
				    				
				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
        
        });  // end invia-email
	
	
			function calcolaTotale()	{
				
				$.ajax({
				 url: urlCall,
				 data: 'step=calcolaTotale&valore=0',
				 type: 'POST',
				//async: false,
				dataType: 'text',
					    
				success: function(responseData, textStatus, jqXHR) {
				
				$('.totale').html(responseData);

				}, 
				error: function (responseData, textStatus, errorThrown) {
					console.log(textStatus +' : '+ errorThrown);
				}
						
			});    //end ajax call
				
			}
	
	
	
	}); //end ready jQuery
	
		
			