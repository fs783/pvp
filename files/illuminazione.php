<?php
session_start();
require_once '../class/main.pvp.php';
$pvp = new PVP();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>PVP - DOTAZIONI CIRCOLAZIONE</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/jquery-ui.min.css">

    	    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

	<link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        <link href="../css/style.css" rel="stylesheet">

  </head>
  <body class="body-illuminazione">
<script>document.body.className += ' fade-out';</script>
<!-- 	  <script>document.body.className += ' fade-out';</script> -->
<!-- Stack the columns on mobile by making one full-width and the other half-width -->
<div class="row ">
	 <div class="col-xs-12 col-md-12 center-page">
	 <h4>PrimaVeraPool</h4>
	 <h3>ILLUMINAZIONE</h3>
	 <br />

	 <?php $pvp->step6(); ?>
	 
	 <div id="numero-led"></div>




  </div>
</div>

<?php include_once "footer.php" ?>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/ajaxCall.js"></script>
    
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
	

	<script src="../js/jquery-ui.min.js"></script>
    
  </body>
</html>