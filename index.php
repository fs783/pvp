<?php
session_start();

require_once './class/main.pvp.php';
$pvp = new PVP();

$pvp->checkValidLogin(); // controllo se il cookie è presente
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>PVP</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    	    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        <link href="css/style.css" rel="stylesheet">

  </head>
  <body style="background-color: #2c3336;color:#FFF;">
	  <script>document.body.className += ' fade-out';</script>


<div class="row centered">
	 <div class="col-xs-12 col-md-12">

<!-- Stack the columns on mobile by making one full-width and the other half-width -->
<div class="alert alert-danger" role="alert" style="width:80%;margin:0 auto;display:none;" id="login-fail">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <center><strong><h4>Attenzione!</h4></strong></center>Utente non abilitato. Contattare il servizio di assistenza.
</div>
	 
	  <h3>Inserisci il tuo codice Identificativo</h3>

	  <br /><br />
	  
	  <input type="text" id="codice-sap" class="form-control input-lg" placeholder="Codice">

	  	<br /><br />
	  	
	  	<button type="button" class="btn btn-danger btn-home btn-lg"  id="login">Login</button>
	  	<br /><br />

  	</div>
</div>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/ajaxCall.js"></script>
    
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
	

	<script src="./js/jquery-ui.min.js"></script>
  </body>
</html>