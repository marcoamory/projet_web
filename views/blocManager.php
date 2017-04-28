<section class="container-fluid">
	<div class="text-center">
		<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
			<fieldset>Introduire UE/AA :
			<?php if(isset($_SESSION['responsibility']) AND ($_SESSION['responsibility'] == 'blocs')){ ?>
				<h6>Bloc numéro :</h6> 
				<input type="text" name="blocNumber" placeholder="numéro bloc" />
			<?php }?>
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="lessons_csv" />
			<input type="submit" value="Envoyer programmeX.csv" />
			</fieldset>
		</form>
		<form enctype="multipart/form-data" action="index.php?action=blocManager" method="post">
			<fieldset>Introduire Etudiants :
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000000" />
			<input class="center-block" id="upload" type="file" name="students_csv" />
			<input type="submit" value="Envoyer Etudiants.csv" />
			</fieldset>
		</form>
		<?php if(isset($_SESSION['notification'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		   		 <p><i class="fa fa-check fa-2x" aria-hidden="true"></i> <?php echo $_SESSION['notification']?> ! </p>
		    </div>
		<?php }?>
	</div>
</section>