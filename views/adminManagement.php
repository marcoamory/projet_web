<section id='adminManagement'>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Agenda de l'année</h3>
		  		</div>
		  		<div class="panel-body">
		   			 <form enctype="multipart/form-data" action="index.php?action=adminManagement" method="post">
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input id="upload" type="file" name="agenda_properties" required />
						<input type="submit" class='btn  btn-success' value="Upload" />
					</form>
		  		</div>
		  		 <div class="panel-footer">
		  		 	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal2">Supprimer l'agenda</button>
		  		 	<!-- Button trigger modal -->
					<!-- Modal -->
					<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Supprimer l'agenda</h4>
					      </div>
					      <div class="modal-body">
					       Cette action entrainera la suppression de l'entiereté de l'agenda. Attention, cette action est irréversible !
					      </div>
					      <div class="modal-footer">
					      	<form action='index.php?action=adminManagement' method="post">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
					        <input type="submit" class="btn btn-danger" name="deleteAgenda" value="Supprimer"/>
					      </div>
					    </div>
					  </div>
					</div>
		  		 </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
		  			<div class="panel-heading">
		   				 <h3 class="panel-title text-center">Professeurs</h3>
		  			</div>
		  		<div class="panel-body">
		   			 <form enctype="multipart/form-data" action="index.php?action=adminManagement" method="post">
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input id="upload" type="file" name="professor_csv" required />
						<input type="submit" class='btn btn-success' value="Upload" />
					</form>
		  		</div>
		  		<div class="panel-footer">
		  		 	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Supprimer les professeurs</button>
		  		 	<!-- Button trigger modal -->
					<!-- Modal -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Supprimer les professeurs</h4>
					      </div>
					      <div class="modal-body">
					       Cette action entrainera la suppression de l'entiereté des professeurs de l'année à l'exception des administrateurs. Attention, cette action est irréversible !
					      </div>
					      <div class="modal-footer">
					      	<form action='index.php?action=adminManagement' method="post">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
					        <input type="submit" class="btn btn-danger" name="deleteTeacher" value="Supprimer"/>
					      </div>
					    </div>
					  </div>
					</div>
		  		 </div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Nettoyer les données annuelles</h3>
				</div>
				<div class="panel-body">
					<form action="index.php?action=adminManagement" method="post">
						<?php 
						/*
						<h4 class="text-center">
							Etudiants
							<input type="radio" name="wipeChoice" value="students" />
						</h4>
						<h4 class="text-center">
							Présences
							<input type="radio" name="wipeChoice" value="presences" />
						</h4>
						<h4 class="text-center">
							Séries
							<input type="radio" name="wipeChoice" value="serie" />
						</h4>
						<h4 class="text-center">
							UE/AA
							<input type="radio" name="wipeChoice" value="lessons" />
						</h4>
						<h4 class="text-center">
							Professeurs
							<input type="radio" name="wipeChoice" value="teachers" />
						</h4>
						<h4 class="text-center">
							Agenda
							<input type="radio" name="wipeChoice" value="agenda" />
						</h4>
						*/
						?>
						<h4 class="text-center">
							Carwash
							<input type="radio" name="wipeChoice" value="total" />
						</h4>
						<div class="text-center">
							<input type="submit" class="btn btn-danger" value="Nettoyer la sélection" />
						</div>
					</form>
		  		</div>
			</div>
		</div>
	</div>

</section>