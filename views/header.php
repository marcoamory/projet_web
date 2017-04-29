<!DOCTYPE html>
<html>
	<head>
		<title>IPL - Agenda</title>
		<meta charset="utf-8"/>
		<meta name="authors" content="Robin Louis, Marco Amory"/>
		<meta name="description" content="Application de prise de présence de l'IPL"/>
      	<link rel="stylesheet" type="text/css" href="<?php echo PATH_CSS; ?>bootstrap.min.css">
      	<link rel="stylesheet" type="text/css" href="<?php echo PATH_CSS; ?>bootstrap-theme.min.css">
      	<link rel="stylesheet" type="text/css" href="<?php echo PATH_CSS; ?>font-awesome.min.css">
      	<link rel="stylesheet" type="text/css" href="<?php echo PATH_CSS; ?>style.css">
	</head>

	<body>
		<nav class="navbar navbar-inverse">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="index.php?action=home">
	          	IPL - Agenda
	          </a>
	        </div>
	        <div id="navbar" class="collapse navbar-collapse">
	        <?php if(isset($_SESSION['responsibility']) AND $_SESSION['responsibility'] == 'true'){
	        	?>
	        	<ul class="nav navbar-nav">
		            <li><a href="index.php?action=teacher">Prise des présences</a></li>
		            <li><a href="index.php?action=adminManagement">Gestion Admin</a></li>
	          </ul>
	          <?php
	        }
	        if(isset($_SESSION['responsibility']) AND ($_SESSION['responsibility'] == 'blocs')){
	        	?>
				<ul class="nav navbar-nav">
		            <li><a href="index.php?action=teacher">Prise des présences</a></li>
		            <li><a href="index.php?action=blocsManager">Gestion Blocs</a></li>
	         	</ul>
	        	<?php
	        } 
	      	if(isset($_SESSION['responsibility']) AND ($_SESSION['responsibility'] == 'bloc' OR $_SESSION['responsibility'] == 'bloc1' OR $_SESSION['responsibility'] == "bloc2" OR $_SESSION['responsibility'] == 'bloc3')){
	        	?>
	        	<ul class="nav navbar-nav">
		            <li><a href="index.php?action=teacher">Prise des présences</a></li>
		            <li><a href="index.php?action=blocsManager">Gestion <?php echo $_SESSION['responsibility'] ?></a></li>
	         	</ul>
	         	<?php
	      	}
	        if(isset($_SESSION['first_name']) AND isset($_SESSION['last_name'])){
	        ?>
	         
	          <a href='index.php?action=logout' type="button" class="btn btn-default navbar-btn navbar-right">Déconnection</a>
	          <p class="navbar-text navbar-right"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></p>
	          <?php
	         }
	         else{ ?>
	         	 <p class="navbar-text navbar-right">Déconnecté</p>
	         	 <?php
	         }
	         ?>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>	

