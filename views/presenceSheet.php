<section id="presence_sheet" class="container-fluit">
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
<?php if((isset($series) AND !empty($series)) OR (isset($serie))){ ?> <!-- First condition -->
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
<?php if(isset($serie) AND !empty($serie)) { ?> <!-- Second condition -->
<div class="row">
<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=presenceSheet">
	    		<div class="col-md-6 col-md-offset-2">
	    			<select name="session" class="form-control">
	    				<?php foreach ($sessions as $element){ ?>
	    				<option value="<?php echo $element->id_session; ?>" <?php if(isset($session) AND $session == $element->id_session) echo 'selected'; ?>><?php echo $element->name . " " . $element->time_slot; ?></option>
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
<?php if(isset($session) AND !empty($session)) { ?> <!-- Third condition -->
<div class="row">
	<table class="table table-striped table-hover">
		<tr>
			<th>NOM Prénom</th>
			<?php for($i=0; $i<count($week);$i++){ 
				if($week[$i]->week_number < $current_week_number || $week[$i]->week_number == $current_week_number){ ?>
			<th><?php echo "Semaine" . $week[$i]->week_number; ?></th>

			<?php } } ?>
		</tr>
		<?php for($j=0; $j<count($presence_array[0]); $j++) { ?>
		<tr>
			<td><?php echo $presence_array[0][$j]->last_name . " " . $presence_array[0][$j]->first_name; ?>
			<?php for($i=0; $i<count($sheet); $i++) {
				if($sheet[$i]){ ?>
			<td><?php echo $presence_array[$i][$j]->state; ?></td>
			<?php } } ?>
		</tr>
		<?php } ?>
	</table>
</div>
<?php } ?> <!--Close third condition -->
<?php } ?> <!--Close second condition -->
<?php } ?> <!--Close first condition -->
</section>