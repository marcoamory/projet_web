<section id="presence_sheet" class="container-fluit">
<span id=top></span>
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
<?php if(isset($serie) AND !empty($serie)) { //Second condition ?>
<div class="row">
<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=presenceSheet">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name="session" class="form-control">
	    				<?php foreach ($sessions as $element){ ?>
	    				<option value="<?php echo $element->id_session; ?>" <?php if(isset($session) AND $session == $element->id_session) echo 'selected'; ?>><?php echo $element->name; ?></option>
	    			<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-1">
	    			<input type="hidden" name="bloc" value= "<?php echo $bloc;?>"/>
	    			<input type="hidden" name="serie" value="<?php echo $serie; ?>">
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
			<?php for($i=0; $i<count($week);$i++){ //A loop on each current's quadri weeks until the current week 
				if($week[$i]->week_number < $current_week_number || $week[$i]->week_number == $current_week_number){ ?>
			<th><?php echo "Semaine" . $week[$i]->week_number; ?></th>

			<?php } } ?>
		</tr>
		<?php if(isset($sheet) AND !empty($sheet[0])) { //If there aren't any presence sheets for this session, a warning message appears
		 	for($j=0; $j<count($presence_array[0]); $j++) { ?>
		<tr>
			<td><?php echo $presence_array[0][$j]->last_name . " " . $presence_array[0][$j]->first_name; ?>
			<?php for($i=0; $i<count($sheet); $i++) {
				if($sheet[$i]){
				if(!empty($presence_array[$i][$j])){ //If the present sheet exist but there are no presence value for some students, a message appears
					if($presence_array[$i][$j]->state == 'active'){ ?>
						<td><span class="label label-success">Présent(e)</span></td>
					<?php } elseif ($presence_array[$i][$j]->state == 'passive') { ?>
						<td><span class="label label-warning">Passif</span></td>
					<?php } elseif ($presence_array[$i][$j]->state == 'justify') { ?>
						<td><span class="label label-info">Justifié(e)</span></td>
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
						        Voulez-vous joindre une justification d'absence pour <?php echo $presence_array[0][$j]->last_name . " " .$presence_array[0][$j]->first_name; ?> pour son absence en Semaine <?php echo $week[$i]->week_number; ?>? 
						      </div>
						      <div class="modal-footer">
						      <form method="post" action="index.php?action=presenceSheet">
						      	<input type="hidden" name="justify" value="<?php echo $presence_array[0][$j]->email_student . "+" . $sheet[$i]->id_sheet; ?>">
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
				<td><span class="label label-default">Présence à prendre</span></td>
			<?php	} } } } ?>
			
		</tr>
		<?php } 
		else{ //If there isn't a present sheet?>
			<div class="alert alert-warning alert-dismissible" role="alert">
		  		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   			<p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> Il n'y a pas de feuilles de présences pour ce cours actuellement ! </p>
    		</div>
		<?php } ?>
	</table>
	<div class="col-md-2 col-md-offset-10 text-right">
		<a class="btn btn-primary" href="#top">Remonter <i class="fa fa-arrow-up" aria-hidden="true"></i> </a>
	</div>
</div>
<?php } //Close third condition ?>
<?php } //Close second condition?>
<?php } //Close first condition?>
</section>