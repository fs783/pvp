	jQuery(document).ready(function(){
		
		

			$(function() {
				    $('body').removeClass('fade-out');
				});	
			
			$('.selectpicker').selectpicker({
			   size: 4
			});
			
		
			$('.telaio').selectpicker({
			   size: 4
			});
			




	var urlCall = "http://localhost/pvp/class/ajaxController.php";
	var urlRedirect = "http://localhost/pvp/files";
			
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
			    
			    statusCode: {
			    403: function() {
			      alert( "proibito!" );
			    }
			  },
			    beforeSend: function(){
			      //$("body").append('<div class="progres_bar_inv"><div class="loading"><div></div></div></div>');
			    },
			    
			    complete: function(){
			     // $(".progres_bar_inv").hide();
			    },
			       
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
			    
			    statusCode: {
			    403: function() {
			      alert( "proibito!" );
			    }
			  },
			    beforeSend: function(){
			      //$("body").append('<div class="progres_bar_inv"><div class="loading"><div></div></div></div>');
			    },
			    
			    complete: function(){
			     // $(".progres_bar_inv").hide();
			    },
			       
			    success: function(responseData, textStatus, jqXHR) {
					
			        $("#telaio").html(responseData);
					$('.telaio').selectpicker("refresh");			
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
			    
			    statusCode: {
			    403: function() {
			      alert( "proibito!" );
			    }
			  },
			    beforeSend: function(){
			      //$("body").append('<div class="progres_bar_inv"><div class="loading"><div></div></div></div>');
			    },
			    
			    complete: function(){
			     // $(".progres_bar_inv").hide();
			    },
			       
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
			    
			    statusCode: {
			    403: function() {
			      alert( "proibito!" );
			    }
			  },
			    beforeSend: function(){
			      //$("body").append('<div class="progres_bar_inv"><div class="loading"><div></div></div></div>');
			    },
			    
			    complete: function(){
			     // $(".progres_bar_inv").hide();
			    },
			       
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
				
			var dimensione_telaio = $(this).find("option:selected").val();
			
			if (dimensione_telaio == 'si'){
			
				
			$.ajax({
			    url: urlCall,
			    data: 'valore=' + tamponamento + '&step=1-tamponamento',
				type: 'POST',
				//async: false,
			    dataType: 'text',
			    
			    statusCode: {
			    403: function() {
			      alert( "proibito!" );
			    }
			  },
			    beforeSend: function(){
			      //$("body").append('<div class="progres_bar_inv"><div class="loading"><div></div></div></div>');
			    },
			    
			    complete: function(){
			     // $(".progres_bar_inv").hide();
			    },
			       
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


							
			} else{

			    location.href = urlRedirect+'/rivestimento.php';
				
			}
		
			});
		
		
		

	
	
	}); //end ready jQuery
	
		
			