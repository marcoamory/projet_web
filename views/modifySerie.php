<section id="bloc_manager" class="container-fluid">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title text-center">Série <?php echo $serie->get_number()?></h3>
			</div>
			<div class="panel-body">
				<form action="index.php?action=blocManager" method="post">
					<table class="table">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Prénom</th>
								<th>Email</th>
								<th>Série</th>
								<th>Bloc</th>
							</tr>
						</thead>
						<tbody>
						<?php for ($i=0;$i<count($students_array);$i++) {?>
							<tr>
								<td> <?php echo $students_array[$i]->getLastName()?></th>
								<td> <?php echo $students_array[$i]->getFirstName()?></th>
								<td> <?php echo $students_array[$i]->getEmail()?></th>
								<td> <?php echo $students_array[$i]->getSerie()?></th>
								<td> <?php echo $students_array[$i]->getBloc()?></th>
								<td> 
									<input type="text" name="new_serie[]" size="30" placeholder="nouvelle série"/>
									<input type="hidden" name="students_modified[]" value="<?php echo $students_array[$i]->getEmail()?>"/>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<input type="submit" name="update_students" value="modifier" />
				</form>
			</div>
		</div>
	</div>
</section>