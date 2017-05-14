<section id="bloc_manager" class="container-fluid">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php if(isset($serie)){?>
					<h3 class="panel-title text-center">Série <?php echo $serie->get_number()?></h3>
				<?php } else {?>
					<h3 class="panel-title text-center">Placement des étudiants sans Série</h3>
				<?php }?>
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
									<select name='new_serie[]' class="form-control">
										<?php if(!isset($serie)){?>
											<option value="null">Sans série</option>
						  				<?php }for($j = 0; $j < count($serie_array) ; $j++){?>
						  					<option value="<?php echo $serie_array[$j]->get_number()?>" 
						  									<?php if(isset($serie)){ 
						  											if($serie->get_number()==$serie_array[$j]->get_number()){
						  												echo " selected ";
						  											}
						  										  }?>>
						  									<?php echo "Série".$serie_array[$j]->get_number()?>
						  					</option>
						  				<?php }?>
						  			</select>
									<input type="hidden" name="students_modified[]" value="<?php echo $students_array[$i]->getEmail()?>"/>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<div class="text-right">
						<button type="submit" name="update_students"  class="btn btn-warning">Modifier <i class="fa fa-refresh" aria-hidden="true"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>