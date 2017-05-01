<section class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Introduire UE/AA</h3>
				</div>
				<div class="panel-body">
					<form enctype="multipart/form-data" action="index.php?action=blocsManager" method="post">
						<h6>Bloc numéro :</h6> 
						<input type="text" name="blocNumber" placeholder="numéro bloc" />
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
						<input class="center-block upload" type="file" name="lessons_csv" />
						<input type="submit" value="Envoyer programmeX.csv" />
					</form>
		  		</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Nettoyer les données annuelles</h3>
				</div>
				<div class="panel-body">
					<form action="index.php?action=blocsManager" method="post">
						<?php 
						/*
						<h4 class="text-center">
							Etudiants
							<input type="radio" name="wipeChoice" value="students" />
						</h4>
						<h4 class="text-center">
							Présences
							<input type="radio" name="wipeChoice" value="presences" />
						</h4>
						<h4 class="text-center">
							Séries
							<input type="radio" name="wipeChoice" value="serie" />
						</h4>
						<h4 class="text-center">
							UE/AA
							<input type="radio" name="wipeChoice" value="lessons" />
						</h4>
						<h4 class="text-center">
							Professeurs
							<input type="radio" name="wipeChoice" value="teachers" />
						</h4>
						<h4 class="text-center">
							Agenda
							<input type="radio" name="wipeChoice" value="agenda" />
						</h4>
						*/
						?>
						<h4 class="text-center">
							Carwash
							<input type="radio" name="wipeChoice" value="total" />
						</h4>
						<div class="text-center">
							<input type="submit" value="Nettoyer la sélection" />
						</div>
					</form>
		  		</div>
			</div>
		</div>
	</div>
</section>
<?php 
	if(!empty($_SESSION['notificationSuccess'])){?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	 	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notificationSuccess']?> ! </p>
		 </div>
	<?php }
	elseif(!empty($_SESSION['notificationError'])){?>
    	<div class="alert alert-danger alert-dismissible" role="alert">
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   	 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notificationError']?> ! </p>
    	</div>
<?php }?>