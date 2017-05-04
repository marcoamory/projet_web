<section id="blocs_manager" class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Introduire UE/AA</h3>
				</div>
				<form enctype="multipart/form-data" action="index.php?action=blocsManager" method="post">
					<div class="panel-body">			
							<h6>Bloc numéro :</h6> 
							<input type="text" name="blocNumber" placeholder="numéro bloc" />
							<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
							<input class="center-block upload" type="file" name="lessons_csv" />	
					</div>
					<div class="panel-footer">
						<input type="submit" value="Upload" class="btn btn-success upload_button"/>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Introduire Etudiants</h3>
		  		</div>
		  		<form enctype="multipart/form-data" action="index.php?action=blocsManager" method="post">
		  			<div class="panel-body">
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input class="center-block upload" type="file" name="students_csv" />
		  			</div>
		  			<div class="panel-footer">
		  				<input type="submit" value="Upload" class="btn btn-success upload_button" />
		  			</div>
		  		</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Nettoyer les données annuelles</h3>
				</div>
				<div class="panel-body">
					<form action="index.php?action=blocsManager" method="post">
						<h4 class="text-center">
							Carwash
							<input type="radio" name="wipeChoice" value="total" />
						</h4>
						<div class="text-center">
							<input type="submit" value="Nettoyer la sélection" class="btn btn-danger" />
						</div>
					</form>
		  		</div>
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
