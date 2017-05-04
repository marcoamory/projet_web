<section id="bloc_manager" class="container-fluid">
	<div class="row">
		<?php if(!isset($_SESSION['modify_serie'])){?>
			<div class="col-md-4">
				<div class="panel panel-primary">
			  		<div class="panel-heading">
			   			 <h3 class="panel-title text-center">Créer Série</h3>
			  		</div>
			  		<div class="panel-body">
			   			 <form action="index.php?action=blocManager" method="post">
							<input type="text" name="nb_series" size="30" placeholder="nombre de séries voulues"/>
							<input type="submit" value="Créer" />
						</form>
			  		</div>
			  		<div class="panel-heading">
			   			 <h3 class="panel-title text-center">Modifier Série</h3>
			  		</div>
			  		<div class="panel-body">
			   			 <form action="index.php?action=blocManager" method="post">
							<input type="text" name="modify_serie" size="30" placeholder="série à modifier"/>
							<input type="text" name="bloc_serie_modify" size="30" placeholder="bloc de la série à supprimer"/>
							<input type="submit" value="Modifier" />
						</form>
			  		</div>
			  		<div class="panel-heading">
			   			 <h3 class="panel-title text-center">Supprimer Série</h3>
			  		</div>
			  		<div class="panel-body">
			   			 <form action="index.php?action=blocManager" method="post">
							<input type="text" name="delete_serie" size="30" placeholder="série à supprimer"/>
							<input type="text" name="bloc_serie_delete" size="30" placeholder="bloc de la série à supprimer"/>
							<input type="submit" value="Supprimer" />
						</form>
			  		</div>
				</div>
			</div>
		<?php 
		} 
		else{?>
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
										<th> <?php echo $students_array[$i]->getLastName()?></th>
										<th> <?php echo $students_array[$i]->getFirstName()?></th>
										<th> <?php echo $students_array[$i]->getEmail()?></th>
										<th> <?php echo $students_array[$i]->getSerie()?></th>
										<th> <?php echo $students_array[$i]->getBloc()?></th>
										<th> <input type="text" name="new_serie[]" size="30" placeholder="nouvelle série"/></th>
									</tr>
								<?php } ?>
								</tbody>
							</table>
							<input type="submit" value="modifier" />
						</form>
					</div>
				</div>
			</div>
		<?php 
		}?>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Créer Séance type</h3>
		  		</div>
		  		<div class="panel-body">
		   			 <form action="index.php?action=blocManager" method="post">
						<input type="text" name="name" size="30" placeholder="Intitulé de la séance(non obligatoire)"/>
						<input type="text" name="lesson_fk" size="30" placeholder="UE/AA concernée"/>
						<input type="text" name="serie_fk" size="30" placeholder="Série concernée"/>
						<input type="submit" value="Créer" />
					</form>
		  		</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
		  		<div class="panel-heading">
		   			 <h3 class="panel-title text-center">Introduire UE/AA</h3>
		  		</div>
		  		<div class="panel-body">
		   			 <form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input class="center-block upload" type="file" name="lessons_csv" />
						<input type="submit" value="Envoyer programmeX.csv" />
					</form>
		  		</div>
			</div>
		</div>
	</div>
	<?php 
	if(!empty($_SESSION['notification_success'])){?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_success']?> ! </p>
		 </div>
	<?php }
	elseif(!empty($_SESSION['notification_error'])){?>
    	<div class="alert alert-danger alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_error']?> ! </p>
    	</div>
<?php }
elseif(!empty($_SESSION['notification_warning'])){?>
    	<div class="alert alert-warning alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification_warning']?> ! </p>
    	</div>
<?php } ?>
</section>
