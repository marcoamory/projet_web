<section id='teacher_view' class="container-fluid">
<span id=#top></span>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-title text-center">
				<h4>Présences au cours <?php if(isset($lesson) AND !empty($lesson)) echo '"' . $lesson .'"';?> du <?php echo date("d/m/Y"); ?> <?php if(isset($current_week_name) AND !empty($current_week_name)) echo "[" . $current_week_name . "]"; ?></h4>
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
<?php if((isset($series) AND !empty($series)) OR (isset($serie))){ ?>
<div class="row">
	<div class="panel panel-primary">
	  <div class="panel-body">
	    	<form method="post" action="index.php?action=teacher">
	    		<div class="col-md-3 col-md-offset-2">
	    			<select name="serie" class="form-control">
	    			<?php foreach ($series as $element){ ?>
	    				<option value="<?php echo $element; ?>" <?php if(isset($serie) AND $serie == $element) echo 'selected'; ?>>Serie <?php echo $element; ?></option>
	    			<?php } ?>
	    			</select>
	    		</div>
	    		<div class="col-md-3">
	    			<select name="lesson" class="form-control">
	    				<?php foreach ($lessons as $element){ ?>
	    				<option value="<?php echo $element; ?>" <?php if(isset($lesson) AND $lesson == $element) echo 'selected'; ?>><?php echo $element; ?></option>
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
<?php if(isset($serie) AND !empty($serie) AND isset($lesson) AND !empty($lesson)){ ?>
<div class="row">
	<div class="panel panel-primary">
		<div class="panel-body">
			<form method="post" action="index.php?action=teacher">
			<div class="col-md-1 col-md-offset-1 text-right">
				<label for="week_select"><?php echo strtoupper($current_quadri); ?>:</label>
			</div>
				<div class="col-md-6">
					<select id="week_select" class="form-control">
						<?php for($i=1; $i < $current_week_number; $i++){ ?>
							<option value="<?php echo $i; ?>">Semaine <?php echo $i; ?></option>
						<?php } ?>
							<option value="<?php echo $current_week_number; ?>" selected>Semaine courante</option>
					</select>
				</div>
				<div class="col-md-1">
					<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
					<input type="hidden" name="lesson" value="<?php echo $lesson ?>"/>
					<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
					<input type="submit" value="Rechercher" class="btn btn-success"/>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<form action="index.php?action=teacher" method="post">
		<table class="table table-striped table-hover">
	  		<tr>
	  			<th>#</th>
	  			<th>Last Name</th>
	  			<th>First Name</th>
	  			<th>Bloc</th>
	  			<th>Serie</th>
	  			<th>Présences</th>
	  			<th>Notes</th>
	  		</tr>
	  		<?php for($i = 0; $i<count($students); $i++) { ?>
	  		<tr>
	  			<td><?php echo $i+1; ?></td>
	  			<td><?php echo $students[$i]->getLastName(); ?></td>
	  			<td><?php echo $students[$i]->getFirstName(); ?> </td>
	  			<td><?php echo $students[$i]->getBloc(); ?> </td>
	  			<td><?php echo $students[$i]->getSerie(); ?> </td>
	  			<td><div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="present">Present(e)
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="absent">Absent(e)
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence<?php echo $i;?>" value="passif">Passif
						</label>
				</div>
				</td>
				<td>
				<div class="btn-group" data-toggle="buttons">
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
				</div></td>
	  		</tr>
	  		<?php } ?>
		</table>
		<div class="col-md-2 col-md-offset-7 text-right">
			<a class="btn btn-primary" href="#top">Remonter <i class="fa fa-arrow-up" aria-hidden="true"></i> </a>
		</div>
		<div class="col-md-1">
			<button type="submit" class="btn btn-success">Enregistrer <i class='fa fa-floppy-o' aria-hidden='true'></i></button> 
		</div>
	</form>
</div>
<?php } ?> 
<?php } ?> 
</section>

