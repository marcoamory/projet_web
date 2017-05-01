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
						<input id="upload" type="file" name="agenda_properties" />
						<input type="submit" class='btn  btn-success' value="Upload" />
					</form>
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
						<input id="upload" type="file" name="professor_csv" />
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
					       Cette action entrainera la suppression de l'entiereté des professeurs à l'exception de ceux bénéficiant de la résponsabilité d'administrateur. Attention, cette action est irréversible !
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

</section>