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
			</div>
		</div>
	</div>

</section>