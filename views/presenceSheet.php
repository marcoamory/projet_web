<section id="presence_sheet" class="container-fluit">
<span id=top></span>
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
					Feuilles de présences
				</div>				
			</div>
				<div class="panel-body">
			    	<form method="post" action="index.php?action=presenceSheet">
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
	    	<form method="post" action="index.php?action=presenceSheet">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name="serie" class="form-control">
	    			<?php if(!empty($series)){
	    				foreach ($series as $element){ ?>
	    					<option value="<?php echo $element; ?>" <?php if(isset($serie) AND $serie == $element) echo 'selected'; ?>>Serie <?php echo $element; ?></option>
	    			<?php } } else{ ?>
	    					<option value=null>Il n'y a pas de série pour ce bloc</option>
	    			<?php	} ?>
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
<?php if(isset($serie) AND !empty($serie)) { //Second condition  ?>
<div class="row">
	<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=presenceSheet">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name="session" class="form-control">
	    				<?php if(!empty($sessions)) {
	    				 	foreach ($sessions as $element){ ?>
	    						<option value="<?php echo $element->id_session; ?>" <?php if(isset($session) AND $session == $element->id_session) echo 'selected'; ?>><?php echo $element->name; ?></option>
	    				<?php } } else{ ?>
	    						<option value=null>Aucune scéance type pour cette série</option>
	    				<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-1">
	    			<input type="hidden" name="bloc" value= "<?php echo $bloc;?>"/>
	    			<input type='hidden' name="serie" value="<?php echo $serie;?>">
	    			<input type=submit class="btn btn-success" value="Rechercher"/>
	    		</div>
	    	</form>
	  </div>
	</div>
</div>
<?php if(isset($session) AND !empty($session)) { //Third condition ?>
<div class="row">
	<table class="table table-striped table-hover">
		<tr>
			<th>NOM Prénom</th>
				<?php for($i=0; $i<count($week);$i++){ //A loop on each current's quadri weeks until the current week ?>	
					<th><?php echo "Semaine" . $week[$i]->week_number; ?></th>
				<?php } ?>
		</tr>
		<?php for($i=0; $i<count($students); $i++) { ?>
		<tr>
			
				<td><?php echo $students[$i]->getLastName() . " " . $students[$i]->getFirstName(); ?></td>
				<?php for($j=1; $j<=count($week); $j++){
					if(!empty($students_array[$i][$j])){ //If the present sheet exist but there are no presence value for some students, a message appears
					if($students_array[$i][$j]->state == 'present'){ 
						if(!empty($students_array[$i][$j]->grade) OR $students_array[$i][$j]->grade == 0 ) { ?>
							<td><span class="label label-success">Présent(e)</span> <span class="label label-primary"><?php echo $students_array[$i][$j]->grade; ?></span></td>
						<?php } else { ?> 
							<td><span class="label label-success">Présent(e)</span></td>
					<?php } } elseif ($students_array[$i][$j]->state == 'passive') { ?>
						<td><span class="label label-warning">Passif</span></td>
					<?php } elseif ($students_array[$i][$j]->state == 'justify') { ?>
						<td><span class="label label-info">Justifié(e)</span></td>
					<?php } elseif ($students_array[$i][$j]->state == 'active') { ?>
						<td><span class="label label-success">Actif</span></td> 
					<?php } else{ ?>
						<td><span class="label label-danger">Absent(e)</span> <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal<?php echo $i . $j?>">
						Justifier <i class="fa fa-paperclip" aria-hidden="true"></i>
						</button>

						<!-- Modal -->
						<div class="modal fade" id="myModal<?php echo $i . $j; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $i . $j;?>">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel<?php echo $i . $j;?>">Joindre une justification d'absence</h4>
						      </div>
						      <div class="modal-body">
						        Voulez-vous joindre une justification d'absence pour <?php echo $students[$i]->getLastName() . " " . $students[$i]->getFirstName(); ?> pour son absence en Semaine <?php echo $students_array[$i][$j]->week; ?>? 
						      </div>
						      <div class="modal-footer">
						      <form method="post" action="index.php?action=presenceSheet">
						      	<input type="hidden" name="justify" value="<?php echo $students[$i]->getEmail() . "+" . $students_array[$i][$j]->id_sheet; ?>">
						      	<input type="hidden" name="bloc" value= "<?php echo $bloc;?>"/>
	    						<input type="hidden" name="serie" value="<?php echo $serie; ?>">
	    						<input type="hidden" name="session" value="<?php echo $session; ?>">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
						        <input type="submit" class="btn btn-info" value="Justifier" />
						       </form>
						      </div>
						    </div>
						  </div>
						</div></td>
					 
			
			<?php } }
			else{ //If there is a present sheet but no presence value in it?>
				<td></td>
			<?php	} }  ?>
			
		</tr>
		<?php } ?>
	</table>
	<div class="col-md-2 col-md-offset-10 text-right">
		<a class="btn btn-primary" href="#top">Remonter <i class="fa fa-arrow-up" aria-hidden="true"></i> </a>
	</div>
</div>
<?php } //Close third condition ?>
<?php } //Close second condition ?>
<?php } //Close first condition ?>
</section>