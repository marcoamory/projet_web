<section id='adminManagement' class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Agenda de l'année</h3>
		  		</div>
		  		<form enctype="multipart/form-data" action="index.php?action=adminManagement" method="post">
		  			<div class="panel-body">
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input id="upload_agenda" type="file" name="agenda_properties" required />
					</div>
					<div class="panel-footer">
						<button type="submit" class='btn btn-success upload_button'>Upload <i class="fa fa-upload" aria-hidden="true"></i></button>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  			<div class="panel-heading">
		   				 <h3 class="panel-title text-center">Professeurs</h3>
		  			</div>
		  			 <form enctype="multipart/form-data" action="index.php?action=adminManagement" method="post">
		  				<div class="panel-body">
							<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
							<input id="upload_teacher" type="file" name="professor_csv" required />	
		  				</div>
		  				<div class="panel-footer">
		  					<button type="submit" class='btn btn-success upload_button'>Upload <i class="fa fa-upload" aria-hidden="true"></i></button> 
		  				</div>
		  			</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Nettoyer les données annuelles</h3>
				</div>
				<form action="index.php?action=adminManagement" method="post">
					<div class="panel-body">
						<!-- Button trigger modal -->
						<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#myModal">
						 Nettoyer les données annuelles <i class="fa fa-trash-o" aria-hidden="true"></i>
						</button>

						<!-- Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">Nettoyage des données annuelles</h4>
						      </div>
						      <div class="modal-body">
						        Attention, cette action entrainera la suppression de toutes vos données à l'exception de celles concernant les administrateurs. Cette action est irréversible, êtes vous sûrs de vouloir continuer? 
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
						        <input type="submit" class="btn btn-danger" name="wipe_data" value="Nettoyer la sélection" />
						      </div>
						    </div>
						  </div>
						</div>
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
		   	 <p><i class="fa fa-times fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_error']?> ! </p>
    	</div>
<?php }
elseif(!empty($_SESSION['notification_warning'])){?>
    	<div class="alert alert-warning alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_warning']?> ! </p>
    	</div>
<?php } ?>
</section>