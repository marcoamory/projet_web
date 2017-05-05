<section id='teacher_view' class="container-fluid">
<div class="row">
	<div class="panel panel-default">
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
	<div class="panel panel-default">
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
	<form action="index.php?action=teacher" method="post">
		<table class="table table-striped table-hover">
	  		<tr>
	  			<th>#</th>
	  			<th>Last Name</th>
	  			<th>First Name</th>
	  			<th>Bloc</th>
	  			<th>Serie</th>
	  			<th>Présences</th>
	  		</tr>
	  		<?php for($i = 0; $i<count($students); $i++) { ?>
	  		<tr>
	  			<td><?php echo $i+1; ?></td>
	  			<td><?php echo $students[$i]->getLastName(); ?></td>
	  			<td><?php echo $students[$i]->getFirstName(); ?> </td>
	  			<td><?php echo $students[$i]->getBloc(); ?> </td>
	  			<td><?php echo $students[$i]->getSerie(); ?> </td>
	  			<td><div class="btn-group" data-toggle="buttons" id="inputBlocSelect">
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence" value="present">Present(e)
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence" value="absent">Absent(e)
						</label>
						<label class="btn btn-default btn-sm">
							<input type="radio" name="presence" value="passif">Passif
						</label>
				</div>
				<div class="btn-group" data-toggle="buttons" id="inputNoteSelect">
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="0">0
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="1">1
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="2">2
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="3">3
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="4">4
						</label>
						<label class="btn btn-primary btn-sm">
							<input type="radio" name="note" value="5">5
						</label>
				</div></td>
	  		</tr>
	  		<?php } ?>
		</table>
	</form>
</div>
<?php } ?> 
<?php } ?> 
</section>

