<section id='teacher_view' class="container-fluid">
<span id=#top></span>
<?php if(isset($message) AND !empty($message)){ ?>
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $message; ?> ! </p>
	</div>
<?php } 
 	if(isset($message_warning) AND !empty($message_warning)){ ?>
	<div class="alert alert-warning alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 <p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> <?php echo $message_warning; ?> ! </p>
	</div>
<?php } ?>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-title text-center">
				<h4>Présences au cours <?php if(isset($lesson) AND !empty($lesson)) echo '"' . $lesson .'"';?> du <?php echo date("d/m/Y"); ?> <?php if(isset($week_name) AND !empty($week_name)) echo "[" . $week_name . "]"; ?></h4>
			</div>
		</div>
	 	<div class="panel-body">
	    	<form method="post" action="index.php?action=teacher">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name='bloc' class="form-control">
	    				<option value="Bloc1" <?php if(isset($bloc) AND $bloc == "Bloc1") echo 'selected'; ?>>Bloc 1</option>
	    				<option value="Bloc2" <?php if(isset($bloc) AND $bloc == "Bloc2") echo 'selected'; ?>>Bloc 2</option>
	    				<option value="Bloc3" <?php if(isset($bloc) AND $bloc == "Bloc3") echo 'selected'; ?>>Bloc 3</option>
	    			</select>
	    		</div>
	    		
	    		<div class="col-md-1">
	    			<input type=submit class="btn btn-success" value="Rechercher"/>
	    		</div>
	    	</form>
	  </div>
	</div>
</div>
<?php if((isset($series) AND !empty($series)) OR (isset($serie))){ //First condition ?>
<div class="row">
	<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=teacher">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name="serie" class="form-control">
	    			<?php foreach ($series as $element){ ?>
	    				<option value="<?php echo $element; ?>" <?php if(isset($serie) AND $serie == $element) echo 'selected'; ?>>Serie <?php echo $element; ?></option>
	    			<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-1">
	    			<input type="hidden" name="bloc" value= "<?php echo $bloc;?>"/>
	    			<input type=submit class="btn btn-success" value="Rechercher"/>
	    		</div>
	    	</form>
	  </div>
	</div>
</div>
<?php if(isset($serie) AND !empty($serie) AND !empty($sessions)) { //Second condition ?>
<div class="row">
	<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=teacher">
	    		<div class="col-md-6 col-md-offset-2">
	    		<select name="session" class="form-control">
	    				<?php foreach ($sessions as $element){ ?>
	    				<option value="<?php echo $element->id_session; ?>" <?php if(isset($session) AND $session == $element->id_session) echo 'selected'; ?>><?php echo $element->name; ?></option>
	    			<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-1">
	    			<input type="hidden" name="bloc" value= "<?php echo $bloc;?>"/>
	    			<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
	    			<input type=submit class="btn btn-success" value="Rechercher"/>
	    		</div>
	    	</form>
	  </div>
	</div>
</div>
<?php if(isset($session) AND !empty($session)){ //Third condition ?>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-body">
			<form method="post" action="index.php?action=teacher">
			<div class="col-md-1 col-md-offset-1 text-right">
				<label for="week_select"><?php echo strtoupper($current_quadri); ?>:</label>
			</div>
				<div class="col-md-3">
					<select id="week_select" class="form-control" name='week'>
						<?php for($i=1; $i < $current_week_number; $i++){ ?>
							<option value="<?php echo $i; ?>" <?php if(isset($week_number) AND $week_number == $i) echo 'selected';?>>Semaine <?php echo $i; ?></option>
						<?php } ?>
							<option value="<?php echo $current_week_number; ?>" <?php if(!isset($week_number) OR (isset($week_number) AND $week_number == $current_week_number)) echo 'selected'; ?>>Semaine courante</option>
					</select>
				</div>
				<div class="col-md-3">
	    			<select name="presence_type" class="form-control">
	    				<option value="X" <?php if($presence_type == "X") echo "selected" ?>>X</option>
	    				<option value="XO" <?php if($presence_type == "XO") echo "selected" ?>>XO</option>
	    				<option value="grade" <?php if($presence_type == "grade") echo "selected" ?>>Cote</option>
	    			</select>
	    		</div>
				<div class="col-md-1">
					<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
					<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
					<input type="hidden" name="session" value="<?php echo $session ?>">
					<input type="submit" value="Rechercher" class="btn btn-success"/>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if(isset($session) AND !empty($session)){ //Fourth condition ?>
<div class="row">
	<form action="index.php?action=teacher" method="post">
		<table class="table table-striped table-hover">
	  		<tr>
	  			<th>#</th>
	  			<th>Nom</th>
	  			<th>Prénom</th>
	  			<th>Bloc</th>
	  			<th>Série</th>
	  			<th>Présences</th>
	  		</tr>
	  		<?php for($i = 0; $i<count($students); $i++) { ?>
	  		<tr>
	  			<td><?php echo $i+1; ?></td>
	  			<td><?php echo $students[$i]->getLastName(); ?></td>
	  			<td><?php echo $students[$i]->getFirstName(); ?> </td>
	  			<td><?php echo $students[$i]->getBloc(); ?> </td>
	  			<td><?php echo $students[$i]->getSerie(); ?> </td>
	  			<?php if($presence_type == "X") { ?>
	  			<td><div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="present">Present(e)
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="absent">Absent(e)
						</label>
				</div>
				</td>
				<?php } elseif($presence_type == "XO") { ?>
				<td>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="active">Actif
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="passive">Passif
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="absent">Absent(e)
						</label>
					</div>
				</td>

				<?php } else{ ?>
				<td>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="absent">Absent(e)
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="0">0
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="1">1
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="2">2
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="3">3
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="4">4
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note<?php echo $i;?>" value="5">5
						</label>
					</div>
				</td>

				<?php } ?>
	  		</tr>
	  		<?php } ?>
		</table>
		<div class="col-md-3">
			
		</div>
		<div class="col-md-2 col-md-offset-5 text-right">
			<a class="btn btn-primary" href="#top">Remonter <i class="fa fa-arrow-up" aria-hidden="true"></i> </a>
		</div>
		<div class="col-md-1">
			<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
			<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
			<input type="hidden" name='week' value="<?php if(isset($week_number)) echo $week_number; else echo $current_week_number; ?>">
			<input type="hidden" name="session" value="<?php echo $session; ?>"/>
			<input type="hidden" name="presence_type" value="<?php echo $presence_type; ?>">
			<?php if(isset($add_student_email)) { ?><input type="hidden" name="add_student_email" value="<?php echo $add_student_email; ?>"/><?php } ?>
			<?php if($modify_presence_sheet){ ?>
				<input type="hidden" name="modify_presence" value="modify_presence"/>
				<button type="submit" class="btn btn-warning">Modifier <i class="fa fa-refresh" aria-hidden='true'></i></button>
			<?php } else { ?>
				<input type='hidden' name='presence_send' value="presence_send"/>
				<button type="submit" class="btn btn-success">Enregistrer <i class='fa fa-floppy-o' aria-hidden='true'></i></button>
			<?php } ?> 
		</div>
	</form>
	<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary btn" data-toggle="modal" data-target="#myModal">
			  Ajouter un étudiant <i class="fa fa-plus" aria-hidden="true"></i>
			</button>

			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Ajouter un étudiant</h4>
			      </div>
			      <form method="post" action="index.php?action=teacher&message=add_student">
			      	<div class="modal-body">
						<input type="text" class="form-control" placeholder="Nom de l'étudiant" required name="add_student_name"/>
						<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
						<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
						<input type="hidden" name='week' value="<?php if(isset($week_number)) echo $week_number; else echo $current_week_number; ?>">
						<input type="hidden" name="session" value="<?php echo $session; ?>"/>
						<input type="hidden" name="presence_type" value="<?php echo $presence_type; ?>">
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			        	<button type="submit" class="btn btn-success">Rechercher</button>
			      	</div>
			      </form>
			    </div>
			  </div>
			</div>
</div>
<?php } //Close fourth condition ?>
<?php } //Close third condition ?>
<?php } //Close second condition?>
<?php } //Close first condition?>
</section>

