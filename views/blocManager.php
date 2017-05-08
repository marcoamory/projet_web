﻿<section id="bloc_manager" class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Créer Série</h3>
		  		</div>
		  		<form action="index.php?action=blocManager" method="post">
			  		<div class="panel-body">
							<input type="number" name="nb_series" size="30" placeholder="nombre de séries voulues" class="form-control" />
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-success btn-block">Créer <i class="fa fa-plus" aria-hidden="true"></i></button>
			  		</div>
		  		</form>
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Modifier Série</h3>
		  		</div>
		  		<form action="index.php?action=blocManager" method="post">
			  		<div class="panel-body"> 
							<input type="number" name="modify_serie" size="30" placeholder="série à modifier" class="form-control" />
							<input type="text" name="bloc_serie_modify" size="30" placeholder="bloc de la série à supprimer" class="form-control" />
					</div>
					<div class="panel-footer">
							<button type="submit" class="btn btn-warning btn-block">Modifier <i class="fa fa-refresh" aria-hidden="true"></i></button>
			  		</div>
		  		</form>
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Supprimer Série</h3>
		  		</div>
		  		<form action="index.php?action=blocManager" method="post">
			  		<div class="panel-body"> 
							<input type="number" name="delete_serie" size="30" placeholder="série à supprimer" class="form-control" />
							<input type="text" name="bloc_serie_delete" size="30" placeholder="bloc de la série à supprimer" class="form-control" />		
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-danger btn-block">Supprimer <i class="fa fa-trash-o" aria-hidden="true"></i></button>
			  		</div>
		  		</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Créer Séance type</h3>
		  		</div>
		  		<form action="index.php?action=blocManager" method="post">
			  		<div class="panel-body">
							<input type="text" name="name" size="30" placeholder="Intitulé de la séance(non obligatoire)" class="form-control" />
							<input type="text" name="lesson_fk" size="30" placeholder="UE/AA concernée" class="form-control" />
							<input type="number" name="serie_fk" size="30" placeholder="Série concernée" class="form-control" />	
					</div>
					<div class="panel-footer">
						<button type="submit" class="btn btn-success btn-block">Créer <i class="fa fa-plus" aria-hidden="true"></i></button>
			  		</div>
		  		</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Introduire UE/AA</h3>
		  		</div>
		  		<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
			  		<div class="panel-body"> 
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input class="center-block upload" type="file" name="lessons_csv" />
			  		</div>
			  		<div class="panel-footer">
			  			<button type="submit" class="btn btn-success btn-block">Upload <i class="fa fa-upload" aria-hidden="true"></i></button>
			  		</div>
		  		</form>
			</div>
		</div>
	</div>
	<?php 
	if(!empty($_SESSION['notification_success'])){?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_success']?> ! </p>
		 </div>
	<?php }
	elseif(!empty($_SESSION['notification_error'])){?>
    	<div class="alert alert-danger alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_error']?> ! </p>
    	</div>
<?php }
elseif(!empty($_SESSION['notification_warning'])){?>
    	<div class="alert alert-warning alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_warning']?> ! </p>
    	</div>
<?php } ?>
</section>
