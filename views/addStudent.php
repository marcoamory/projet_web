<section id="add_student" class="container-fluid">
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
				Ajouter un étudiant à la séance
			</div>
		</div>
		<div class="panel-body">
			<form method="post" action="index.php?action=teacher&message=add_student">
				<div class="col-md-6 col-md-offset-2">
					<input type="text" class="form-control" name="add_student_name" placeholder="Nom de l'étudiant" required />
				</div>
				<div class="col-md-1">
					<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
					<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
					<input type="hidden" name='week' value="<?php if(isset($week_number)) echo $week_number; else echo $current_week_number; ?>">
					<input type="hidden" name="session" value="<?php echo $session; ?>"/>
					<input type="hidden" name="presence_type" value="<?php echo $presence_type; ?>">
					<input type="submit" value="Rechercher" class="btn btn-success" />
				</div>
			</form>
		</div>
	</div>
</div>
<?php if(isset($students_prospect) AND !empty($students_prospect)){ ?>
<div class="row">
	<form action="index.php?action=teacher" method="post">
			<input type="hidden" name="bloc" value="<?php echo $bloc; ?>"/>
			<input type="hidden" name="serie" value="<?php echo $serie; ?>"/>
			<input type="hidden" name='week' value="<?php if(isset($week_number)) echo $week_number; else echo $current_week_number; ?>">
			<input type="hidden" name="session" value="<?php echo $session; ?>"/>
			<input type="hidden" name="presence_type" value="<?php echo $presence_type; ?>">
			<table class="table table-striped table-hover">
	  		<tr>
	  			<th>Nom</th>
	  			<th>Prénom</th>
	  			<th>Bloc</th>
	  			<th>Série</th>
	  			<th>Séléctionner</th>
	  		</tr>
	  		<?php foreach($students_prospect as $element) { ?>
	  		<tr>
	  			<td><?php echo $element->getLastName(); ?></td>
	  			<td><?php echo $element->getFirstName(); ?> </td>
	  			<td><?php echo $element->getBloc(); ?> </td>
	  			<td><?php echo $element->getSerie(); ?> </td>
	  			<input type="hidden" name="add_student_email" value="<?php echo $element->getEmail(); ?>">
	  			<td><input type="submit" value="Ajouter" class="btn btn-success"/></td>
	  		</tr>
	  		<?php } ?>
	  	</table>
	</form>
</div>
<?php } //Close condition ?>
</section>