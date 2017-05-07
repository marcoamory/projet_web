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
<?php if((isset($series) AND !empty($series)) OR (isset($serie))){ ?>
<div class="row">
	<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=presenceSheet">
	    		<div class="col-md-3 col-md-offset-2">
	    			<select name="serie" class="form-control">
	    			<?php foreach ($series as $element){ ?>
	    				<option value="<?php echo $element; ?>" <?php if(isset($serie) AND $serie == $element) echo 'selected'; ?>>Serie <?php echo $element; ?></option>
	    			<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-3">
	    			<select id="week_select" class="form-control">
						<?php for($i=1; $i < $current_week_number; $i++){ ?>
							<option value="<?php echo $i; ?>">Semaine <?php echo $i; ?></option>
						<?php } ?>
							<option value="<?php echo $current_week_number; ?>" selected>Semaine courante</option>
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
<?php if(isset($serie) AND !empty($serie)) { ?>
<div class="row">
	<table class="table table-striped table-hover">
		<tr>
			<th>#</th>
			<?php foreach($sessions as $element) { ?>
			<th><?php echo $element->name; ?></th>
			<?php } ?>
		</tr>
		<tr>
			<th>NOM Prénom</th>
			<?php foreach($sessions as $element) { ?>
			<th><?php echo $element->time_slot; ?></th>
			<?php } ?>
		</tr>
		<?php foreach ($students as $element) { ?>
		<tr>
			<td><?php echo $element->getLastName() . " " . $element->getFirstName(); ?></td>
		</tr>	
		<?php } ?>

	</table>
</div>
<?php } ?>
<?php } ?>
</section>